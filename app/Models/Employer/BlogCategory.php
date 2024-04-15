<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BlogCategory  extends Model
{
    protected $table = 'blog_categories';
    protected static $tbl = 'blog_categories';
    protected $primaryKey = 'blog_category_id';

    public static function get($column, $value)
    {
        $value = ($column == 'blog_categories.blog_category_id' || $column == 'blog_category_id') ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function store($data, $edit = null)
    {
        unset($data['_token'], $data['blog_category_id']);
        $data['employer_id'] = employerId();

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('blog_category_id', decode($edit))->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            return encode($id);
        }
    }

    public static function changeStatus($blog_category_id, $status)
    {
        Self::where('blog_category_id', decode($blog_category_id))->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($blog_category_id)
    {
        Self::where(array('blog_category_id' => decode($blog_category_id)))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data['data']));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('blog_category_id', decodeArray($ids))->update(array('status' => '1'));
            break;
            case "deactivate":
                Self::whereIn('blog_category_id', decodeArray($ids))->update(array('status' => '0'));
            break;
        }
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $query = Self::whereNotNull('blog_categories.blog_category_id');
        $query->where($field, $value);
        $query->where('employer_id', employerId());        
        if ($edit) {
            $query->where('blog_category_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;
    }

    public static function getAll($active = true)
    {
        $query = Self::whereNotNull('blog_categories.blog_category_id');
        if ($active) {
            $query->where('status', 1);
        }
        $query->where('employer_id', employerId());
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function blogCategoriesList($request)
    {
        $columns = array(
            "",
            "",
            "blog_categories.title",
            "blog_categories.created_at",
            "blog_categories.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('blog_categories.blog_category_id');
        $query->select(
            'blog_categories.*'
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('blog_categories.title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('blog_categories.status', $request['status']);
        }
        $query->where('employer_id', employerId());        
        $query->groupBy('blog_categories.blog_category_id');
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
        $query = Self::whereNotNull('blog_categories.blog_category_id');
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('blog_categories.title', 'like', '%'.$srh.'%');
            });
       }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('blog_categories.status', $request['status']);
        }
        $query->where('employer_id', employerId());        
        $query->groupBy('blog_categories.blog_category_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($blog_categories)
    {
        $sorted = array();
        foreach ($blog_categories as $c) {
            $actions = '';
            $c = objToArr($c);
            $id = encode($c['blog_category_id']);
            if ($c['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (empAllowedTo('edit_blog_categories')) {
            $actions = '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-blog-category" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (empAllowedTo('delete_blog_categories')) {
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-blog-category" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($c['title'], 'html'),
                date('d M, Y', strtotime($c['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-blog-category-status" data-status="'.$c['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }
}