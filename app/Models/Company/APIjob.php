<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Company\Job;

class APIjob extends Model
{
    public static function jobsList($request)
    {
        // Get the first day of the last month
        $firstDayLastMonth = Carbon::now()->subMonth()->startOfMonth();
        // Get the last day of the last month
        $lastDayLastMonth = Carbon::now()->subMonth()->endOfMonth();
        // Get the current date
        $currentDate = Carbon::now();
        // Subtract 30 days from the current date to get the start date
        $startDate = $currentDate->subDays(30);

        $columns = array(
            "api_jobs.job_title",
            "api_jobs.category_id",
            "api_jobs.company_name",
            "api_jobs.job_type",
            "api_jobs.updated_at",
            "api_jobs.status",
            ""
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 6 ? 4 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];
        
        $query = DB::connection('apiDatabase')->table('api_jobs');
        $query->select(
           'api_jobs.id',
           'api_jobs.job_title',
           'api_jobs.company_name',
           'api_jobs.job_type',
           'api_jobs.job_pull_date',
           'api_jobs.status',
           'api_jobs.updated_at',
           'categories.id as c_id',
           'categories.name as c_name'
        );
        $query->leftJoin("categories", "api_jobs.category_id", "categories.id");
        $query->where('api_jobs.job_update', '1');
        $query->whereDate('api_jobs.updated_at', '>=', $startDate);
        // $query->whereDate('api_jobs.updated_at', '>=', $firstDayLastMonth)->whereDate('api_jobs.updated_at', '<=', $lastDayLastMonth);

        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('api_jobs.job_title', 'like', '%'.$srh.'%')
                ->orWhere('api_jobs.job_description', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['category']) && $request['category'] != '') {
            $query->where('api_jobs.category_id', decode($request['category']));
        }
        if (isset($request['company']) && $request['company'] != '') {
            $query->where('api_jobs.company_name', decode($request['company']));
        }
        
        $query->orderBy($orderColumn, $orderDirection);
        $query->skip($offset);
        $query->take($limit);
        $result = $query->get();
        $result = $result ? $result->toArray() : array();

        // echo "<pre>";print_r($result);die;

        $result = array(
            'data' => Self::prepareDataForTable($result),
            'recordsTotal' => Self::getTotal(),
            'recordsFiltered' => Self::getTotal($srh, $request),
        );

        return $result;
    }

    public static function getTotal($srh = false, $request = '')
    {
        // Get the first day of the last month
        $firstDayLastMonth = Carbon::now()->subMonth()->startOfMonth();
        // Get the last day of the last month
        $lastDayLastMonth = Carbon::now()->subMonth()->endOfMonth();
        // Get the current date
        $currentDate = Carbon::now();
        // Subtract 30 days from the current date to get the start date
        $startDate = $currentDate->subDays(30);

        $query = DB::connection('apiDatabase')->table('api_jobs');
        $query->select(
           'api_jobs.id',
           'api_jobs.job_title',
           'api_jobs.company_name',
           'api_jobs.job_type',
           'api_jobs.job_pull_date',
           'api_jobs.status',
           'api_jobs.updated_at',
           'categories.id as c_id',
           'categories.name as c_name'
        );
        $query->leftJoin("categories", "api_jobs.category_id", "categories.id");
        $query->where('api_jobs.job_update', '1');
        $query->whereDate('api_jobs.updated_at', '>=', $startDate);
        // $query->whereDate('api_jobs.updated_at', '>=', $firstDayLastMonth)->whereDate('api_jobs.updated_at', '<=', $lastDayLastMonth);

        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('api_jobs.job_title', 'like', '%'.$srh.'%')
                ->orWhere('api_jobs.job_description', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['category']) && $request['category'] != '') {
            $query->where('api_jobs.category_id', decode($request['category']));
        }
        if (isset($request['company']) && $request['company'] != '') {
            $query->where('api_jobs.company_name', decode($request['company']));
        }

        return $query->get()->count();
    }

    private static function prepareDataForTable($jobs)
    {
        $userEmp = session()->all();
        $emp_id = $userEmp['company']['company_id'] ? $userEmp['company']['company_id'] : '';
        $addRec = Job::select("api_job_id")->where("company_id", $emp_id)->get();
        $empAPIjob = [];
        foreach ($addRec as $key => $value) {
            $empAPIjob[] = $value->api_job_id;
        }

        // echo "<pre>";print_r($empAPIjob);die;

        $sorted = array();
        foreach ($jobs as $j) {
            $actions = '';
            $actionsdd = '';
            $j = objToArr($j);
            $id = encode($j['id']);
            $c_id = encode($j['c_id']);

            if (in_array(decode($id), $empAPIjob)) {
                $actions .= '
                    <button type="button" class="btn btn-success btn-xs addedPostJob" data-id="'.$id.'"><b>Added</b></button>
                ';
            } else {
                $actions .= '
                    <button type="button" class="btn btn-primary btn-xs" onclick="added('."['$id']".')" data-id="'.$id.'"><i class="fa fa-plus"></i></button>
                ';
            }

            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($j['job_title']),
                $j['c_name'] ? esc_output($j['c_name']) : '---',
                $j['company_name'] ? esc_output($j['company_name']) : '---',
                $j['job_type'] ? esc_output($j['job_type']) : '---',
                date('d M, Y', strtotime($j['job_pull_date'])),
                $j['status'],
                $actions,
            );
        }
        return $sorted;
    }
}