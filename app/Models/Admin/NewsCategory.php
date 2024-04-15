<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewsCategory extends Model
{
    protected $table = 'news_categories';
    protected static $tbl = 'news_categories';
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_id',
        'title',
        'status',
        'created_at',
        'updated_at',
    ];

    public static function getCategory($column, $value)
    {
    	$categories = Self::where($column, $value)->first();
    	return $categories ? $categories : emptyTableColumns(Self::$tbl);
    }

    public static function store($data, $edit = null)
    {
        unset($data['_token']);
        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('category_id', $edit)->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['status'] = 1;
            Self::insert($data);
        }
    }

    public static function changeStatus($category_id, $status)
    {
        Self::where('category_id', $category_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($category_id)
    {
        Self::where(array('category_id' => $category_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('category_id', $ids)->update(array('status' => '1'));
            break;
            case "deactivate":
                Self::whereIn('category_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function getAll($active = true, $limit = '')
    {
        $query = Self::whereNotNull('news_categories.category_id');
        if ($active) {
            $query->where('status', 1);
        }
        if ($limit) {
            $query->skip(0);
            $query->take($limit);
        }
        $query->from(Self::$tbl);
        $query->orderBy('category_id', 'DESC');
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function list($request)
    {
        $columns = array(
            '',
            'news_categories.title',
            'news_categories.created_at',
            'news_categories.status',
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('news_categories.category_id');
        $query->select(
            'news_categories.*',
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('news_categories.status', $request['status']);
        }
        $query->groupBy('news_categories.category_id');
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
        $query = Self::whereNotNull('news_categories.category_id');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('news_categories.title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('news_categories.status', $request['status']);
        }
        $query->groupBy('news_categories.category_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($categories)
    {
        $sorted = array();
        foreach ($categories as $u) {
            $actions = '';
            $u = objToArr($u);
            $id = $u['category_id'];
            if ($u['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (allowedTo('edit_news_categories')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-news-category" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (allowedTo('delete_news_categories')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-news-category" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($u['title']),
                date('d M, Y', strtotime($u['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-news-category-status" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }  
}