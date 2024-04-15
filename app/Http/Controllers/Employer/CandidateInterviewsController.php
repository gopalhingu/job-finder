<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Employer;
use App\Models\Employer\Job;
use App\Models\Employer\CandidateInterview;
use App\Models\Employer\JobBoard;
use App\Models\Employer\Candidate;

class CandidateInterviewsController extends Controller
{
    /**
     * View Function to display candidateInterviews list view page
     *
     * @return html/string
     */
    public function listView()
    {
        $data['page'] = __('message.candidate_interviews');
        $data['menu'] = 'candidate_interviews';
        $data['employers'] = objToArr(Employer::getAll());
        $data['jobs'] = objToArr(Job::getAll());
        return view('employer.candidate-interviews.list', $data);
    }

    /**
     * Function to get data for candidateInterviews jquery datatable
     *
     * @return json
     */
    public function data(Request $request)
    {
        echo json_encode(CandidateInterview::candidateInterviewsList($request->all()));
    }    

    /**
     * View Function (for ajax) to display view or conduct view page via modal
     *
     * @param integer $candidate_interview_id
     * @return html/string
     */
    public function viewOrConduct($candidate_interview_id = NULL)
    {
        $candidate_interview = CandidateInterview::getCandidateInterview(
            'candidate_interview_id', $candidate_interview_id
        );
        echo view('employer.candidate-interviews.edit', compact('candidate_interview'))->render();
    }

    /**
     * Function (for ajax) to process candidate_interview create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();
        $data = CandidateInterview::storeCandidateInterview($request->all());
        JobBoard::updateInterviewResultInJobApplication($data);
        JobBoard::updateOverallResultInJobApplication($data);
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.candidate_interview_recorded')))
        ));
    }   
}
