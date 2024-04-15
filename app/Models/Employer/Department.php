<?php

namespace App\Models\Employer;

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
        $value = $column == 'department_id' || $column == 'departments.department_id' ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function storeDepartment($data, $edit = null, $image = '')
    {
        unset($data['_token'], $data['department_id']);

        $data['employer_id'] = employerId();

        if ($image) {
            $data['image'] = $image;
        }
        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('department_id', decode($edit))->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            return array('id' => encode($id), 'title' => $data['title']);
        }
    }

    public static function changeStatus($department_id, $status)
    {
        Self::where('department_id', decode($department_id))->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($department_id)
    {
        Self::where(array('department_id' => decode($department_id)))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data['data']));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('department_id', decodeArray($ids))->update(array('status' => 1));
            break;
            case "deactivate":
                Self::whereIn('department_id', decodeArray($ids))->update(array('status' => '0'));
            break;
        }
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'department_id' || $field == 'departments.department_id' ? decode($value) : $value;
        $query = Self::where($field, $value);
        $query->where('employer_id', employerId());
        if ($edit) {
            $query->where('department_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;
    }

    public static function getAll($active = true)
    {
        $query = Self::whereNotNull('departments.department_id');
        if (setting('departments_creation') == 'only_admin') {
            $query->where('employer_id', 0);
        } else {
            $query->where(function($q) {
                if (canHideAdminDepartments('employer_area')) {
                    $q->where('employer_id', employerId());
                } else {
                    $q->where('employer_id', employerId())->orWhere('employer_id', 0);
                }
            });
        }
        if ($active) {
            $query->where('status', 1);
        }
        return $query->get();
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
            'departments.*'
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('departments.title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('departments.status', $request['status']);
        }
        $query->where('employer_id', employerId());
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
        $query->where('employer_id', employerId());
        $query->groupBy('departments.department_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($departments)
    {
        $sorted = array();
        foreach ($departments as $c) {
            $actions = '';
            $id = encode($c['department_id']);
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