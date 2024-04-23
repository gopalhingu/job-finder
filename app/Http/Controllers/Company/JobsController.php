<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use SimpleExcel\SimpleExcel;

use App\Models\Company\Candidate;
use App\Models\Company\Department;
use App\Models\Company\Job;
use App\Models\Company\APIjob;
use App\Models\Company\JobFilter;
use App\Models\Company\Traite;
use App\Models\Company\Quiz;
use App\Rules\MinString;
use App\Rules\MaxString;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class JobsController extends Controller
{
    /**
     * View Function to display jobs list view page
     *
     * @return html/string
     */
    public function listView()
    {
        $data['page'] = __('message.jobs');
        $data['menu'] = 'jobs';
        $data['departments'] = objToArr(Department::getAll());
        $data['job_filters'] = objToArr(JobFilter::getAll());
        return view('company.jobs.list', $data);
    }

    public function allJobListView()
    {
        // Get the current date
        $currentDate = Carbon::now();
        // Subtract 30 days from the current date to get the start date
        $startDate = $currentDate->subDays(30);

        $queryCompany = DB::connection('apiDatabase')->table('api_jobs')->select("id", "company_name")->groupBy("company_name")->get()->toArray();
        $queryCategory = DB::connection('apiDatabase')->table('categories')
                            ->select("categories.id as c_id", "categories.name as c_name")
                            ->join("api_jobs", "api_jobs.category_id", "categories.id")
                            ->where('api_jobs.job_update', '1')
                            ->whereDate('api_jobs.updated_at', '>=', $startDate)
                            ->groupBy("categories.name")->get()->toArray();

        // echo "<pre>";print_r($queryCategory);die;

        $data['page'] = __('message.all_job');
        $data['menu'] = 'all_job';
        $data['company'] = $queryCompany;
        $data['category'] = $queryCategory;
        return view('company.jobs.all-list', $data);
    }

    public function jobTransfer(Request $request) {

        $userEmp = session()->all();
        $emp_id = $userEmp['employer']['employer_id'] ? $userEmp['employer']['employer_id'] : '';

        if(isset($request->id) && $request->id != '' && $emp_id != '') {
            $id = decode($request->id);
            $job = DB::connection('apiDatabase')->table('api_jobs')->select("*")->where("id", $id)->first();
            if($job) {
                $status = true;
                $insData = [
                    'employer_id' => $emp_id,
                    'department_id' => $job->category_id,
                    'title' => $job->job_title,
                    'description' => $job->job_description,
                    'slug' => Job::getSlug($job->job_title, '', false),
                    'api_job_id' => $id,
                    'created_at' => date('Y-m-d G:i:s'),
                ];
                // echo "<pre>";print_r($insData);die;
                $returnID = Job::insert($insData);
                if ($returnID) {
                    $status = true;
                } else {
                    $status = false;
                }
            } else {
                $status = false;
            }
        } else {
            $status = true;
        }
        return response()->json(['status' => $status]);
        // echo "<pre>";print_r($_REQUEST);die;
    }

    /**
     * Function to get data for jobs jquery datatable
     *
     * @return json
     */
    public function data(Request $request)
    {
        echo json_encode(Job::jobsList($request->all()));
    } 
    
    public function allData(Request $request)
    {
        echo json_encode(APIjob::jobsList($request->all()));
    }

    /**
     * View Function (for ajax) to display create or edit job
     *
     * @param integer $job_id
     * @return html/string
     */
    public function createOrEdit($job_id = NULL)
    {
        $data['job'] = objToArr(Job::getJob('jobs.job_id', $job_id));
        $data['departments'] = objToArr(Department::getAll());
        $data['traites'] = objToArr(Traite::getAll());
        $data['fields'] = objToArr(Job::getFields($job_id));
        $data['quizes'] = objToArr(Quiz::getAll(true));
        $data['job_filters'] = objToArr(JobFilter::getAll());
        $data['page'] = __('message.job');
        $data['menu'] = 'job';
        return view('company.jobs.create-or-edit', $data);
    }

    /**
     * Function (for ajax) to process job create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('job_id') ? $request->input('job_id') : false;

        // $this->checkActiveJobs($edit, $request->input('status'));

        $rules['title'] = ['required', new MinString(2), new MaxString(200)];
        $rules['slug'] = ['alpha_dash', new MinString(2), new MaxString(200)];
        $rules['description'] = ['required', new MinString(50), new MaxString(10000)];
        $rules['labels.*'] = ['required', new MaxString(200)];
        $rules['values.*'] = ['required', new MaxString(200)];

        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'slug.alpha_dash' => __('validation.alpha_dash'),
            'slug.min' => __('validation.min_string'),
            'slug.max' => __('validation.max_string'),
            'description.required' => __('validation.required'),
            'description.min' => __('validation.min_string'),
            'description.max' => __('validation.max_string'),
            'labels.max' => __('validation.max_string'),
            'values.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $data = $request->all();
        $data['description'] = sanitizeHtmlTemplates(templateInput('description'));
        $job_id = Job::storeJob($data, $edit);
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.job') . ($edit ? __('message.updated') : __('message.created')))),
            'data' => $job_id
        ));
    }

    /**
     * Function (for ajax) to process job change status request
     *
     * @param integer $job_id
     * @param string $status
     * @return void
     */
    public function changeStatus($job_id = null, $status = null)
    {
        $this->checkIfDemo();

        if ($status == '0') {
            $this->checkActiveJobs();
        }

        Job::changeStatus($job_id, $status);
    }

    /**
     * Function (for ajax) to process job delete request
     *
     * @param integer $job_id
     * @return void
     */
    public function delete($job_id)
    {
        $this->checkIfDemo();
        Job::remove($job_id);
    }

    /**
     * Post Function to download jobs data in excel
     *
     * @return void
     */
    public function excel(Request $request)
    {
        $data = Job::getJobsForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');                    
        $excel->writer->setData($data);
        $excel->writer->saveFile('jobs'); 
        exit;
    }


    /**
     * Function (for ajax) to process add custom field request
     *
     * @return void
     */
    public function addCustomField()
    {
        $data['field'] = array('custom_field_id' => '', 'label' => '', 'value' => '');
        echo view('employer.jobs.custom-field', $data)->render();
    }

    /**
     * Function (for ajax) to process remove custom field request
     *
     * @param integer $custom_field_id
     * @return void
     */
    public function removeCustomField($custom_field_id)
    {
        $this->checkIfDemo();
        Job::removeCustomField($custom_field_id);
    }

    /**
     * Function to check active membership content
     *
     * @return html
     */
    private function checkActiveJobs($id = '', $status = '')
    {
        if ($id != '' && $status == '0') {
            return false;
        }

        //Checking if allowed in membership
        $totalActiveJobs = Job::getTotalJobs($id);
        $totalAllowedActiveJobs = empMembership(employerId(), 'active_jobs');
        if ($totalAllowedActiveJobs == '-1') {
            return false;
        }                
        if ($totalActiveJobs >= $totalAllowedActiveJobs) {
            $detail = '<br />'.__('message.current_active').' : '.$totalActiveJobs;
            $detail .= '<br />'.__('message.allowed_in_membership').' : '.$totalAllowedActiveJobs.'<br />';
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.active_jobs_limit_message').$detail))
            )));
        }        
    }

}
