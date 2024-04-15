<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use SimpleExcel\SimpleExcel;
use Dompdf\Dompdf;

use App\Models\Employer\JobBoard;
use App\Models\Employer\Department;
use App\Models\Employer\Quiz;
use App\Models\Employer\Interview;
use App\Models\Employer\Employer;
use App\Models\Employer\Candidate;
use App\Models\Employer\CandidateInterview;
use App\Models\Employer\Job;

class JobBoardController extends Controller
{
    /**
     * View Function For the overall page of job board
     *
     * @return html/string
     */
    public function index(Request $request, $job_id = '')
    {
        $data['page'] = __('message.job_board');
        $data['menu'] = 'job_board';

        $jobsResults = JobBoard::getJobs($request->all());
        $jobs = $jobsResults['records'];
        $data['jobs_total_pages'] = $jobsResults['total_pages'];
        $data['jobs_pagination'] = $jobsResults['pagination'];
        $data['jobs'] = view('employer.job-board.job-list-items', compact('jobs'))->render();

        //Getting session values for search, filters and pagination for jobs and candidates
        $session_data = array('jobs_per_page','jobs_search','jobs_company_id','jobs_department_id',
                            'jobs_status','candidates_per_page','candidates_search','candidates_sort',
                            'candidates_min_experience','candidates_max_experience','candidates_min_overall',
                            'candidates_max_overall','candidates_min_interview','candidates_max_interview',
                            'candidates_min_quiz','candidates_max_quiz','candidates_min_self','candidates_max_self',);
        foreach ($session_data as $value) {
            $data[$value] = getSessionValues($value);
        }
        $data['jobs_page'] = getSessionValues('jobs_page', 1);
        $data['candidates_page'] = getSessionValues('candidates_page', 1);

        $data['departments'] = objToArr(Department::getAll());
        $data['first_job_id'] = $job_id ? $job_id : (isset($jobs[0]['job_id']) ? $jobs[0]['job_id'] : '');

        return view('employer.job-board.index', $data);
    }

    /**
     * Function (via ajax) to get data for jobs list
     *
     * @return json
     */
    public function jobsList(Request $request)
    {
        $jobsResults = JobBoard::getJobs($request->all());
        $jobs = $jobsResults['records'];
        echo json_encode(array(
            'pagination' => $jobsResults['pagination'],
            'total_pages' => $jobsResults['total_pages'],
            'list' => view('employer.job-board.job-list-items', compact('jobs'))->render(),
        ));
    }

    /**
     * Function (via ajax) to get data for candidates list
     *
     * @param $job_id integer
     * @return json
     */
    public function candidatesList(Request $request, $job_id = '')
    {
        $candidatesResults = JobBoard::getCandidates($request->all(), $job_id);
        $candidates = $candidatesResults['records'];
        echo json_encode(array(
            'pagination' => $candidatesResults['pagination'],
            'total_pages' => $candidatesResults['total_pages'],
            'candidates_all' => $candidatesResults['candidates_all'],
            'list' => view('employer.job-board.candidate-list-items', compact('candidates'))->render(),
        ));
    }

    /**
     * Function (via ajax) to view assign quiz or interview to candidate(s)
     *
     * @param $type string
     * @param $job_id integer
     * @return json
     */
    public function assignView($type = '', $job_id = '')
    {
        if ($type == 'quiz') {
            $data['quizes'] = Quiz::getAll(true);
        } else {
            $data['interviews'] = Interview::getAll(true);
            $data['employers'] = objToArr(Employer::getAll());
        }
        $data['type'] = $type;
        $data['job_id'] = $job_id;
        echo view('employer.job-board.assign', $data)->render();
        exit;
    }

