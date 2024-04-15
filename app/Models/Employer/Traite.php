<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Traite extends Model
{
    protected $table = 'traites';
    protected static $tbl = 'traites';
    protected $primaryKey = 'traite_id';

    public static function getTraite($column, $value)
    {
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function storeTraite($data, $edit = null)
    {
        unset($data['_token'], $data['traite_id']);
        $data['employer_id'] = employerId();

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('traite_id', decode($edit))->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            return encode($id);
        }
    }

    public static function changeStatus($traite_id, $status)
    {
        Self::where('traite_id', $traite_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($traite_id)
    {
        Self::where(array('traite_id' => $traite_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data['data']));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('traite_id', $ids)->update(array('status' => 1));
            break;
            case "deactivate":
                Self::whereIn('traite_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $query = Self::where($field, $value);
        $query->where('employer_id', employerId());        
        if ($edit) {
            $query->where('traite_id', '!=', $edit);
        }
        return $query->get()->count() > 0 ? true : false;
    }

    public static function getAll($active = true)
    {
        $query = Self::whereNotNull('traites.traite_id');
        $query->where('employer_id', employerId());
        if ($active) {
            $query->where('status', 1);
        }
        $query = $query->get();
        return $query ? $query->toArray() : array();
    }

    public static function traitesList($request)
    {
        $columns = array(
            "",
            "traites.title",
            "traites.created_at",
            "traites.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('traites.traite_id');
        $query->select(
            'traites.*'
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('traites.title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('traites.status', $request['status']);
        }
        $query->where('employer_id', employerId());        
        $query->groupBy('traites.traite_id');
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
        $query = Self::whereNotNull('traites.traite_id');
        $query->where('employer_id', employerId());
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('traites.title', 'like', '%'.$srh.'%');
            });            
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('traites.status', $request['status']);
        }
        $query->groupBy('traites.traite_id');
        return $query->get()->count();
    }

    public static function getTotalTraites($id = '')
    {
        $query = Self::whereNotNull('traites.traite_id');
        $query->where('status', 1);
        $query->where('employer_id', employerId());
        if ($id) {
            $query->where('traites.traite_id', '!=', decode($id));
        }
        return $query->get()->count();
    }    

    private static function prepareDataForTable($traites)
    {
        $sorted = array();
        foreach ($traites as $c) {
            $actions = '';
            if ($c['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (empAllowedTo('edit_traites')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-traite" data-id="'.$c['traite_id'].'"><i class="far fa-edit"></i></button>
            ';
            }
            if (empAllowedTo('delete_traites')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-traite" data-id="'.$c['traite_id'].'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$c['traite_id']."' />",
                esc_output($c['title'], 'html'),
                date('d M, Y', strtotime($c['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-traite-status" data-status="'.$c['status'].'" data-id="'.$c['traite_id'].'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }

    public static function getJobTraites($job_id)
    {
        $query = DB::table('job_traites')->whereNotNull();
        $query->where('job_traites.job_id', $job_id);
        $query = $query->get();
        return $query ? $query->toArray() : array();
    }   
}