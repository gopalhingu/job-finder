<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\QuestionCategory;
use App\Rules\MinString;
use App\Rules\MaxString;

class QuestionCategoriesController extends Controller
{
    /**
     * View Function to display Question Categories list view page
     *
     * @return html/string
     */
    public function listView()
    {
        $data['page'] = __('message.question_categories');
        $data['menu'] = 'question_categories';
        return view('employer.question-categories.list', $data);
    }

    /**
     * Function to get data for Question Categories jquery datatable
     *
     * @return json
     */
    public function data(Request $request)
    {
        echo json_encode(QuestionCategory::questionCategoriesList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $question_category_id
     * @return html/string
     */
    public function createOrEdit($question_category_id = NULL)
    {
        $question_category = QuestionCategory::get('question_category_id', $question_category_id);
        echo view('employer.question-categories.create-or-edit', compact('question_category'))->render();
    }

    /**
     * Function (for ajax) to process Question Category create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('question_category_id') ? $request->input('question_category_id') : false;
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

        if (QuestionCategory::valueExist('title', $request->input('title'), $edit)) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.question_category_already_exist')))
            ));
        } else {
            QuestionCategory::store($request->all(), $edit);
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => __('message.question_category') . ($edit ? __('message.updated') : __('message.created'))))
            ));
        }
    }

    /**
     * Function (for ajax) to process Question Category change status request
     *
     * @param integer $question_category_id
     * @param string $status
     * @return void
     */
    public function changeStatus($question_category_id = null, $status = null)
    {
        $this->checkIfDemo();
        QuestionCategory::changeStatus($question_category_id, $status);
    }

    /**
     * Function (for ajax) to process Question Category bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        QuestionCategory::bulkAction($request->all());
    }

    /**
     * Function (for ajax) to process Question Category delete request
     *
     * @param integer $question_category_id
     * @return void
     */
    public function delete($question_category_id)
    {
        $this->checkIfDemo();
        QuestionCategory::remove($question_category_id);
    }
}
