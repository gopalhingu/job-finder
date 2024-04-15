<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Front\Quiz;

class QuizesController extends Controller
{
    /**
     * View Function to display candidate quiz listing page
     *
     * @param integer $page
     * @return html/string
     */
    public function listView()
    {
        $quizes = Quiz::getCandidateQuizes();
        $data['page_title'] = __('message.assigned_quizes');
        $data['menu'] = 'quizes';
        $data['quizes'] = $quizes['results'];
        $data['pagination'] = $quizes['pagination'];
        return view('front'.viewPrfx().'candidates.quiz-listing', $data);
    }

    /**
     * View Function to display quiz detail and attempt page
     *
     * @param  $id string
     * @return html/string
     */
    public function attemptView($id = null)
    {
        $detail = Quiz::getQuiz($id);
        if (!$detail) {
            $data['page'] = '';
            return view('front'.viewPrfx().'candidates.404', $data);
        }

        $quiz = objToArr(json_decode($detail['quiz_data']));
        $data['page_title'] = $detail['quiz_title'].' : '.$detail['job_title'];
        $data['menu'] = 'quizes';
        $data['detail'] = $detail;
        $data['quiz'] = $quiz['quiz'];
        $data['questions'] = $quiz['questions'];
        $data['time'] = quizTime($detail['started_at'], $detail['allowed_time']);
        $data['employer_slug'] = $detail['employer_slug'];

        if ($detail['attempt'] > 0) {
            if ($data['time']['diff'] > 0 && ($detail['attempt'] <= $detail['total_questions'])) {
                $data['question'] = isset($data['questions'][$detail['attempt']-1]) ? $data['questions'][$detail['attempt']-1] : '';
                return view('front'.viewPrfx().'candidates.quiz-attempt', $data);
            } else {
                return view('front'.viewPrfx().'candidates.quiz-done', $data);
            }
        } else {
            return view('front'.viewPrfx().'candidates.quiz-detail', $data);
        }
    }

    /**
     * Function (form post) to record quiz progress
     *
     * @return html/string
     */
    public function attempt(Request $request)
    {
        $this->checkIfDemo('reload');
        Quiz::updateCandidateQuiz($request->all());
        return redirect(url('/').'/account/quiz/'.$request->input('quiz'));
    }
}

