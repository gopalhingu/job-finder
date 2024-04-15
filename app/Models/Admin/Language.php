<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Language extends Model
{
    protected $table = 'languages';
    protected static $tbl = 'languages';
    protected $primaryKey = 'language_id';

    public static function getLanguage($column, $value)
    {
        $language = Self::where($column, $value)->first();
        return $language ? $language : emptyTableColumns(Self::$tbl);
    }

    public static function getSelected()
    {
        $language = Self::where('is_selected', 1)->first();
        return $language ? $language->slug : '';
    }

    public static function getDefault($full = false)
    {
        $language = Self::where('is_default', 1)->first();
        if ($full) {
            return $language ? $language : array();
        }
        return $language ? $language->slug : 'origin';
    }

    public static function storeLanguage($data, $edit = null)
    {
        unset($data['_token']);

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            $data['slug'] = $data['slug'] ? $data['slug'] : makeSlug($data['title']);
            Self::where('language_id', $edit)->update($data);
        } else {
            $langUpdate['slug'] = $data['slug'] ? $data['slug'] : makeSlug($data['title']);
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            return array('id' => $id, 'title' => $data['title'], 'slug' => $data['slug']);
        }
    }

    public static function changeStatus($language_id, $status)
    {
        Self::where('language_id', $language_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function changeDefault($language_id)
    {
        Self::whereNotNull('language_id')->update(array('is_default' => 0));
        Self::where('language_id', $language_id)->update(array('is_default' => 1));
    }

    public static function changeSelected($language_id)
    {
        //First making all disabled
        Self::whereNotNull('languages.language_id')->update(array('is_selected' => 0));

        //Second making selected enabled
        Self::where('language_id', $language_id)->update(array('is_selected' => 1));
    }

    public static function remove($language_id)
    {
        Self::where(array('language_id' => $language_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('language_id', $ids)->update(array('status' => 1));
            break;
            case "deactivate":
                Self::whereIn('language_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $query = Self::whereNotNull('languages.language_id');
        $query->where($field, $value);
        if ($edit) {
            $query->where('language_id', '!=', $edit);
        }
        $query = $query->get();
        return $query->count() > 0 ? true : false;
    }

    public static function getAll($active = true)
    {
        $query = Self::whereNotNull('languages.language_id');
        if ($active) {
            $query->where('status', 1);
        }
        $query->where('is_main', 0);
        $query->from(Self::$tbl);
        $query = $query->get();
        return $query ? objToArr($query->toArray()) : array();
    }

    public static function languagesList($request)
    {
        $columns = array(
            "",
            "languages.title",
            "languages.created_at",
            "languages.is_selected",
            "languages.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('languages.language_id');
        $query->select(
            'languages.*'
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('languages.title', 'like', '%'.$srh.'%');
            });            
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('languages.status', $request['status']);
        }
        $query->where('languages.is_main', 0);
        $query->groupBy('languages.language_id');
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
        $query = Self::whereNotNull('languages.language_id');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('languages.title', 'like', '%'.$srh.'%');
            });            
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('languages.status', $request['status']);
        }
        $query->where('languages.is_main', 0);
        $query->groupBy('languages.language_id');
        $query = $query->get();
        return $query->count();
    }

    private static function prepareDataForTable($languages)
    {
        $sorted = array();
        foreach ($languages as $c) {
            $c = objToArr($c);
            if ($c['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if ($c['is_default'] == 1) {
                $button_text_d = 'Default';
                $button_class_d = 'success';
                $button_title_d = 'Default';
            } else {
                $button_text_d = 'Click To Make Default';
                $button_class_d = 'danger';
                $button_title_d = 'Click to Make Default';
            }
            if ($c['is_selected'] == 1) {
                $button_text_s = 'Selected';
                $button_class_s = 'success';
                $button_title_s = 'Selected';
            } else {
                $button_text_s = 'Click To Select';
                $button_class_s = 'danger';
                $button_title_s = 'Click to select';
            }
            $actions = '
                <button type="button" class="btn btn-warning btn-xs create-or-edit-language" title="Edit" data-id="'.$c['language_id'].'"><i class="far fa-edit"></i></button>
                <button type="button" class="btn btn-primary btn-xs edit-language-messages" title="edit messages" data-id="'.$c['language_id'].'"><i class="far fa-edit"></i></button>
                <button type="button" class="btn btn-primary btn-xs edit-language-validations" title="edit validation messages" data-id="'.$c['language_id'].'"><i class="far fa-edit"></i></button>
                <button type="button" class="btn btn-danger btn-xs delete-language" data-id="'.$c['language_id'].'"><i class="far fa-trash-alt"></i></button>
            ';
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$c['language_id']."' />",
                "<img src='".url('a-assets')."/flags/".$c['flag'].".png'/> ".$c['title'],
                date('d M, Y', strtotime($c['created_at'])),
                '<button type="button" title="'.$button_title_d.'" class="btn btn-'.$button_class_d.' btn-xs change-language-default" data-selected="'.$c['is_default'].'" data-id="'.$c['language_id'].'">'.$button_text_d.'</button>',
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-language-status" data-status="'.$c['status'].'" data-id="'.$c['language_id'].'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }
}