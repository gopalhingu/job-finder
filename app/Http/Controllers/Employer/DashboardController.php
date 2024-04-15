<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use SimpleExcel\SimpleExcel;
use Dompdf\Dompdf;

use App\Models\Employer\Job;
use App\Models\Employer\Candidate;
use App\Models\Employer\JobBoard;
use App\Models\Employer\Interview;
use App\Models\Employer\Dashboard;

class DashboardController extends Controller
{
    /**
     * Function to load dashboard main page
     *
     * @return json
     */
    public function index()
    {
        $data['page'] = __('message.dashboard');
        $data['menu'] = 'dashboard';
        $data['jobs'] = Job::getAll();
        $data['jobsCount'] = Job::getTotalJobs();
        $data['candidates'] = Candidate::getTotalCandidates();
        $data['applications'] = JobBoard::getJobApplicationsCount();
        $data['hired'] = JobBoard::getJobApplicationsCount('hired');
        $data['rejected'] = JobBoard::getJobApplicationsCount('rejected');
        $data['interviews'] = Interview::getInterviewsCount();
        $data['dashboard_jobs_page'] = getSessionValues('dashboard_jobs_page', 1);
        $data['dashboard_jobs_total_pages'] = getSessionValues('dashboard_jobs_total_pages');
        $data['dashboard_todos_page'] = getSessionValues('dashboard_todos_page', 1);
        $data['dashboard_todos_total_pages'] = getSessionValues('dashboard_todos_total_pages');
        return view('employer.dashboard.index', $data);
    }

    /**
     * Function (via ajax) to get data for popular job charts
     *
     * @return json
     */
    public function popularJobsChartData(Request $request)
    {
        echo Job::getPopularJobs($request->all());
    }

    /**
     * Function (via ajax) to get data for candidates cahrts
     *
     * @return json
     */
    public function topCandidatesChartData(Request $request)
    {
        echo Candidate::getTopCandidates($request->all());
    }

    /**
     * Function (via ajax) to get data for job statuses
     *
     * @return json
     */
    public function jobsList(Request $request)
    {
        $jobsResults = Dashboard::getJobs($request->all());
        $jobs = objToArr($jobsResults['records']);
        echo json_encode(array(
            'pagination' => $jobsResults['pagination'],
            'total_pages' => $jobsResults['total_pages'],
            'list' => view('employer.dashboard.job-item', compact('jobs'))->render(),
        ));
    }

    /**
     * Function (via ajax) to get data for candidates cahrts
     *
     * @return json
     */
    public function combined(Request $request)
    {
        $data = $request->all();
        $data = objToArr(json_decode($data['data']));
        $popular = Job::getPopularJobs($data['popular']);
        $top = Candidate::getTopCandidates($data['top']);
        $jobsResults = Dashboard::getJobs($data['jobs']);
        $jobs = objToArr($jobsResults['records']);
        $jobsData = json_encode(array(
            'pagination' => $jobsResults['pagination'],
            'total_pages' => $jobsResults['total_pages'],
            'list' => view('employer.dashboard.job-item', compact('jobs'))->render(),
        ));
        $array = array(
            'popular' => $popular,
            'top' => $top,
            'jobs' => $jobsData
        );
        echo json_encode($array);
    }    

}
