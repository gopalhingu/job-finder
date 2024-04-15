<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Candidate\Quiz;

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
        $data['page'] = __('message.assigned_quizes').' | ' . settingEmpSlug('site_name');
        $data['breadcrumb_title'] = __('message.assigned_quizes');
        $data['menu'] = 'quizes';
        $data['quizes'] = $quizes['results'];
        $data['pagination'] = $quizes['pagination'];
        return view('candidate'.viewPrfx().'account-quiz-listing', $data);
    }

    /**
     * View Function to display quiz detail and attempt page
     *
     * @param  $id string
     * @return html/string
     */
    public function attemptView($slug = null, $id = null)
    {        
        $pageData['page'] = __('message.attempt_quiz').' | ' . settingEmpSlug('site_name');

        $detail = Quiz::getQuiz($id);
        if (!$detail) {
            $data['page'] = '';
            return view('candidate'.viewPrfx().'404', $data);
        }

        $quiz = objToArr(json_decode($detail['quiz_data']));
        $data['page'] = __('message.assigned_quizes').' | ' . settingEmpSlug('site_name');
        $data['breadcrumb_title'] = $detail['quiz_title'].' : '.$detail['job_title'];
        $data['menu'] = 'quizes';
        $data['detail'] = $detail;
        $data['quiz'] = $quiz['quiz'];
        $data['questions'] = $quiz['questions'];
        $data['time'] = quizTime($detail['started_at'], $detail['allowed_time']);

        if ($detail['attempt'] > 0) {
            if ($data['time']['diff'] > 0 && ($detail['attempt'] <= $detail['total_questions'])) {
                $data['question'] = isset($data['questions'][$detail['attempt']-1]) ? $data['questions'][$detail['attempt']-1] : '';
                return view('candidate'.viewPrfx().'account-quiz-attempt', $data);
            } else {
                return view('candidate'.viewPrfx().'account-quiz-done', $data);
            }
        } else {
            return view('candidate'.viewPrfx().'account-quiz-detail', $data);
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
        return redirect(empUrl().'account/quiz/'.$request->input('quiz'));
    }
}

