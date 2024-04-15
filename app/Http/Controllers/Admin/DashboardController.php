<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\Admin\Employer;
use App\Models\Admin\Candidate;
use App\Models\Admin\Membership;
use App\Models\Admin\Job;

class DashboardController extends Controller
{
    /**
     * View function to display admin dashboard
     *
     * @return html/string
     */
    public function dashboard()
    {
        $data['page'] = __('message.dashboard');
        $data['menu'] = 'dashboard';
        $data['employers'] = Employer::where('type', 'main')->count();
        $data['candidates'] = Candidate::count();
        $data['sales'] = Membership::sales();
        $data['jobs'] = Job::count();
        $data['quizes'] = DB::table('quizes')->count();
        $data['interviews'] = DB::table('interviews')->count();
        $data['traites'] = DB::table('traites')->count();
        $data['job_applications'] = DB::table('job_applications')->count();
        return view('admin.dashboard', $data);
    }

    /**
     * Function (via ajax) to get data for sales charts
     *
     * @return json
     */
    public function salesChartData(Request $request)
    {
        $data = json_decode($request->input('data'));
        echo Membership::salesDetail($data->selected);
    }

    /**
     * Function (via ajax) to get data for signups charts
     *
     * @return json
     */
    public function signupsChartData(Request $request)
    {
        $data = json_decode($request->input('data'));
        $employers = Employer::signupsDetail($data->selected);
        $candidates = Employer::signupsDetail($data->selected, 'candidate');
        $return['labels'] = $employers['labels'];
        $return['values']['employers'] = $employers['values'];
        $return['values']['candidates'] = $candidates['values'];
        echo json_encode($return);
    }
}
