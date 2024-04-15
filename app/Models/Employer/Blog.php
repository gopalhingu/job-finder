<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Blog  extends Model
{
    protected $table = 'blogs';
    protected static $tbl = 'blogs';
    protected $primaryKey = 'blog_id';

    public static function getBlog($column, $value)
    {
        $value = $column == 'blog_id' || $column == 'blogs.blog_id' ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function changeStatus($blog_id, $status)
    {
        Self::where('blog_id', decode($blog_id))->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function store($data, $edit = null, $image = '')
    {
        //Replacing &nbsp with space
        $string = htmlentities($data['description'], null, 'utf-8');
        $data['description'] = str_replace("&nbsp;", " ", $string);
        $data['description'] = html_entity_decode($data['description']);
        $data['description'] = removeUselessLineBreaks($data['description']);

        unset($data['_token'], $data['blog_id']);
        $data['employer_id'] = employerId();
        $data['blog_category_id'] = decode($data['blog_category_id']);

        if ($image) {
            $data['image'] = $image;
        }        

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('blog_id', decode($edit))->update($data);
            return $edit;
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            return encode($id);
        }
    }

    public static function remove($blog_id)
    {
        Self::where(array('blog_id' => decode($blog_id)))->delete();        
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data['data']));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('blog_id', decodeArray($ids))->update(array('status' => 1));
            break;
            case "deactivate":
                Self::whereIn('blog_id', decodeArray($ids))->update(array('status' => '0'));
            break;
        }
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $query = Self::where($field, $value);
        $query->where('employer_id', employerId());
        if ($edit) {
            $query->where('blog_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;

    }

    public static function getAll($active = true, $srh = '')
    {
        $query = Self::whereNotNull('blogs.blog_id');
        if ($active) {
            $query->where('status', 1);
        }
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('blogs.title', 'like', '%'.$srh.'%');
            });            
        }
        $query->where('employer_id', employerId());
        return $query->get();
    }

    public static function blogsList($request)
    {
        $columns = array(
            "",
            "blogs.title",
            "blogs.created_at",
            "blogs.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('blogs.blog_id');
        $query->select(
            'blogs.*'
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('blogs.title', 'like', '%'.$srh.'%');
            });            
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('blogs.status', $request['status']);
        }
        $query->where('employer_id', employerId());        
        $query->groupBy('blogs.blog_id');
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
        $query = Self::whereNotNull('blogs.blog_id');
        $query->where('blogs.employer_id', employerId());        
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('blogs.title', 'like', '%'.$srh.'%');
            });            
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('blogs.status', $request['status']);
        }
        $query->groupBy('blogs.blog_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($blogs)
    {
        $sorted = array();
        foreach ($blogs as $b) {
            $actions = '';
            $id = encode($b['blog_id']);
            if ($b['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (empAllowedTo('edit_blog')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-blog" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (empAllowedTo('delete_blog')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-blog" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($b['title']),
                date('d M, Y', strtotime($b['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-blog-status" data-status="'.$b['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }
}