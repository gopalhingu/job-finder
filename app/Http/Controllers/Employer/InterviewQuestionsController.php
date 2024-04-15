<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Question;
use App\Models\Employer\InterviewQuestion;
use App\Models\Employer\QuestionAnswer;
use App\Rules\MinString;
use App\Rules\MaxString;

class InterviewQuestionsController extends Controller
{
    /**
     * Function to get data for questions list
     *
     * @param integer $interview_id
     * @return json
     */
    public function index($interview_id = '')
    {
        $questions = InterviewQuestion::getAll($interview_id);
        echo view('employer.interview-questions.list-items', compact('questions'))->render();
    }

    /**
     * Function to add question to interview from data bank by drag and drop
     *
     * @param integer $interview_id
     * @param integer $question_id
     * @return json
     */
    public function add($interview_id = '', $question_id = '')
    {
        $question = objToArr(Question::getQuestion('question_id', $question_id));
        $answers = objToArr(QuestionAnswer::getQuestionAnswers('question_id', $question_id));
        if (decode($interview_id) == 0) {
            die();
        }
        echo InterviewQuestion::add($interview_id, $question, $answers);
    }

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $interview_question_id
     * @return html/string
     */
    public function edit($interview_question_id = NULL)
    {
        $data['question'] = objToArr(InterviewQuestion::get('interview_question_id', $interview_question_id));
        echo view('employer.interview-questions.edit', $data)->render();
    }

    /**
     * Function (for ajax) to process question create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

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

        InterviewQuestion::updateInterviewQuestion($request->all());
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.question_updated')))
        ));
    }    

    /**
     * Post Function (via ajax) to order questions in interview
     *
     * @return void
     */
    public function order(Request $request)
    {
        $this->checkIfDemo();
        InterviewQuestion::orderQuestions($request->all());
    }

    /**
     * Post Function (via ajax) to delete question in interview
     *
     * @return void
     */
    public function delete($interview_question_id = '')
    {
        $this->checkIfDemo();
        InterviewQuestion::remove($interview_question_id);
    }

    /**
     * Function (for ajax) to process add new answer request to a opened question
     *
     * @param integer $interview_question_id
     * @param string $type
     * @return html/string
     */
    public function addAnswer($interview_question_id = '', $type = null)
    {
        $data['interview_question_id'] = $interview_question_id;
        $data['type'] = $type;
        echo view('employer.interview-questions.new-answer-item', $data)->render();
    }

    /**
     * Function (for ajax) to process remove answer request to a opened question
     *
     * @param integer $question_answer_id
     * @return void
     */
    public function removeAnswer($question_answer_id = '', $type = null)
    {
        $this->checkIfDemo();
        InterviewQuestion::removeAnswer($question_answer_id);
    }
}