    /**
     * Function (via ajax) to assign quiz or interview to candidate(s)
     *
     * @return json
     */
    public function assign(Request $request)
    {
        ini_set('max_execution_time', 5000); //For three or more emails in this function
        $this->checkIfDemo();
        $data = $request->all();

        //Checking if quiz is selected
        if ($data['type'] == 'quiz' && issetVal($data, 'quiz_id') == '') {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.quiz_can_not_be_empty')))
            )));            
        }

        //Checking if interview is selected
        if ($data['type'] == 'interview' && (issetVal($data, 'interview_id') == '' || issetVal($data, 'interviewer_id') == '')) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.interview_and_interviewer_can_not_be_empty')))
            )));            
        }


        $assigned = JobBoard::assignToCandidates($request->all());
        $candidates = json_decode($data['candidates']);

        if (isset($data['notify_candidate'])) {
            foreach ($candidates as $candidate_id) {
                $candidate = objToArr(Candidate::getCandidate('candidate_id', decode($candidate_id)));
                if ($data['type'] == 'interview') {
                    $tagsWithValues = array(
                        '((site_link))' => url('/'.employerId('slug')),
                        '((site_logo))' => settingEmp('site_logo'),
                        '((site_name))' => settingEmp('site_name'),
                        '((job_title))' => $assigned['job'],
                        '((first_name))' => $candidate['first_name'],
                        '((last_name))' => $candidate['last_name'],
                        '((date_time))' => $data['interview_time'],
                        '((description))' => $data['description'],
                    );
                    $messageEmail = replaceTagsInTemplate2(settingEmp('candidate_interview_assign'), $tagsWithValues);
                    $subject = settingEmp('site_name').' : '.__('message.interview_schedule');
                    $this->sendEmail($messageEmail, $candidate['email'], $subject);
                } else {
                    $tagsWithValues = array(
                        '((site_link))' => url('/'.employerId('slug')),
                        '((site_logo))' => settingEmp('site_logo'),
                        '((first_name))' => $candidate['first_name'],
                        '((last_name))' => $candidate['last_name'],
                        '((job_title))' => $assigned['job'],
                        '((quiz))' => $assigned['title'],
                    );
                    $messageEmail = replaceTagsInTemplate2(settingEmp('candidate_quiz_assign'), $tagsWithValues);
                    $subject = settingEmpSlug('site_name').' : '.__('message.quiz_assigned');
                    $this->sendEmail($messageEmail, $candidate['email'], $subject);
                }                
            }
        }

        if (isset($data['notify_team_member']) && $data['type'] == 'interview') {
            $team = objToArr(Employer::getEmployer('employer_id', $data['interviewer_id']));
            $tagsWithValues = array(
                '((site_link))' => url('/employer'),
                '((site_logo))' => settingEmp('site_logo'),
                '((first_name))' => $team['first_name'],
                '((last_name))' => $team['last_name'],
                '((job_title))' => $assigned['job'],
                '((date_time))' => $data['interview_time'],
                '((description))' => $data['description'],
            );
            $messageEmail = replaceTagsInTemplate2(settingEmp('employer_interview_assign'), $tagsWithValues);
            $subject = settingEmp('site_name').' : '.__('message.interview_assigned');
            $this->sendEmail($messageEmail, $team['email'], $subject);
        }

        die(json_encode(array('success' => 'true', 'messages' => __('message.assigned'))));
    }

    /**
     * Function (via ajax) to update candidate job application status
     *
     * @return json
     */
    public function candidateStatus(Request $request)
    {
        JobBoard::updateCandidateStatus($request->all());
        echo json_encode(array(
            'success' => 'true',
            'messages' => __('message.assigned')
        ));
    }

    /**
     * Function (via ajax) to delete candidate job application
     *
     * @return json
     */
    public function deleteApplication(Request $request)
    {
        JobBoard::deleteCandidateApplication($request->all());
        echo json_encode(array(
            'success' => 'true',
            'messages' => __('message.delete')
        ));
    }

    /**
     * Function (via ajax) to view job detail
     *
     * @param  $job_id integer
     * @return json
     */
    public function viewJob($job_id = '')
    {
        $job = objToArr(Job::getJob('jobs.job_id', $job_id));
        echo view('employer.job-board.job-detail', compact('job'))->render();
    }

    /**
     * Function (via ajax) to view resume
     *
     * @param  $resume_id integer
     * @return json
     */
    public function viewResume($resume_id = '')
    {
        $resume = objToArr(Candidate::getCompleteResume($resume_id, true));
        $resume_file = $resume['file'];
        $resume_id = $resume['resume_id'];
        $data['resume_id'] = $resume_id;
        $data['type'] = $resume['type'];
        $data['file'] = $resume['file'];
        $data['resume'] = view('employer.candidates.resume', compact('resume', 'resume_file', 'resume_id'))->render();
        echo view('employer.job-board.resume', $data)->render();
    }

    /**
     * Function do export overall result in excel
     *
     * @return json
     */
    public function overallResult(Request $request)
    {
        ini_set('max_execution_time', '0');
        $result = JobBoard::overallResult($request->all());
        $result = objToArr($result);
        $data = sortForCSV($result);
        $excel = new SimpleExcel('csv');
        $excel->writer->setData($data);
        $excel->writer->saveFile('overallResult');
        exit;
    }

    /**
     * Function do export pdf result for traits, quizes and interviews
     *
     * @return json
     */
    public function pdfResult(Request $request)
    {
        $this->checkIfDemo('reload');
        ini_set('max_execution_time', '0');
        $results = '';
        $filename = '';

        if ($request->input('type') == 'e-self') {
            $result = JobBoard::traitesResult($request->all());
            //dd($result);
            foreach ($result as $r) {
                $data['traite'] = $r;
                $results .= view('employer.job-board.pdf-traits', $data)->render();
            }
            $filename = $request->input('job').'-SelfAssementResults.pdf';
        } else if ($request->input('type') == 'e-quiz') {
            $result = JobBoard::quizesResult($request->all());
            foreach ($result as $r) {
                $data['quizes'] = $r;
                $results .= view('employer.job-board.pdf-quizes', $data)->render();
            }
            $filename = $request->input('job').'-QuizResults.pdf';
        } else if ($request->input('type') == 'e-interview') {
            $result = JobBoard::interviewsResult($request->all());
            foreach ($result as $r) {
                $data['interviews'] = $r;
                $results .= view('employer.job-board.pdf-interviews', $data)->render();
            }
            $filename = $request->input('job').'-interviewsResults.pdf';
        }

        $dompdf = new Dompdf();
        $dompdf->loadHtml($results);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename);
        exit;
    }

    /**
     * Function (for ajax) to delete candidate interview
     *
     * @param  $candidate_interview_id integer
     * @return redirect
     */
    public function deleteInterview($candidate_interview_id = '')
    {
        $this->checkIfDemo();
        $data = CandidateInterview::deleteCandidateInterview($candidate_interview_id);
        JobBoard::updateInterviewResultInJobApplication($data);
        JobBoard::updateOverallResultInJobApplication($data);
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.candidate_interview_deleted')))
        ));
    }

    /**
     * Function (for ajax) to delete candidate quiz
     *
     * @param  $candidate_quiz_id integer
     * @return redirect
     */
    public function deleteQuiz($candidate_quiz_id = '')
    {
        $this->checkIfDemo();
        $data = Quiz::deleteCandidateQuiz($candidate_quiz_id);
        JobBoard::updateQuizResultInJobApplication($data);
        JobBoard::updateOverallResultInJobApplication($data);
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.candidate_quiz_deleted')))
        ));
    }

}
