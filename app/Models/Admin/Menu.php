<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Admin\News;
use App\Models\Admin\Page;

class Menu extends Model
{
    protected $table = 'menus';
    protected static $tbl = 'menus';
    protected $primaryKey = 'menu_id';

    public static function getMenu($column, $value)
    {
    	$menu = Self::where($column, $value)->first();
    	return $menu ? $menu->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function itemInMenu($item)
    {
        return Self::where('menus.type', $item)->first();
    }

    public static function storeMenu($menu_item_id, $sub_item_id, $title, $link, $alignment)
    {
        $order = Self::count() + 1;
        $data['menu_item_id'] = $menu_item_id;
        $data['type'] = $menu_item_id;
        $data['order'] = $order;
        $data['alignment'] = $alignment;
        $url = env('APP_URL');

        switch ($menu_item_id) {
            case 'select_page':
                $page = Page::getPage('pages.page_id', $sub_item_id);
                $data['title'] = $page->title;
                $data['link'] = $url.'pages'.'/'.$page->slug;
                break;
            case 'select_news':
                $page = News::getNews('news.news_id', $sub_item_id);
                $data['title'] = $page->title;
                $data['link'] = $url.'news'.'/'.$page->slug;
                break;
            case 'home_main':
                $data['title'] = 'message.home';
                $data['link'] = $url;
                break;
            case 'features':
                $data['title'] = 'message.features';
                $data['link'] = $url.'features';
                break;
            case 'pricing':
                $data['title'] = 'message.pricing';
                $data['link'] = $url.'pricing';
                break;
            case 'news':
                $data['title'] = 'message.news';
                $data['link'] = $url.'news';
                break;
            case 'contact':
                $data['title'] = 'message.contact';
                $data['link'] = $url.'contact';
                break;
            case 'faqs':
                $data['title'] = 'message.faqs';
                $data['link'] = $url.'faqs';
                break;
            case 'all_companies_page':
                $data['title'] = 'message.companies';
                $data['link'] = $url.'companies';
                break;
            case 'all_candidates_page':
                $data['title'] = 'message.candidates';
                $data['link'] = $url.'candidates';
                break;
            case 'all_news_page':
                $data['title'] = 'message.news';
                $data['link'] = $url.'news';
                break;
            case 'all_jobs_page':
                $data['title'] = 'message.jobs';
                $data['link'] = $url.'jobs';
                break;
            case 'register_button':
                $data['title'] = 'message.register';
                $data['link'] = '#';
                break;
            case 'login_button':
                $data['title'] = 'message.login';
                $data['link'] = '#';
                break;
            case 'language_button':
                $data['title'] = 'message.language';
                $data['link'] = '#';
                break;
            case 'dark_mode_button':
                $data['title'] = 'message.dark_mode_button';
                $data['link'] = '#';
                break;
            case 'static_external':
                $data['title'] = $title;
                $data['link'] = $link;
                break;
            default:
                die(__('message.not_allowed'));
                break;
        }

        $insert = Self::insert($data);        
    }

    public static function orderUpdate($request)
    {
        $items = objToArr(json_decode($request->input('data')));
        $items = $items['list'];
        Self::orderUpdateRecursive($items);
    }

    private static function orderUpdateRecursive($items, $parent = null)
    {
        foreach ($items as $order => $item) {
            $data['order'] = $order + 1;
            if (isset($item['children'])) {
                Self::orderUpdateRecursive($item['children'], $item['id']);
            }
            $data['parent_id'] = $parent ? $parent : 0;
            $query = Self::whereNotNull('menus.menu_id');
            $query->where('menu_id', $item['id'])->update($data);
        }
    }    

    public static function remove($menu_id)
    {
    	Self::where('menu_id', $menu_id)->delete();
    }

    public static function getAll($alignment = '')
    {
    	$query = Self::whereNotNull('menus.menu_id');
        $query->select(
        	"menus.*",
        );
        if ($alignment) {
            $query->where('alignment', $alignment);
        }
        $query->from(Self::$tbl);
        $query->orderBy('menus.created_at', 'DESC');
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function getAllForMenu($alignment = '')
    {
        $query = Self::whereNotNull('menus.menu_id');
        if ($alignment) {
            $query->where('alignment', $alignment);
        }
        $query->from(Self::$tbl);
        $query->orderBy('order', 'ASC');
        $query = $query->get();
        $result = Self::sort($query->toArray(), 'menu_id');
        return $result;
    }

    private static function sort($items, $type = '')
    {
        $return = array();
        foreach ($items as $item) {
            $id = $item[$type];
            if ($item['parent_id'] == 0) {
                $return[$id] = $item;
                $return[$id]['childs'] = Self::getchilds($items, $id, $type);
            }
        }
        return $return;
    }

    private static function getchilds($items, $id, $type)
    {
        $return = array();
        foreach ($items as $item) {
            if ($item['parent_id'] == $id) {
                $return[$item[$type]] = $item;
                $return[$item[$type]]['childs'] = Self::getchilds($items, $item[$type], $type);
            }
        }
        return $return;
    }

}