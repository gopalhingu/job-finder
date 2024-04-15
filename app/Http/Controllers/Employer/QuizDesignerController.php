<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Question;
use App\Models\Employer\QuestionCategory;
use App\Models\Employer\QuizCategory;

class QuizDesignerController extends Controller
{
    /**
     * View Function For the overall page of quiz designer
     *
     * @return html/string
     */
    public function index(Request $request, $nature = 'quiz')
    {
        $data['page'] = __('message.quizes');
        $data['menu'] = 'quizes';

        $questionsResults = Question::getAll($request->all());
        $data['question_categories'] = QuestionCategory::getAll();
        $data['quiz_categories'] = QuizCategory::getAll();
        $questions = $questionsResults['records'];
        $data['questions_page'] = getSessionValues($nature.'_questions_page', 1);
        $data['questions_per_page'] = getSessionValues($nature.'_questions_per_page');
        $data['questions_search'] = getSessionValues($nature.'_questions_search');
        $data['questions_category_id'] = getSessionValues($nature.'_questions_category_id');
        $data['questions_type'] = getSessionValues($nature.'_questions_type');
        $data['total_pages'] = $questionsResults['total_pages'];
        $data['pagination'] = $questionsResults['pagination'];
        $data['questions'] = view('employer.questions.list-items', compact('questions', 'nature'))->render();
        return view('employer.quizes.designer', $data);
    }
}
