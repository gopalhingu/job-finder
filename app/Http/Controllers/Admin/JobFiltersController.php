<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\JobFilter;
use App\Models\Admin\Employer;
use App\Rules\MinString;
use App\Rules\MaxString;

class JobFiltersController extends Controller
{
    /**
     * View Function to display job_filters list view page
     *
     * @return html/string
     */
    public function listView()
    {
        $data['page'] = __('message.job_filters');
        $data['menu'] = 'job_filters';
        $data['employers'] = objToArr(Employer::getAll());
        return view('admin.job-filters.list', $data);
    }

    /**
     * Function to get data for job_filters jquery datatable
     *
     * @return json
     */
    public function data(Request $request)
    {
        echo json_encode(JobFilter::job_filtersList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $job_filter_id
     * @return html/string
     */
    public function createOrEdit($job_filter_id = NULL)
    {
        $job_filter = objToArr(JobFilter::getJobFilter('job_filter_id', $job_filter_id));
        echo view('admin.job-filters.create-or-edit', compact('job_filter'))->render();
    }

    /**
     * Function (for ajax) to process job_filter create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('job_filter_id') ? $request->input('job_filter_id') : false;

        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $rules['order'] = ['required', new MinString(1), new MaxString(3)];
        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'order.required' => __('validation.required'),
            'order.min' => __('validation.min_string'),
            'order.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $data = JobFilter::storeJobFilter($request->all(), $edit);
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.job_filter') . ($edit ? __('message.updated') : __('message.created')))),
            'data' => $data
        ));
    }

    /**
     * View Function (for ajax) to display update values view page via modal
     *
     * @param integer $job_filter_id
     * @return html/string
     */
    public function updateValuesForm($job_filter_id = NULL)
    {
        $values = objToArr(JobFilter::getAllValues($job_filter_id));
        echo view('admin.job-filters.values', compact('values', 'job_filter_id'))->render();
    }

    /**
     * View Function (for ajax) to display new value field
     *
     * @param integer $job_filter_id
     * @return html/string
     */
    public function newValue($job_filter_id = NULL)
    {
        echo view('admin.job-filters.value')->render();
    }

    /**
     * Function (for ajax) to process job_filter update values form request
     *
     * @return redirect
     */
    public function updateValues(Request $request)
    {
        $this->checkIfDemo();

        $rules['values.*'] = ['required', new MinString(2), new MaxString(50)];
        $validator = Validator::make($request->all(), $rules, [
            'values.required' => __('validation.required'),
            'values.min' => __('validation.min_string'),
            'values.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }        

        $data = JobFilter::storeJobFilterValue($request->all());
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.job_filter') . __('message.updated'))),
            'data' => $data
        ));
    }

    /**
     * Function (for ajax) to process job_filter change status request
     *
     * @param integer $job_filter_id
     * @param string $status
     * @return void
     */
    public function changeStatus($job_filter_id = null, $status = null)
    {
        $this->checkIfDemo();

        JobFilter::changeStatus($job_filter_id, $status);
    }

    /**
     * Function (for ajax) to process job_filter bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        JobFilter::bulkAction($request->all());
    }

    /**
     * Function (for ajax) to process job_filter delete request
     *
     * @param integer $job_filter_id
     * @return void
     */
    public function delete($job_filter_id)
    {
        $this->checkIfDemo();
        JobFilter::remove($job_filter_id);
    }
}
