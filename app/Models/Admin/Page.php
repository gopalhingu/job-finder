<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Page extends Model
{
    protected $table = 'pages';
    protected static $tbl = 'pages';
    protected $primaryKey = 'page_id';

    protected $fillable = [
        'page_id',
        'title',
        'slug',
        'summary',
        'description',
        'status',
        'created_at',
        'updated_at',
    ];

    public static function getPage($column, $value)
    {
    	$page = Self::where($column, $value)->first();
    	return $page ? $page : emptyTableColumns(Self::$tbl);
    }

    public static function storePage($data, $edit = null)
    {
        unset($data['page_id'], $data['_token']);
        if (!$data['slug']) {
            $data['slug'] = Self::getSlug($data['title'], $edit);
        }

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('page_id', $edit)->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['status'] = 1;
            Self::insert($data);
        }
    }

    public static function changeStatus($page_id, $status)
    {
        Self::where('page_id', $page_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($page_id)
    {
        Self::where(array('page_id' => $page_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('page_id', $ids)->update(array('status' => '1'));
            break;
            case "deactivate":
                Self::whereIn('page_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function getAll($active = true)
    {
        $query = Self::whereNotNull('pages.page_id');
        if ($active) {
            $query->where('status', 1);
        }
        $query->from(Self::$tbl);
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function pagesList($request)
    {
        $columns = array(
            '',
            'title',
            'pages.summary',
            'pages.created_at',
            'pages.status',
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('pages.page_id');
        $query->from('pages');
        $query->select(
            'pages.*'
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('description', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('pages.status', $request['status']);
        }
        $query->groupBy('pages.page_id');
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
        $query = Self::whereNotNull('pages.page_id');
        $query->from('pages');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('pages.description', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('pages.status', $request['status']);
        }
        $query->groupBy('pages.page_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($pages)
    {
        $sorted = array();
        foreach ($pages as $u) {
            $actions = '';
            $u = objToArr($u);
            $id = $u['page_id'];
            if ($u['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (allowedTo('edit_page')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-page" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (allowedTo('delete_page')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-page" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($u['title']).' ('.esc_output($u['slug']).')',
                esc_output($u['summary']),
                date('d M, Y', strtotime($u['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-page-status" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }

    public static function getPagesForCSV($ids)
    {
        $query = Self::whereNotNull('pages.page_id');
        $query->from('pages');
        $query->select(
            'pages.*'
        );
        $query->whereIn('pages.page_id', explode(',', $ids));
        $query->groupBy('pages.page_id');
        $query->orderBy('pages.created_at', 'DESC');
        return $query->get();
    }

    private static function getSlug($title, $edit)
    {
        $slug = slugify($title);
        $numbers = range(1, 100);
        array_unshift($numbers , '');
        foreach ($numbers as $number) {
            $completeSlug = $slug.($number ? '-'.$number : '');
            $query = Self::whereNotNull('pages.page_id');
            $query->from('pages');
            $query->where('pages.slug', $completeSlug);
            $count = $query->get()->count();
            if ($count == 0) {
                return $completeSlug;
            }
        }
    }     
}