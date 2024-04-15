<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dashboard  extends Model
{
    public static function getJobs($request)
    {
        //Setting session for every parameter of the request
        setSessionValues($request);

        //First getting total records
        $total = Self::getTotalJobs();
        
        //Setting filters, search and pagination via posted session variables
        $page = getSessionValues('dashboard_jobs_page', 1);
        $per_page = 5;

        $per_page = $per_page < $total ? $per_page : $total;
        $limit = $per_page;
        $offset = ($page == 1 ? 0 : ($page-1)) * $per_page;
        $offset = $offset < 0 ? 0 : $offset;

        $query = DB::table('jobs')->whereNotNull('jobs.job_id');
        $query->select(
            'jobs.*',
            'departments.title as department',
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_applications.job_application_id)) as total_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'jas.job_application_id)) as shortlisted_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'jai.job_application_id)) as interviewed_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'jah.job_application_id)) as hired_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'jar.job_application_id)) as rejected_count')
        );
        $query->where('jobs.status', 1);
        $query->where('jobs.employer_id', employerId());
        $query->leftJoin('departments', 'departments.department_id', '=', 'jobs.department_id');
        $query->leftJoin('job_applications', 'job_applications.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_applications as jas', function($join) {
            $join->on('jas.job_id', '=', 'jobs.job_id')->where('jas.status', '=', 'shortlisted');
        });
        $query->leftJoin('job_applications as jai', function($join) {
            $join->on('jai.job_id', '=', 'jobs.job_id')->where('jai.status', '=', 'interviewed');
        });
        $query->leftJoin('job_applications as jah', function($join) {
            $join->on('jah.job_id', '=', 'jobs.job_id')->where('jah.status', '=', 'hired');
        });
        $query->leftJoin('job_applications as jar', function($join) {
            $join->on('jar.job_id', '=', 'jobs.job_id')->where('jar.status', '=', 'rejected');
        });
        $query->groupBy('jobs.job_id');
        $query->orderBy('jobs.created_at', 'DESC');
        $query->skip($offset);
        $query->take($limit);
        $result = $query->get();
        $records = $result ? $result->toArray() : array();

        //Making pagination for display
        $total_pages = $total != 0 ? ceil($total/$per_page) : 0;
        $pagination = ($offset == 0 ? 1 : ($offset+1));
        $pagination .= ' - ';
        $pagination .= $total_pages == $page ? $total : ($limit*$page);
        $pagination .= ' of ';
        $pagination .= $total;

        //Returning final results
        return array(
            'records' => $records,
            'total' =>  $total,
            'total_pages' => $total_pages,
            'pagination' => $pagination
        );
    }

    public static function getTotalJobs()
    {
        $query = DB::table('jobs')->whereNotNull('jobs.job_id');
        $query->where('jobs.status', 1);
        $query->where('jobs.employer_id', employerId());
        $query->leftJoin('departments', 'departments.department_id', '=', 'jobs.department_id');
        $query->leftJoin('job_applications as jas', function($join) {
            $join->on('jas.job_id', '=', 'jobs.job_id')->where('jas.status', '=', 'hired');
        });
        $query->groupBy('jobs.job_id');
        return $query->get()->count();
    }     
}