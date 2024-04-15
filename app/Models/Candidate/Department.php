<?php

namespace App\Models\Candidate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Department extends Model
{
    protected $table = 'departments';
    protected static $tbl = 'departments';
    protected $primaryKey = 'department_id';

    public static function getDepartment($column, $value)
    {
        $result = Self::where($column, $value)->first();
        return $result ? objToArr($result->toArray()) : emptyTableColumns(Self::$tbl);
    }

    public static function getAll($active = true, $limit = 100)
    {
        $query = Self::whereNotNull('departments.department_id');
        $query->select(
            'departments.*', 
            DB::Raw('COUNT('.dbprfx().'jobs.job_id) AS count'),
        );
        if (setting('departments_creation') == 'only_admin') {
            $query->where('departments.employer_id', 0);
        } else {
            $query->where(function($q) {
                if (canHideAdminDepartments('candidate_area')) {
                    $q->where('departments.employer_id', employerIdBySlug());
                } else {
                    $q->where('departments.employer_id', employerIdBySlug())->orWhere('departments.employer_id', 0);
                }
            });
        }
        $query->leftJoin('jobs', function($join) {
            $join->on('jobs.department_id', '=', 'departments.department_id')->where('jobs.employer_id', employerIdBySlug());
        });
        if ($active) {
            $query->where('jobs.status', 1);
        }
        $query->groupBy('departments.department_id');        
        $query->skip(0);
        $query->take($limit);
        $query = $query->get();
        return objToArr($query->toArray());
    }
}