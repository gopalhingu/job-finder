<?php

namespace App\Models\Candidate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JobFilter extends Model
{
    protected $table = 'job_filters';
    protected static $tbl = 'job_filters';
    protected $primaryKey = 'job_filter_id';    

    public static function getAll($active = true)
    {
        $query = Self::whereNotNull('job_filters.job_filter_id');
        $query->select(
            'job_filters.*',
            DB::Raw('GROUP_CONCAT(
                DISTINCT(CONCAT('.dbprfx().'job_filter_values.title, ")=-=(", '.dbprfx().'job_filter_values.job_filter_value_id))
            SEPARATOR "(=-=)") as filters')
        );
        if ($active) {
            $query->where('status', 1);
        }
        if (setting('job_filters_creation') == 'only_admin') {
            $query->where('job_filters.employer_id', 0);
        } else {
            $query->where(function($q) {
                if (canHideAdminJobFilters('candidate_area')) {
                    $q->where('job_filters.employer_id', employerIdBySlug());
                } else {
                    $q->whereIn('job_filters.employer_id', array(employerIdBySlug(), 0));
                }
            });
        }                
        $query->leftJoin('job_filter_values', 'job_filter_values.job_filter_id', '=', 'job_filters.job_filter_id');
        $query->groupBy('job_filters.job_filter_id');
        $query->orderBy('job_filter_values.job_filter_value_id', 'ASC');
        $query = $query->get();
        $result = $query ? $query->toArray() : array();
        $return = array();
        foreach ($result as $r) {
            if ($r['filters']) {
                $exploded1 = explode('(=-=)', $r['filters']);
                foreach ($exploded1 as $e) {
                    $exploded2 = explode(')=-=(', $e);
                    $r['values'][] = array('id' => $exploded2[1], 'title' => $exploded2[0]);
                }
            } else {
                $r['values'] = array();
            }
            unset($r['filters']);
            $return[] = $r;
        }
        return objToArr($return);
    }

    public static function getAllSimple($active = true, $front_filter = true)
    {
        $query = Self::whereNotNull('job_filters.job_filter_id');
        $query->whereIn('job_filters.employer_id', array(employerIdBySlug(), 0));
        $query->select('job_filters.*');
        $query = $query->get();
        $result = $query ? $query->toArray() : array();
        $return = array();
        foreach ($result as $r) {
            $return[$r['job_filter_id']] = array(
                'admin_filter' => $r['admin_filter'],
                'front_filter' => $r['front_filter'],
                'front_value' => $r['front_value'],
                'status' => $r['status'],
            );
        }
        return $return;
    }
}