<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\JobTags;

class JobTagsController extends Controller
{
    /**
     * Function (for ajax) to display roles list
     *
     * @return view
     */
    public function listView()
    {
        if (!allowedTo('view_job_tag')) {
            die(__('message.not_allowed'));
        }

        $data['page'] = __('message.job_tag');
        $data['menu'] = 'job tags';
        $data['jobTags'] = JobTags::getAll('all');
        return view('admin.job-tags.main', $data);
    }

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $id
     * @return html/string
     */
    public function createOrEdit($id = NULL)
    {
        $jobTags = objToArr(JobTags::getJobTags('id', $id));
        echo view('admin.job-tags.create-or-edit', compact('jobTags'))->render();
    }

    /**
     * Function (for ajax) to process role create or edit form request
     *
     * @return redirect
     */
    public function saveJobTags(Request $request)
    {
        $this->checkIfDemo();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => __('validation.required'),
        ]);

        if ($validator->fails()) {
            $errors =  $validator->messages()->toArray();
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }        

        if (JobTags::valueExist('name', $request->input('name'), $request->input('id'))) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.job_tag_already_exist')))
            ));
        } else {
            $data = JobTags::storeJobTags($request->all(), $request->input('id'));
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => __('message.job_tag').' '.($request->input('id') ? __('message.updated') : __('message.created')))),
                'data' => $data
            ));
        }        
    }

    /**
     * Function (for ajax) to process role delete request
     *
     * @param integer $id
     * @return void
     */
    public function delete($id)
    {
        $this->checkIfDemo();
        JobTags::remove($id);
    }

}