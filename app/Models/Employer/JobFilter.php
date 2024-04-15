<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JobFilter extends Model
{
    protected $table = 'job_filters';
    protected static $tbl = 'job_filters';
    protected $primaryKey = 'job_filter_id';    

    public static function getJobFilter($column, $value)
    {
        $value = $column == 'job_filter_id' || $column == 'job_filters.job_filter_id' ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function storeJobFilter($data, $edit = null)
    {
        $query = Self::whereNotNull('job_filters.job_filter_id');
        unset($data['_token'], $data['job_filter_id']);
        $data['employer_id'] = employerId();

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            $query->where('job_filter_id', decode($edit))->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $query->insert($data);
            $id = DB::getPdo()->lastInsertId();
            return array('id' => $id, 'title' => $data['title']);
        }
    }

    public static function storeJobFilterValue($data)
    {
        $job_filter_id = decode($data['job_filter_id']);
        $ids = decodeArray($data['ids']);

        $fields = array(
            'id' => $ids, 
            'value' => $data['values'], 
        );
        $values = arrangeSections($fields);

        unset($data['_token'], $data['job_filter_id']);

        //Deleting any if not in input
        DB::table('job_filter_values')
        ->where('job_filter_id', $job_filter_id)
        ->whereNotIn('job_filter_value_id', $ids)
        ->delete();

        //Delete from job filter / field if any
        DB::table('job_filter_value_assignments')
        ->where('job_filter_id', $job_filter_id)
        ->whereNotIn('job_filter_value_id', $ids)
        ->delete();

        foreach ($values as $value) {
            $existing = Self::checkExistingJobFilterValue($value['id']);
            if ($existing) {
                DB::table('job_filter_values')
                ->where('job_filter_value_id', $value['id'])
                ->update(array('title' => $value['value']));
            } else {
                $insert['job_filter_id'] = $job_filter_id;
                $insert['title'] = $value['value'];
                $insert['employer_id'] = employerId();
                DB::table('job_filter_values')->insert($insert);
            }
        }
    }

    private static function checkExistingJobFilterValue($id)
    {
        $query = DB::table('job_filter_values')->where('job_filter_value_id', $id);
        return $query->get()->count() > 0 ? true : false;
    }

    public static function changeStatus($job_filter_id, $status)
    {
        Self::where('job_filter_id', decode($job_filter_id))->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($job_filter_id)
    {
        $condition = array('job_filter_id' => decode($job_filter_id));
        Self::where($condition)->delete();
        DB::table('job_filter_values')->where($condition)->delete();
        DB::table('job_filter_value_assignments')->where($condition)->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data['data']));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('job_filter_id', decodeArray($ids))->update(array('status' => 1));
            break;
            case "deactivate":
                Self::whereIn('job_filter_id', decodeArray($ids))->update(array('status' => '0'));
            break;
        }
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'job_filter_id' || $field == 'job_filters.job_filter_id' ? decode($value) : $value;
        $query = Self::whereNotNull('job_filters.job_filter_id');
        $query->where($field, $value);
        $query->where('employer_id', employerId());        
        if ($edit) {
            $query->where('job_filter_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;

    }

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
                if (canHideAdminJobFilters('employer_area')) {
                    $q->where('job_filters.employer_id', employerId());
                } else {
                    $q->whereIn('job_filters.employer_id', array(employerId(), 0));
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
        return $return;
    }

    public static function getAllValues($id)
    {
        $query = DB::table('job_filter_values')->where('job_filter_id', decode($id))->get();
        return $query ? $query->toArray() : array();
    }

    public static function job_filtersList($request)
    {
        $columns = array(
            "",
            "job_filters.title",
            "",
            "job_filters.order",
            "job_filters.admin_filter",
            "job_filters.front_filter",
            "job_filters.front_value",
            "job_filters.type",
            "job_filters.title",
            "job_filters.created_at",
            "job_filters.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('job_filters.job_filter_id');
        $query->from('job_filters');
        $query->select(
            'job_filters.*',
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_filter_values.title) SEPARATOR ", ") as filter_values')
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('job_filters.title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('job_filters.status', $request['status']);
        }
        $query->where('job_filters.employer_id', employerId());        
        $query->leftJoin('job_filter_values', 'job_filter_values.job_filter_id', '=', 'job_filters.job_filter_id');
        $query->groupBy('job_filters.job_filter_id');
        $query->orderBy($orderColumn, $orderDirection);
        $query->skip($offset);
        $query->take($limit);
        $result = $query->get();
        $result = $result ? $result->toArray() : array();

        $result = array(
            'data' => Self::prepareDataForTable($result),
            'recordsTotal' => Self::getTotal(),
            'recordsFiltered' => Self::getTotal($srh, $request),
        );

        return $result;
    }

    public static function getTotal($srh = false, $request = '')
    {
        $query = Self::whereNotNull('job_filters.job_filter_id');
        $query->from('job_filters');
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('job_filters.title', 'like', '%'.$srh.'%');
            });            
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('job_filters.status', $request['status']);
        }
        $query->where('job_filters.employer_id', employerId());        
        $query->groupBy('job_filters.job_filter_id');
        return $query->get()->count();
    }

    public static function getTotalJobFilters($id = '')
    {
        $query = Self::whereNotNull('job_filters.job_filter_id');
        $query->where('status', 1);
        $query->where('employer_id', employerId());
        if ($id) {
            $query->where('job_filters.job_filter_id', '!=', decode($id));
        }
        return $query->get()->count();
    }    

    private static function prepareDataForTable($job_filters)
    {
        $sorted = array();
        foreach ($job_filters as $c) {
            $actions = '';
            $id = encode($c['job_filter_id']);
            if ($c['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (empAllowedTo('edit_job_filters')) {
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-job-filter" data-id="'.$id.'"><i class="far fa-edit"></i></button>
                <button type="button" class="btn btn-warning btn-xs add-job-filter-values" data-id="'.$id.'" data-title="'.$c['title'].'"><i class="fas fa-list"></i></button>
            ';
            }
            if (empAllowedTo('delete_job_filters')) {
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-job-filter" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($c['title'], 'html'),
                $c['filter_values'] ? esc_output($c['filter_values']) : '---',
                esc_output($c['order'], 'html'),
                ($c['admin_filter'] == 1 ? __('message.yes') : __('message.no')),
                ($c['front_filter'] == 1 ? __('message.yes') : __('message.no')),
                ($c['front_value'] == 1 ? __('message.yes') : __('message.no')),
                ($c['type'] == 'dropdown' ? __('message.dropdown') : __('message.checkbox')),
                date('d M, Y', strtotime($c['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-job-filter-status" data-status="'.$c['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }
}