<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionCategory extends Model
{
    protected $table = 'question_categories';
    protected static $tbl = 'question_categories';
    protected $primaryKey = 'question_category_id';

    public static function get($column, $value)
    {
        $value = $column == 'question_category_id' || $column == 'question_categories.question_category_id' ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function store($data, $edit = null)
    {
        unset($data['_token'], $data['question_category_id']);
        $data['employer_id'] = employerId();

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('question_category_id', decode($edit))->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            return encode($id);
        }
    }

    public static function changeStatus($question_category_id, $status)
    {
        Self::where('question_category_id', decode($question_category_id))->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($question_category_id)
    {
        Self::where(array('question_category_id' => decode($question_category_id)))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data['data']));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('question_category_id', decodeArray($ids))->update(array('status' => 1));
            break;
            case "deactivate":
                Self::whereIn('question_category_id', decodeArray($ids))->update(array('status' => '0'));
            break;
        }
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'question_category_id' || $field == 'question_categories.question_category_id' ? decode($value) : $value;        
        $query = Self::whereNotNull('question_categories.question_category_id');
        $query->where($field, $value);
        $query->where('employer_id', employerId());        
        if ($edit) {
            $query->where('question_category_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;
    }

    public static function getAll($active = true)
    {
        $query = Self::whereNotNull('question_categories.question_category_id');
        if ($active) {
            $query->where('status', 1);
        }
        $query->where('employer_id', employerId());        
        $query = $query->get();
        return $query ? $query->toArray() : array();
    }

    public static function questionCategoriesList($request)
    {
        $columns = array(
            "",
            "question_categories.title",
            "question_categories.created_at",
            "question_categories.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('question_categories.question_category_id');
        $query->from('question_categories');
        $query->select(
            'question_categories.*'
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('question_categories.title', 'like', '%'.$srh.'%');
            });            
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('question_categories.status', $request['status']);
        }
        $query->where('question_categories.employer_id', employerId());        
        $query->groupBy('question_categories.question_category_id');
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
        $query = Self::whereNotNull('question_categories.question_category_id');
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('question_categories.title', 'like', '%'.$srh.'%');
            });            
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('question_categories.status', $request['status']);
        }
        $query->where('question_categories.employer_id', employerId());        
        $query->groupBy('question_categories.question_category_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($question_categories)
    {
        $sorted = array();
        foreach ($question_categories as $c) {
            $actions = '';
            $id = encode($c['question_category_id']);
            if ($c['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (empAllowedTo('edit_question_categories')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-question-category" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (empAllowedTo('delete_question_categories')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-question-category" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($c['title']),
                date('d M, Y', strtotime($c['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-question-category-status" data-status="'.$c['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }
}