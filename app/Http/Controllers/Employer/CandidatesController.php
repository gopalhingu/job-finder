<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Admin\Employer;
use App\Models\Admin\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Candidate;
use App\Rules\MinString;
use App\Rules\MaxString;

use SimpleExcel\SimpleExcel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;

class CandidatesController extends Controller
{
    /**
     * View Function to display candidates list view page
     *
     * @return html/string
     */
    public function listView()
    {
        $data['page'] = __('message.candidates');
        $data['menu'] = 'all_candidates';
        return view('employer.candidates.list', $data);
    }

    /**
     * Function to get data for candidates jquery datatable
     *
     * @return json
     */
    public function data(Request $request)
    {
        echo json_encode(Candidate::candidatesList($request->all()));
    }

    /**
     * View Function to display candidates list view page
     *
     * @return html/string
     */
    public function listViewMy()
    {
        $data['page'] = __('message.candidates');
        $data['menu'] = 'my_candidates';
        return view('employer.candidates.my-list', $data);
    }

    /**
     * Function to get data for candidates jquery datatable
     *
     * @return json
     */
    public function dataMy(Request $request)
    {
        echo json_encode(Candidate::myCandidatesList($request->all()));
    }

    /**
     * View Function to display candidates list view page
     *
     * @return html/string
     */
    public function listViewMatched()
    {
        $data['page'] = __('message.candidates');
        $data['menu'] = 'matched_candidates';
        return view('employer.candidates.matched-list', $data);
    }

    /**
     * Function to get data for candidates jquery datatable
     *
     * @return json
     */
    public function dataMatched(Request $request)
    {
        echo json_encode(Candidate::matchedCandidatesList($request->all()));
    }

    public function sendMail($candidate_id, $job_id) {
        try {
            $employer = Employer::where('employer_id', employerId())->first()->toArray();
            $candidate = Candidate::where("candidate_id", decode($candidate_id))->first()->toArray();
            $job = Job::where("job_id", decode($job_id))->first()->toArray();

            $tagsWithValues = array(
                '((site_link))' => url('/'),
                '((site_logo))' => setting('site_logo'),
                '((first_name))' => $candidate['first_name'],
                '((last_name))' => $candidate['last_name'],
                '((job_title))' => $job['title'],
                '((job_description))' => $job['description'],
                '((link))' => route('front-jobs-detail', $job['slug']),
                '((job_description))' => $job['description'],
                '((employer_first_name))' => $employer['first_name'],
                '((employer_last_name))' => $employer['last_name'],
                '((employer_phone_number))' => $employer['phone1'] ?? $employer['phone1'] ?? '',
                '((employer_email_address))' => $employer['email'],
            );
            $message = replaceTagsInTemplate2(setting('matched_candidate_send_email'), $tagsWithValues);
            $subject = setting('site_name').' : '.__('message.matched_job');
            $this->sendEmail($message, $candidate['email'], $subject);

            DB::table("jobs_candidates")->insert(['job_id' => $job['job_id'], 'candidate_id' => $candidate['candidate_id']]);
            
            return response()->json(['status' => true, 'message' => 'Send Mail Successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Function (for ajax) to process candidate bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        Candidate::bulkAction($request->all());
    }

    /**
     * Function (for ajax) to display candidate resume
     *
     * @param integer $candidate_id
     * @return void
     */
    public function resume($candidate_id)
    {
        $resume = Candidate::getCompleteResume($candidate_id);
        if ($resume) {
            $data['resume_id'] = $resume['resume_id'];
            $data['resume_file'] = $resume['file'];
            $data['type'] = $resume['type'];
            $data['file'] = $resume['file'];
            $data['resume'] = $resume;
            echo view('employer.candidates.resume', $data)->render();
        } else {
            echo __('message.no_resumes_found');
        }
    }

    public function jobDetail($job_id)
    {
        $job = Job::where('job_id', decode($job_id))->first()->toArray();
        if ($job) {
            $data['job_id'] = $job['job_id'];
            $data['job'] = $job;
            echo view('employer.jobs.job-detail', $data)->render();
        } else {
            echo __('message.no_jobs_found');
        }
    }

    /**
     * Function (for ajax) to display form to send email to candidate
     *
     * @return void
     */
    public function messageView()
    {
        echo view('employer.candidates.message')->render();
    }

    /**
     * Function (for ajax) to send email to candidate
     *
     * @return void
     */
    public function message(Request $request)
    {
        $this->checkIfDemo();
        ini_set('max_execution_time', 5000);
        $data = $request->input();
        $candidates = explode(',', $data['ids']);

        $rules['msg'] = ['required', new MinString(10), new MaxString(10000)];
        $rules['subject'] = ['required', new MinString(2), new MaxString(100)];
        $validator = Validator::make($request->all(), $rules, [
            'msg.required' => __('validation.required'),
            'msg.min' => __('validation.min_string'),
            'msg.max' => __('validation.max_string'),
            'subject.required' => __('validation.required'),
            'subject.min' => __('validation.min_string'),
            'subject.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        foreach ($candidates as $candidate_id) {
            $candidate = objToArr(Candidate::getCandidate('candidate_id', decode($candidate_id)));
            $this->sendEmail(removeUselessLineBreaks($data['msg']), $candidate['email'], $data['subject']);
        }

        die(json_encode(array('success' => 'true', 'messages' => '')));
    }    

    /**
     * Post Function to download candidate resume
     *
     * @return void
     */
    public function resumeDownload(Request $request)
    {
        try {
            ini_set('max_execution_time', '0');
            $ids = explode(',', $request->input('ids'));
            $resumes = '';
            foreach ($ids as $id) {
                $data['resume'] = objToArr(Candidate::getCompleteResumeJobBoard($id));
                if (isset($data['resume']['type'])) {
                    if ($data['resume']['type'] == 'detailed') {
                        $resumes .= view('employer.candidates.resume-pdf', $data)->render();
                    } else {
                        $resumes .= "<hr />";
                        $resumes .= 'Resume of "'.$data['resume']['first_name'].' '.$data['resume']['last_name'].' ('.$data['resume']['designation'].')" is static and can be downloaded separately';
                        $resumes .= "<br /><hr />";
                    }
                } else {
                    $resumes .= 'No Record';
                } 
            }  

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($resumes);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream('Resumes.pdf');
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Post Function to download candidates data in excel
     *
     * @return void
     */
    public function candidatesExcel(Request $request)
    {
        $data = Candidate::getCandidatesForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');                    
        $excel->writer->setData($data);
        $excel->writer->saveFile('candidates'); 
    }

    public function addOrRemove($candidate_id = null) {
        $data = DB::table('employer_candidates')->where(['employer_id' => employerId(), 'candidate_id' => decode($candidate_id)])->first();
        if(!empty($data)) {
            DB::table('employer_candidates')->where(['employer_id' => employerId(), 'candidate_id' => decode($candidate_id)])->delete();
        } else {
            DB::table('employer_candidates')->insert(['employer_id' => employerId(), 'candidate_id' => decode($candidate_id)]);
        }
        return true;
    }
}
