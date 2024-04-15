<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InterviewCategory extends Model
{
    protected $table = 'interview_categories';
    protected static $tbl = 'interview_categories';
    protected $primaryKey = 'interview_category_id';

    public static function get($column, $value)
    {
        $value = $column == 'interview_category_id' || $column == 'interview_categories.interview_category_id' ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function store($data, $edit = null)
    {
        unset($data['_token'], $data['interview_category_id']);
        $data['employer_id'] = employerId();

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('interview_category_id', decode($edit))->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            return $id;
        }
    }

    public static function changeStatus($interview_category_id, $status)
    {
        Self::where('interview_category_id', decode($interview_category_id))->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($interview_category_id)
    {
        Self::where(array('interview_category_id' => decode($interview_category_id)))->delete();        
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data['data']));
        $action = $data['action'];
        $ids = decodeArray($data['ids']);
        switch ($action) {
            case "activate":
                Self::whereIn('interview_category_id', $ids)->update(array('status' => 1));
            break;
            case "deactivate":
                Self::whereIn('interview_category_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'interview_category_id' || $field == 'interview_categories.interview_category_id' ? decode($value) : $value;
        $query = Self::where($field, $value);
        $query->where('employer_id', employerId());
        if ($edit) {
            $query->where('interview_category_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;
    }

    public static function getAll($active = true)
    {
        $query = Self::whereNotNull('interview_categories.interview_category_id');
        $query->where('employer_id', employerId());
        if ($active) {
            $query->where('status', 1);
        }
        $query = $query->get();
        return $query ? $query->toArray() : array();
    }

    public static function interviewCategoriesList($request)
    {
        $columns = array(
            "",
            "interview_categories.title",
            "interview_categories.created_at",
            "interview_categories.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('interview_categories.interview_category_id');
        $query->select('interview_categories.*');
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('interview_categories.title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('interview_categories.status', $request['status']);
        }
        $query->where('employer_id', employerId());
        $query->groupBy('interview_categories.interview_category_id');
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
        $query = Self::whereNotNull('interview_categories.interview_category_id');
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('interview_categories.title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('interview_categories.status', $request['status']);
        }
        $query->where('employer_id', employerId());
        $query->groupBy('interview_categories.interview_category_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($interview_categories)
    {
        $sorted = array();
        foreach ($interview_categories as $c) {
            $actions = '';
            $id = encode($c['interview_category_id']);
            if ($c['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (empAllowedTo('edit_interview_categories')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-interview-category" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (empAllowedTo('delete_interview_categories')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-interview-category" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($c['title'], 'html'),
                date('d M, Y', strtotime($c['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-interview-category-status" data-status="'.$c['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }   
}