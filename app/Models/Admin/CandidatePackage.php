<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CandidatePackage extends Model
{
    protected $table = 'candidate_packages';
    protected static $tbl = 'candidate_packages';
    protected $primaryKey = 'candidate_package_id';

    protected $fillable = [
        'candidate_package_id',
        'title',
        'description',
        'currency',
        'monthly_price',
        'yearly_price',
        'active_jobs',
        'active_users',
        'active_custom_filters',
        'active_quizes',
        'active_interviews',
        'separate_site',
        'active_traites',
        'branding',
        'role_permissions',
        'custom_emails',
        'is_free',
        'is_top_sale',
        'status',
        'created_at',
        'updated_at',
    ];

    public static function getSingle($column, $value)
    {
    	$item = Self::where($column, $value)->first();
    	return $item ? $item : emptyTableColumns(Self::$tbl);
    }

    public static function storeItem($data, $edit = null, $image = '')
    {
        unset($data['candidate_package_id'], $data['_token']);
        if ($image) {
            $data['image'] = $image;
        }
        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('candidate_package_id', $edit)->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['status'] = 1;
            Self::insert($data);
        }
    }

    public static function changeStatus($candidate_package_id, $status)
    {
        Self::where('candidate_package_id', $candidate_package_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function changeStatusFree($candidate_package_id, $status)
    {
        Self::whereNotNull('candidate_packages.candidate_package_id')->update(array('is_free' => 0));
        Self::where('candidate_package_id', $candidate_package_id)->update(array('is_free' => ($status == 1 ? 1 : 0)));
    }

    public static function changeStatusTop($candidate_package_id, $status)
    {
        Self::whereNotNull('candidate_packages.candidate_package_id')->update(array('is_top_sale' => 0));
        Self::where('candidate_package_id', $candidate_package_id)->update(array('is_top_sale' => ($status == 1 ? 1 : 0)));
    }

    public static function remove($candidate_package_id)
    {
        Self::where(array('candidate_package_id' => $candidate_package_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('candidate_package_id', $ids)->update(array('status' => '1'));
            break;
            case "deactivate":
                Self::whereIn('candidate_package_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function getAll($active = true)
    {
        $query = Self::whereNotNull('candidate_packages.candidate_package_id');
        if ($active) {
            $query->where('status', 1);
        }
        $query->from(Self::$tbl);
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function itemsList($request)
    {
        $columns = array(
            '',
            'candidate_packages.title',
            'candidate_packages.currency',
            'candidate_packages.monthly_price',
            'candidate_packages.yearly_price',
            'candidate_packages.allowed_job_applications',
            'candidate_packages.allowed_resumes',
            'candidate_packages.show_hide_personal_info',
            'candidate_packages.created_at',
            'candidate_packages.is_free',
            'candidate_packages.is_top_sale',
            'candidate_packages.status',
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('candidate_packages.candidate_package_id');
        $query->from('candidate_packages');
        $query->select(
            'candidate_packages.*',
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('candidate_packages.status', $request['status']);
        }
        $query->groupBy('candidate_packages.candidate_package_id');
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
        $query = Self::whereNotNull('candidate_packages.candidate_package_id');
        $query->from('candidate_packages');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('candidate_packages.title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('candidate_packages.status', $request['status']);
        }
        $query->groupBy('candidate_packages.candidate_package_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($candidate_packages)
    {
        $sorted = array();
        foreach ($candidate_packages as $u) {
            $actions = '';
            $u = objToArr($u);
            $id = $u['candidate_package_id'];
            if ($u['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if ($u['is_free'] == 1) {
                $button_text_if = __('message.yes');
                $button_class_if = 'success';
                $button_title_if = __('message.click_to_disable');
            } else {
                $button_text_if = __('message.no');
                $button_class_if = 'danger';
                $button_title_if = __('message.click_to_enable');
            }
            if ($u['is_top_sale'] == 1) {
                $button_text_ts = __('message.yes');
                $button_class_ts = 'success';
                $button_title_ts = __('message.click_to_disable');
            } else {
                $button_text_ts = __('message.no');
                $button_class_ts = 'danger';
                $button_title_ts = __('message.click_to_enable');
            }
            if (allowedTo('edit_candidate_package')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-candidate-package" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (allowedTo('delete_candidate_package')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-candidate-package" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($u['title']),
                esc_output($u['currency']),
                esc_output($u['monthly_price']),
                esc_output($u['yearly_price']),
                esc_output($u['allowed_job_applications']),
                esc_output($u['allowed_resumes']),
                yesOrNo($u['show_hide_personal_info']),
                date('d M, Y', strtotime($u['created_at'])),
                '<button type="button" title="'.$button_title_if.'" class="btn btn-'.$button_class_if.' btn-xs change-candidate-package-free" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text_if.'</button>',
                '<button type="button" title="'.$button_title_ts.'" class="btn btn-'.$button_class_ts.' btn-xs change-candidate-package-top" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text_ts.'</button>',
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-candidate-package-status" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }

    public static function getItemsForCSV($ids)
    {
        $query = Self::whereNotNull('candidate_packages.candidate_package_id');
        $query->from('candidate_packages');
        $query->select(
            'candidate_packages.*'
        );
        $query->whereIn('candidate_packages.candidate_package_id', explode(',', $ids));
        $query->groupBy('candidate_packages.candidate_package_id');
        $query->orderBy('candidate_packages.created_at', 'DESC');
        return $query->get();
    }    
}