<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Quiz;
use App\Models\Employer\QuizCategory;
use App\Rules\MinString;
use App\Rules\MaxString;

use SimpleExcel\SimpleExcel;
use Dompdf\Dompdf;

class QuizesController extends Controller
{
    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $quiz_id
     * @return html/string
     */
    public function createOrEdit($quiz_id = NULL)
    {
        $data['quiz'] = objToArr(Quiz::get('quiz_id', $quiz_id));
        $data['quiz_categories'] = QuizCategory::getAll();
        echo view('employer.quizes.create-or-edit', $data)->render();
    }

    /**
     * Function (for ajax) to process quiz create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('quiz_id') ? $request->input('quiz_id') : false;

        $this->checkActiveQuizes($edit, $request->input('status'));

        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $rules['allowed_time'] = ['required', new MinString(1), new MaxString(4)];
        $rules['description'] = ['required', new MinString(10), new MaxString(2500)];
        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'allowed_time.required' => __('validation.required'),
            'allowed_time.min' => __('validation.min_string'),
            'allowed_time.max' => __('validation.max_string'),
            'allowed_time.numeric' => __('validation.numeric'),
            'description.required' => __('validation.required'),
            'description.min' => __('validation.min_string'),
            'description.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        if (Quiz::valueExist('title', $request->input('title'), $edit)) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.quiz_already_exist')))
            ));
        } else {
            $result = Quiz::store($request->all(), $edit);
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => __('message.quiz').' ' . ($edit ? __('message.updated') : __('message.created')))),
                'data' => $result
            ));
        }
    }

    /**
     * Function (for ajax) to process quiz delete request
     *
     * @param integer $quiz_id
     * @return void
     */
    public function delete($quiz_id)
    {
        $this->checkIfDemo();
        Quiz::remove($quiz_id);
    }

    /**
     * View Function (for ajax) to return quiz dropdown list
     *
     * @param integer $quiz_category_id
     * @return html/string
     */
    public function dropdown($quiz_category_id = NULL)
    {
        echo json_encode(Quiz::getDropDown($quiz_category_id));
    }

    /**
     * View Function (for ajax) to display clone form page via modal
     *
     * @param integer $quiz_id
     * @return html/string
     */
    public function cloneForm($quiz_id = NULL)
    {
        if ($quiz_id != '0') {
            $data['quiz'] = objToArr(Quiz::get('quiz_id', $quiz_id));
            $data['quiz_categories'] = QuizCategory::getAll();
            echo view('employer.quizes.clone', $data)->render();
        } else {
            echo view('employer.quizes.no-quiz', array())->render();
        }
    }

    /**
     * Function (for ajax) to process quiz clone form request
     *
     * @return redirect
     */
    public function cloneQuiz(Request $request)
    {
        $this->checkIfDemo();
        
        $edit = $request->input('quiz_id') ? $request->input('quiz_id') : false;

        $this->checkActiveQuizes('', $request->input('status'));

        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $rules['allowed_time'] = ['required', new MinString(1), new MaxString(4)];
        $rules['description'] = ['required', new MinString(10), new MaxString(2500)];
        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'allowed_time.required' => __('validation.required'),
            'allowed_time.min' => __('validation.min_string'),
            'allowed_time.max' => __('validation.max_string'),
            'allowed_time.numeric' => __('validation.numeric'),
            'description.required' => __('validation.required'),
            'description.min' => __('validation.min_string'),
            'description.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }        

        if (Quiz::valueExist('title', $request->input('title'), $edit)) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.quiz_already_exist')))
            ));
        } else {
            $result = Quiz::cloneQuiz($request->all(), $edit);
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => __('message.quiz_cloned'))),
                'data' => $result
            ));
        }
    }

    /**
     * Post Function to download quiz
     *
     * @param integer $quiz_id
     * @return void
     */
    public function download($quiz_id = NULL)
    {
        $result = Quiz::getCompleteQuiz($quiz_id);
        $quiz = view('employer.quizes.quiz-pdf', $result)->render();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($quiz);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('quiz.pdf');
        exit;
    }

    /**
     * Function to check active membership content
     *
     * @return html
     */
    private function checkActiveQuizes($id = '', $status = '')
    {
        if ($id != '' && $status == '0') {
            return false;
        }

        //Checking if allowed in membership
        $totalActiveQuizes = Quiz::getTotalQuizes($id);
        $totalAllowedActiveQuizes = empMembership(employerId(), 'active_quizes');
        if ($totalAllowedActiveQuizes == '-1') {
            return false;
        }
        if ($totalActiveQuizes >= $totalAllowedActiveQuizes) {
            $detail = '<br />'.__('message.current_active').' : '.$totalActiveQuizes;
            $detail .= '<br />'.__('message.allowed_in_membership').' : '.$totalAllowedActiveQuizes.'<br />';
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.active_quizes_limit_message').$detail))
            )));
        }        
    }    
}
