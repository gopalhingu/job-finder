<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Question;
use App\Models\Employer\QuestionAnswer;
use App\Models\Employer\QuestionCategory;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

class QuestionsController extends Controller
{
    /**
     * Function to get data for questions list
     *
     * @param  $nature string
     * @return json
     */
    public function index(Request $request, $nature = 'quiz')
    {
        $questionsResults = Question::getAll($request->all(), $nature);
        $questions = $questionsResults['records'];
        echo json_encode(array(
            'pagination' => $questionsResults['pagination'],
            'total_pages' => $questionsResults['total_pages'],
            'list' => view('employer.questions.list-items', compact('questions', 'nature'))->render(),
        ));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param string $nature
     * @param integer $question_id
     * @return html/string
     */
    public function createOrEdit(Request $request, $nature = '', $question_id = NULL)
    {
        $data['question'] = objToArr(Question::getQuestion('question_id', $question_id));
        $data['answers'] = objToArr(QuestionAnswer::getQuestionAnswers('question_id', $question_id));
        $data['question_categories'] = QuestionCategory::getAll($request->all());
        $data['type'] = $data['question']['type'] ? $data['question']['type'] : 'radio';
        $data['nature'] = $nature;
        echo view('employer.questions.create-or-edit', $data)->render();
    }

    /**
     * Function (for ajax) to process question create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        if ($request->input('nature') == 'quiz') {
            $rules['answer_titles.*'] = ['required', new MinString(1), new MaxString(500)];
        }
        $answers = $request->input('answers') ? $request->input('answers') : array();
        $edit = $request->input('question_id') ? $request->input('question_id') : false;
        $rules['title'] = ['required', new MinString(1), new MaxString(1000)];
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

        //Deleting old file if any
        if ($edit && $request->input('image')) {
            $oldFile = Question::getQuestion('questions.question_id', $edit);
            $this->deleteOldFile($oldFile['image']);
        }

        //Uploading new file
        $fileUpload = $this->uploadPublicFile(
            $request, 'image', employerPath().'/'.config('constants.upload_dirs.questions'), 
            array('image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(generalFileUploadLimit())]),
            array('image.image' => __('validation.image'))
        );

        if (!in_array(1, $answers) && $request->input('nature') == 'quiz') {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.at_least_one_question')))
            ));
        } elseif (count($answers) < 2 && $request->input('nature') == 'quiz') {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.please_add_at_least_2_options')))
            ));
        } else {
            Question::storeQuestion($request->all(), issetVal($fileUpload, 'message'));
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => __('message.question') . ($edit ? __('message.updated') : __('message.created'))))
            ));
        }
    }

    /**
     * Function (for ajax) to process question delete request
     *
     * @param integer $question_id
     * @return void
     */
    public function delete($question_id)
    {
        $this->checkIfDemo();
        Question::remove($question_id);
    }

    /**
     * Function (for ajax) to process add new answer request to a opened question
     *
     * @param string $type
     * @param integer $question_id
     * @return html/string
     */
    public function addAnswer($type = '', $question_id = '')
    {
        $data['question_id'] = $question_id;
        $data['type'] = $type;
        echo view('employer.questions.new-answer-item', $data)->render();
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
        Question::removeAnswer($question_answer_id);
    }

    /**
     * Function (for ajax) to process remove image request to a opened question
     *
     * @param integer $question_id
     * @return void
     */
    public function removeImage($question_id = '')
    {
        $this->checkIfDemo();
        Question::removeImage($question_id);
    }
}
