<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Question;
use App\Models\Employer\QuestionAnswer;
use App\Models\Employer\QuizQuestion;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

class QuizQuestionsController extends Controller
{
    /**
     * Function to get data for questions list
     *
     * @param integer $quiz_id
     * @return json
     */
    public function index($quiz_id = '')
    {
        $questions = QuizQuestion::getAll($quiz_id);
        echo view('employer.quiz-questions.list-items', compact('questions'))->render();
    }

    /**
     * Function to add question to quiz from data bank by drag and drop
     *
     * @param integer $quiz_id
     * @param integer $question_id
     * @return json
     */
    public function add(Request $request, $question_id = '', $quiz_id = '')
    {
        $question = objToArr(Question::getQuestion('question_id', $question_id));
        $answers = objToArr(QuestionAnswer::getQuestionAnswers('question_id', $question_id));
        if (decode($quiz_id) == 0) {
            die();
        }
        echo QuizQuestion::add($quiz_id, $question, $answers);
    }

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $quiz_question_id
     * @return html/string
     */
    public function edit($quiz_question_id = NULL)
    {
        $data['question'] = objToArr(QuizQuestion::get('quiz_question_id', $quiz_question_id));
        $data['answers'] = objToArr(QuizQuestion::getQuizQuestionAnswers('quiz_question_id', $quiz_question_id));
        $data['type'] = $data['question']['type'] ? $data['question']['type'] : 'radio';
        echo view('employer.quiz-questions.edit', $data)->render();
    }

    /**
     * Function (for ajax) to process question create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $answers = $request->input('answers') ? $request->input('answers') : array();
        $edit = $request->input('quiz_question_id') ? $request->input('quiz_question_id') : false;

        $rules['title'] = ['required', new MinString(5), new MaxString(1000)];
        $rules['answer_titles.*'] = ['required', new MinString(1), new MaxString(500)];

        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'answer_titles.*.required' => __('validation.required'),
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages()->toArray();
            $messages = validateArrayMessage($messages, __('message.answer'));
            if (issetVal($messages, 'title')) {
                $messages['title'] = issetVal($messages, 'title');
            }
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $messages))
            )));
        }

        //Uploading new file
        $fileUpload = $this->uploadPublicFile(
            $request, 'image', employerPath().'/'.config('constants.upload_dirs.questions'), 
            array('image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(generalFileUploadLimit())]),
            array('image.image' => __('validation.image'))
        );

        if (!in_array(1, $request->input('answers'))) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.at_least_one_question')))
            ));
        } elseif (count($request->input('answers')) < 2) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.please_add_at_least_2_options')))
            ));
        } else {
            QuizQuestion::updateQuizQuestion($request->all(), issetVal($fileUpload, 'message'));
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => __('message.question_updated')))
            ));
        }
    }    

    /**
     * Post Function (via ajax) to order questions in quiz
     *
     * @return void
     */
    public function order(Request $request)
    {
        QuizQuestion::orderQuestions($request->all());
    }

    /**
     * Post Function (via ajax) to delete question in quiz
     *
     * @return void
     */
    public function delete($quiz_question_id = '')
    {
        $this->checkIfDemo();
        QuizQuestion::remove($quiz_question_id);
    }

    /**
     * Function (for ajax) to process add new answer request to a opened question
     *
     * @param integer $quiz_question_id
     * @param string $type
     * @return html/string
     */
    public function addAnswer($quiz_question_id = '', $type = null)
    {
        $data['quiz_question_id'] = $quiz_question_id;
        $data['type'] = $type;
        echo view('employer.quiz-questions.new-answer-item', $data)->render();
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
        QuizQuestion::removeAnswer($question_answer_id);
    }

    /**
     * Function (for ajax) to process remove image request to a opened question
     *
     * @param integer $quiz_question_id
     * @return void
     */
    public function removeImage($quiz_question_id = '')
    {
        $this->checkIfDemo();
        QuizQuestion::removeImage($quiz_question_id);
    }  
}
