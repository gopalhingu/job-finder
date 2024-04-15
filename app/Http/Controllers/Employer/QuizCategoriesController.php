<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\QuizCategory;
use App\Rules\MinString;
use App\Rules\MaxString;

class QuizCategoriesController extends Controller
{
    /**
     * View Function to display Quiz Categories list view page
     *
     * @return html/string
     */
    public function listView()
    {
        $data['page'] = __('message.quiz_categories');
        $data['menu'] = 'quiz_categories';
        return view('employer.quiz-categories.list', $data);
    }

    /**
     * Function to get data for Quiz Categories jquery datatable
     *
     * @return json
     */
    public function data(Request $request)
    {
        echo json_encode(QuizCategory::quizCategoriesList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $quiz_category_id
     * @return html/string
     */
    public function createOrEdit($quiz_category_id = NULL)
    {
        $quiz_category = QuizCategory::get('quiz_category_id', $quiz_category_id);
        echo view('employer.quiz-categories.create-or-edit', compact('quiz_category'))->render();
    }

    /**
     * Function (for ajax) to process Quiz Category create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('quiz_category_id') ? $request->input('quiz_category_id') : false;
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

        if (QuizCategory::valueExist('title', $request->input('title'), $edit)) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.quiz_category_already_exist')))
            ));
        } else {
            QuizCategory::store($request->all(), $edit);
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' =>  __('message.quiz_category') . ($edit ? __('message.updated') : __('message.created'))))
            ));
        }
    }

    /**
     * Function (for ajax) to process Quiz Category change status request
     *
     * @param integer $quiz_category_id
     * @param string $status
     * @return void
     */
    public function changeStatus($quiz_category_id = null, $status = null)
    {
        $this->checkIfDemo();
        QuizCategory::changeStatus($quiz_category_id, $status);
    }

    /**
     * Function (for ajax) to process Quiz Category bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        QuizCategory::bulkAction($request->all());
    }

    /**
     * Function (for ajax) to process Quiz Category delete request
     *
     * @param integer $quiz_category_id
     * @return void
     */
    public function delete($quiz_category_id)
    {
        $this->checkIfDemo();
        QuizCategory::remove($quiz_category_id);
    }
}
