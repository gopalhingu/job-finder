<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Faq extends Model
{
    protected $table = 'faqs';
    protected static $tbl = 'faqs';
    protected $primaryKey = 'faqs_id';

    protected $fillable = [
        'faqs_id',
        'question',
        'answer',
        'status',
        'created_at',
        'updated_at',
    ];

    public static function getFaq($column, $value)
    {
    	$faqs = Self::where($column, $value)->first();
    	return $faqs ? $faqs : emptyTableColumns(Self::$tbl);
    }

    public static function storeFaq($data, $edit = null)
    {
        unset($data['faqs_id'], $data['_token']);
        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('faqs_id', $edit)->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['status'] = 1;
            Self::insert($data);
        }
    }

    public static function changeStatus($faqs_id, $status)
    {
        Self::where('faqs_id', $faqs_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($faqs_id)
    {
        Self::where(array('faqs_id' => $faqs_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('faqs_id', $ids)->update(array('status' => '1'));
            break;
            case "deactivate":
                Self::whereIn('faqs_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function getAll($active = true, $limit = '', $request)
    {
        $query = Self::whereNotNull('faqs.faqs_id');
        if ($active) {
            $query->where('status', 1);
        }
        if ($request->get('keyword')) {
            $query->where('question', 'like', '%'.$request->get('keyword').'%');
        }
        if ($request->get('category')) {
            $query->where('category_id', decode($request->get('category')));
        }
        $query->orderBy('faqs_id', 'DESC');
        return $query->paginate($limit);
    }

    public static function faqsList($request)
    {
        $columns = array(
            '',
            'faqs.category_id',
            'faqs.question',
            'faqs.answer',
            'faqs.status',
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('faqs.faqs_id');
        $query->from('faqs');
        $query->select(
            'faqs.*',
            'faqs_categories.title as category'
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('question', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('faqs.status', $request['status']);
        }
        $query->leftJoin('faqs_categories', 'faqs_categories.category_id', '=', 'faqs.category_id');
        $query->groupBy('faqs.faqs_id');
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
        $query = Self::whereNotNull('faqs.faqs_id');
        $query->from('faqs');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('faqs.question', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('faqs.status', $request['status']);
        }
        $query->leftJoin('faqs_categories', 'faqs_categories.category_id', '=', 'faqs.category_id');
        $query->groupBy('faqs.faqs_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($faqs)
    {
        $sorted = array();
        foreach ($faqs as $u) {
            $actions = '';
            $u = objToArr($u);
            $id = $u['faqs_id'];
            if ($u['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (allowedTo('edit_faqs')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-faqs" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (allowedTo('delete_faqs')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-faqs" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($u['category']),
                esc_output($u['question']),
                esc_output($u['answer']),
                date('d M, Y', strtotime($u['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-faqs-status" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }

    public static function getFaqForCSV($ids)
    {
        $query = Self::whereNotNull('faqs.faqs_id');
        $query->from('faqs');
        $query->select(
            'faqs.*'
        );
        $query->whereIn('faqs.faqs_id', explode(',', $ids));
        $query->groupBy('faqs.faqs_id');
        $query->orderBy('faqs.created_at', 'DESC');
        return $query->get();
    }

    public static function getForPricingPage()
    {
        $query = Self::whereNotNull('faqs.faqs_id');
        $query->select(
            'faqs.*',
            'faqs_categories.title as category'
        );
        $query->where('faqs.status', 1);
        $query->where('faqs.category_id', 1);
        $query->leftJoin('faqs_categories', 'faqs.category_id', '=', 'faqs_categories.category_id');
        $query->orderBy('faqs.created_at', 'DESC');
        $result = $query->get();
        return $result ? objToArr($result->toArray()) : array();
    }    
}