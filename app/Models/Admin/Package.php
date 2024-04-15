<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Package extends Model
{
    protected $table = 'packages';
    protected static $tbl = 'packages';
    protected $primaryKey = 'package_id';

    protected $fillable = [
        'package_id',
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

    public static function getPackage($column, $value)
    {
    	$package = Self::where($column, $value)->first();
    	return $package ? $package : emptyTableColumns(Self::$tbl);
    }

    public static function storePackage($data, $edit = null, $image = '')
    {
        unset($data['package_id'], $data['_token']);
        if ($image) {
            $data['image'] = $image;
        }
        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('package_id', $edit)->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['status'] = 1;
            Self::insert($data);
        }
    }

    public static function changeStatus($package_id, $status)
    {
        Self::where('package_id', $package_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function changeStatusFree($package_id, $status)
    {
        Self::whereNotNull('packages.package_id')->update(array('is_free' => 0));
        Self::where('package_id', $package_id)->update(array('is_free' => ($status == 1 ? 1 : 0)));
    }

    public static function changeStatusTop($package_id, $status)
    {
        Self::whereNotNull('packages.package_id')->update(array('is_top_sale' => 0));
        Self::where('package_id', $package_id)->update(array('is_top_sale' => ($status == 1 ? 1 : 0)));
    }

    public static function remove($package_id)
    {
        Self::where(array('package_id' => $package_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('package_id', $ids)->update(array('status' => '1'));
            break;
            case "deactivate":
                Self::whereIn('package_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function getAll($active = true)
    {
        $query = Self::whereNotNull('packages.package_id');
        if ($active) {
            $query->where('status', 1);
        }
        $query->where('is_free', 0);
        $query->from(Self::$tbl);
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function packagesList($request)
    {
        $columns = array(
            '',
            'packages.title',
            'packages.currency',
            'packages.monthly_price',
            'packages.yearly_price',
            'packages.active_jobs',
            'packages.active_users',
            'packages.active_custom_filters',
            'packages.active_quizes',
            'packages.active_interviews',
            'packages.active_traites',
            'packages.branding',
            'packages.role_permissions',
            'packages.custom_emails',
            'packages.created_at',
            'packages.is_free',
            'packages.is_top_sale',
            'packages.status',
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('packages.package_id');
        $query->from('packages');
        $query->select(
            'packages.*',
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('packages.status', $request['status']);
        }
        $query->groupBy('packages.package_id');
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
        $query = Self::whereNotNull('packages.package_id');
        $query->from('packages');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('packages.title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('packages.status', $request['status']);
        }
        $query->groupBy('packages.package_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($packages)
    {
        $sorted = array();
        foreach ($packages as $u) {
            $actions = '';
            $u = objToArr($u);
            $id = $u['package_id'];
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
            if (allowedTo('edit_package')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-package" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (allowedTo('delete_package')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-package" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($u['title']),
                esc_output($u['currency']),
                esc_output($u['monthly_price']),
                esc_output($u['yearly_price']),
                esc_output($u['active_jobs']),
                esc_output($u['active_users']),
                esc_output($u['active_custom_filters']),
                esc_output($u['active_quizes']),
                esc_output($u['active_interviews']),
                esc_output($u['active_traites']),
                yesOrNo($u['branding']),
                yesOrNo($u['role_permissions']),
                yesOrNo($u['custom_emails']),
                date('d M, Y', strtotime($u['created_at'])),
                '<button type="button" title="'.$button_title_if.'" class="btn btn-'.$button_class_if.' btn-xs change-package-free" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text_if.'</button>',
                '<button type="button" title="'.$button_title_ts.'" class="btn btn-'.$button_class_ts.' btn-xs change-package-top" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text_ts.'</button>',
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-package-status" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }

    public static function getPackagesForCSV($ids)
    {
        $query = Self::whereNotNull('packages.package_id');
        $query->from('packages');
        $query->select(
            'packages.*'
        );
        $query->whereIn('packages.package_id', explode(',', $ids));
        $query->groupBy('packages.package_id');
        $query->orderBy('packages.created_at', 'DESC');
        return $query->get();
    }    
}