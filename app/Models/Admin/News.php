<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class News extends Model
{
    protected $table = 'news';
    protected static $tbl = 'news';
    protected $primaryKey = 'news_id';

    protected $fillable = [
        'news_id',
        'title',
        'summary',
        'description',
        'status',
        'created_at',
        'updated_at',
    ];

    public static function getNews($column, $value)
    {
        $query = Self::whereNotNull('news.news_id');
        $query->where($column, $value);
        $query->select(
            'news.*',
            'news_categories.title as category'
        );
        $query->where('news.status', 1);
        $query->leftJoin('news_categories', 'news_categories.category_id', '=', 'news.category_id');
        $result = $query->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function storeNews($data, $edit = null, $image = '')
    {
        unset($data['news_id'], $data['_token']);
        if ($image) {
            $data['image'] = $image;
        }        
        if (!$data['slug']) {
            $data['slug'] = Self::getSlug($data['title'], $edit);
        }
        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('news_id', $edit)->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['status'] = 1;
            Self::insert($data);
        }
    }

    public static function changeStatus($news_id, $status)
    {
        Self::where('news_id', $news_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($news_id)
    {
        Self::where(array('news_id' => $news_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('news_id', $ids)->update(array('status' => '1'));
            break;
            case "deactivate":
                Self::whereIn('news_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function getAll($active = true, $limit = '', $request)
    {
        $query = Self::whereNotNull('news.news_id');
        $query->select(
            'news.*',
            'news_categories.title as category'
        );
        if ($active) {
            $query->where('news.status', 1);
        }
        if ($request->get('search')) {
            $query->where('news.title', 'like', '%'.$request->get('search').'%');
        }
        if ($request->get('category')) {
            $query->where('news.category_id', decode($request->get('category')));
        }
        $query->leftJoin('news_categories', 'news_categories.category_id', '=', 'news.category_id');
        $query->orderBy('news.news_id', 'DESC');
        $query->groupBy('news.news_id');
        return $query->paginate($limit);
    }

    public static function newsList($request)
    {
        $columns = array(
            '',
            'news.title',
            'news.category_id',
            'news.summary',
            'news.status',
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('news.news_id');
        $query->from('news');
        $query->select(
            'news.*',
            'news_categories.title as category'
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('news.status', $request['status']);
        }
        $query->leftJoin('news_categories', 'news_categories.category_id', '=', 'news.category_id');
        $query->groupBy('news.news_id');
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
        $query = Self::whereNotNull('news.news_id');
        $query->from('news');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('news.title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('news.status', $request['status']);
        }
        $query->leftJoin('news_categories', 'news_categories.category_id', '=', 'news.category_id');
        $query->groupBy('news.news_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($news)
    {
        $sorted = array();
        foreach ($news as $u) {
            $actions = '';
            $u = objToArr($u);
            $id = $u['news_id'];
            if ($u['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (allowedTo('edit_news')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-news" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (allowedTo('delete_news')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-news" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($u['title']).' ('.esc_output($u['slug']).')',
                esc_output($u['category']),
                esc_output($u['summary']),
                date('d M, Y', strtotime($u['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-news-status" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }

    public static function getNewsForCSV($ids)
    {
        $query = Self::whereNotNull('news.news_id');
        $query->from('news');
        $query->select(
            'news.*'
        );
        $query->whereIn('news.news_id', explode(',', $ids));
        $query->groupBy('news.news_id');
        $query->orderBy('news.created_at', 'DESC');
        return $query->get();
    }

    public static function getForHome($status = true, $limit, $orderByCol, $orderByDir)
    {
        $query = Self::whereNotNull('news.description');
        $query->select(
            'news.*',
            'news_categories.title as category',
        );
        if ($status) {
            $query->where('news.status', 1);
        }
        $query->skip(0);
        if ($orderByCol) {
            $query->skip(0);
            $query->take($limit);
        }
        $query->take($limit);
        $query->leftJoin('news_categories', 'news.category_id', '=', 'news_categories.category_id');
        if ($orderByCol) {
            $query->orderBy($orderByCol, $orderByDir);
        }
        $result = $query->get();
        return $result ? objToArr($result->toArray()) : array();
    }    

    private static function getSlug($title, $edit)
    {
        $slug = slugify($title);
        $numbers = range(1, 100);
        array_unshift($numbers , '');
        foreach ($numbers as $number) {
            $completeSlug = $slug.($number ? '-'.$number : '');
            $query = Self::whereNotNull('news.news_id');
            $query->from('news');
            $query->where('news.slug', $completeSlug);
            $count = $query->count();
            if ($count == 0) {
                return $completeSlug;
            }
        }
    }
}