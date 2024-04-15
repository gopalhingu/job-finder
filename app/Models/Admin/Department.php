<?php

namespace App\Models\Admin;

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
        $value = $column == 'department_id' || $column == 'departments.department_id' ? $value : $value;
        $query = Self::where($column, $value);
        $query->select('departments.*', 'employers.slug');
        $query->leftJoin('employers', function($join) {
            $join->on('employers.employer_id', '=', 'departments.employer_id');
        });
        $result = $query->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl, array('slug'));
    }

    public static function storeDepartment($data, $edit = null, $image = '')
    {
        unset($data['_token'], $data['department_id']);

        if ($image) {
            $data['image'] = $image;
        }
        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('department_id', $edit)->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['employer_id'] = 0;
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            return array('id' => $id, 'title' => $data['title']);
        }
    }

    public static function changeStatus($department_id, $status)
    {
        Self::where('department_id', $department_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($department_id)
    {
        Self::where(array('department_id' => $department_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data['data']));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('department_id', $ids)->update(array('status' => 1));
            break;
            case "deactivate":
                Self::whereIn('department_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'department_id' || $field == 'departments.department_id' ? $value : $value;
        $query = Self::where($field, $value);
        if ($edit) {
            $query->where('department_id', '!=', $edit);
        }
        return $query->get()->count() > 0 ? true : false;
    }

    public static function getAll($active = true, $limit = '', $orderByCol = '', $orderByDir = '')
    {
        $query = Self::whereNotNull('departments.department_id');
        $query->select(
            'departments.*', 
            DB::Raw('COUNT('.dbprfx().'jobs.job_id) AS count'),
            'employers.company'
        );
        $query->where('departments.employer_id', 0);
        if ($active) {
            $query->where('departments.status', 1);
        }
        if ($orderByCol) {
            $query->orderBy($orderByCol, $orderByDir);
        }
        if ($limit) {
            $query->skip(0);
            $query->take($limit);            
        }
        $query->leftJoin('jobs', function($join) {
            $join->on('jobs.department_id', '=', 'departments.department_id');
        });                
        $query->leftJoin('employers', 'employers.employer_id', '=', 'departments.employer_id');
        $query->groupBy('departments.department_id');
        $result = $query->get();
        $result = $result ? $result->toArray() : array();
        return $result;
    }

    public static function departmentsList($request)
    {
        $columns = array(
            "",
            "",
            "departments.title",
            "departments.created_at",
            "departments.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('departments.department_id');
        $query->select(
            'departments.*',
            'employers.slug'
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('departments.title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('departments.status', $request['status']);
        }
        if (isset($request['employer_id']) && $request['employer_id'] != '') {
            $query->where('departments.employer_id', $request['employer_id']);
        }
        $query->leftJoin('employers', function($join) {
            $join->on('employers.employer_id', '=', 'departments.employer_id');
        });        
        $query->groupBy('departments.department_id');
        $query->orderBy($orderColumn, $orderDirection);
        $query->skip($offset);
        $query->take($limit);
        $query = $query->get();
        $result = $query ? $query->toArray() : array();

        $result = array(
            'data' => Self::prepareDataForTable($result),
            'recordsTotal' => Self::getTotal(),
            'recordsFiltered' => Self::getTotal($srh, $request),
        );

        return $result;
    }

    public static function getTotal($srh = false, $request = '')
    {
        $query = Self::whereNotNull('departments.department_id');
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('departments.title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('departments.status', $request['status']);
        }
        if (isset($request['employer_id']) && $request['employer_id'] != '') {
            $query->where('departments.employer_id', $request['employer_id']);
        }        
        $query->groupBy('departments.department_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($departments)
    {
        $sorted = array();
        foreach ($departments as $c) {
            $actions = '';
            $id = $c['department_id'];
            if ($c['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (empAllowedTo('edit_departments')) {
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-department" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (empAllowedTo('delete_departments')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-department" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $thumb = departmentThumb($c['image']);
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                "<img class='user-thumb-table' src='".$thumb['image']."' onerror='this.src=\"".$thumb['error']."\"'/>",
                esc_output($c['title'], 'html'),
                date('d M, Y', strtotime($c['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-department-status" data-status="'.$c['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }   
}