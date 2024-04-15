<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\InterviewCategory;
use App\Rules\MinString;
use App\Rules\MaxString;

class InterviewCategoriesController extends Controller
{
    /**
     * View Function to display Interview Categories list view page
     *
     * @return html/string
     */
    public function listView()
    {
        $data['page'] = __('message.interview_categories');
        $data['menu'] = 'interview_categories';
        return view('employer.interview-categories.list', $data);
    }

    /**
     * Function to get data for Interview Categories jquery datatable
     *
     * @return json
     */
    public function data(Request $request)
    {
        echo json_encode(InterviewCategory::interviewCategoriesList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $interview_category_id
     * @return html/string
     */
    public function createOrEdit($interview_category_id = NULL)
    {
        $interview_category = objToArr(InterviewCategory::get('interview_category_id', $interview_category_id));
        echo view('employer.interview-categories.create-or-edit', compact('interview_category'))->render();
    }

    /**
     * Function (for ajax) to process Interview Category create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('interview_category_id') ? $request->input('interview_category_id') : false;
        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        } 

        if (InterviewCategory::valueExist('title', $request->input('title'), $edit)) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.interview_category_already_exist')))
            ));
        } else {
            InterviewCategory::store($request->all(), $edit);
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' =>  __('message.interview_category') . ($edit ? __('message.updated') : __('message.created'))))
            ));
        }
    }

    /**
     * Function (for ajax) to process Interview Category change status request
     *
     * @param integer $interview_category_id
     * @param string $status
     * @return void
     */
    public function changeStatus($interview_category_id = null, $status = null)
    {
        $this->checkIfDemo();
        InterviewCategory::changeStatus($interview_category_id, $status);
    }

    /**
     * Function (for ajax) to process Interview Category bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        InterviewCategory::bulkAction($request->all());
    }

    /**
     * Function (for ajax) to process Interview Category delete request
     *
     * @param integer $interview_category_id
     * @return void
     */
    public function delete($interview_category_id)
    {
        $this->checkIfDemo();
        InterviewCategory::remove($interview_category_id);
    }
}
