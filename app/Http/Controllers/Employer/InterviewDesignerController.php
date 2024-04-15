<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Question;
use App\Models\Employer\QuestionCategory;
use App\Models\Employer\InterviewCategory;

class InterviewDesignerController extends Controller
{
    /**
     * View Function For the overall page of interview designer
     *
     * @return html/string
     */
    public function index(Request $request, $nature = 'interview')
    {
        $data['page'] = __('message.interviews');
        $data['menu'] = 'interviews';

        $questionsResults = Question::getAll($request->all(), 'interview');
        $data['question_categories'] = QuestionCategory::getAll();
        $data['interview_categories'] = InterviewCategory::getAll();
        $questions = $questionsResults['records'];
        $data['total_pages'] = $questionsResults['total_pages'];
        $data['pagination'] = $questionsResults['pagination'];
        $data['questions_page'] = getSessionValues($nature.'_questions_page', 1);
        $data['questions_per_page'] = getSessionValues($nature.'_questions_per_page');
        $data['questions_search'] = getSessionValues($nature.'_questions_search');
        $data['questions_category_id'] = getSessionValues($nature.'_questions_category_id');
        $data['questions_type'] = getSessionValues($nature.'_questions_type');
        $data['questions'] = view('employer.questions.list-items', compact('questions', 'nature'))->render();
        return view('employer.interviews.designer', $data);
    }
}
