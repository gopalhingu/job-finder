<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Admin\Package;

class Membership extends Model
{
    protected $table = 'memberships';
    protected static $tbl = 'memberships';
    protected $primaryKey = 'membership_id';

    protected $fillable = [
        'membership_id',
        'employer_id',
        'package_id',
        'title',
        'payment_type',
        'package_type',
        'price_paid',
        'details',
        'separate_site',
        'status',
        'expiry',
        'transaction_id',
        'payment_status',
        'payment_currency',
        'receiver_email',
        'payer_email',
        'created_at',
        'updated_at',
    ];

    public static $payment_types = [
        'manual',
        'paypal',
        'stripe',
        'offline'
    ];

    public static $package_types = [
        'free',
        'monthly',
        'annual',
    ];

    public static function getMembership($column, $value)
    {
    	$membership = Self::where($column, $value)->first();
    	return $membership ? $membership : emptyTableColumns(Self::$tbl);
    }

    public static function storeMembership($data, $edit = null)
    {
        unset($data['membership_id'], $data['_token']);

        //Getting package details and cleaning
        $package = Package::getPackage('packages.package_id', $data['package_id']);
        unset($package['package_id'], $package['currency'], $package['monthly_price'], $package['yearly_price'], 
        $package['is_free'], $package['is_top_sale'], $package['status'], $package['created_at'], $package['updated_at']);

        if ($edit) {
            if ($data['status'] == 1) {
                Self::deactiavateAllOtherMemberships($data['employer_id']);
            }

            $data['updated_at'] = date('Y-m-d G:i:s');
            $data['separate_site'] = $package['separate_site'];
            Self::where('membership_id', $edit)->update($data);

        } else {
            //First : deactivating all other memberships of particular employer
            Self::deactiavateAllOtherMemberships($data['employer_id']);

            //Second : Inserting new one
            $data['details'] = json_encode(objToArr($package));
            $data['separate_site'] = $package['separate_site'];
            $data['transaction_id'] = strtotime(date('Y-m-d G:i:s').appId());
            $data['created_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
        }
    }

    private static function deactiavateAllOtherMemberships($employer_id)
    {
        Self::where('employer_id', $employer_id)->update(array('status' => '0'));
    }

    public static function changeStatus($membership_id, $status)
    {
        if ($status != 1) { 
            $membership = Self::getMembership('memberships.membership_id', $membership_id);
            $employer_id = $membership['employer_id'];
            Self::deactiavateAllOtherMemberships($employer_id);
        }
        Self::where('membership_id', $membership_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($membership_id)
    {
        Self::where(array('membership_id' => $membership_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                foreach ($ids as $membership_id) {
                    $membership = Self::getMembership('memberships.membership_id', $membership_id);
                    $employer_id = $membership['employer_id'];
                    Self::deactiavateAllOtherMemberships($employer_id);
                }
                Self::whereIn('membership_id', $ids)->update(array('status' => '1'));
            break;
            case "deactivate":
                Self::whereIn('membership_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function getAll($active = true)
    {
        $query = Self::whereNotNull('memberships.membership_id');
        if ($active) {
            $query->where('status', 1);
        }
        $query->from(Self::$tbl);
        $result = $query->get();
        return $result ? objToArr($result->toArray()) : array();
    }

    public static function sales()
    {
        $query = Self::whereNotNull('memberships.membership_id');
        $query->select(
            DB::Raw('SUM('.dbprfx().'memberships.price_paid) AS sales')
        );
        $result = $query->first();
        $result = $result ? $result->sales : array();
        return $result;
    }

    public static function salesDetail($interval = 'this_month')
    {
        $query = Self::whereNotNull('memberships.membership_id');

        if ($interval == 'this_month') {
            $query->select(
                DB::Raw('CONCAT(DATE_FORMAT(created_at, "%Y"), "-", DATE_FORMAT(created_at, "%m"), "-", DATE_FORMAT(created_at, "%d")) AS labels'),
                DB::Raw('ROUND(SUM(price_paid), 2) AS valuess'),
            );
            $query->whereRaw('MONTH(created_at) = MONTH(CURDATE())');
            $query->whereRaw('YEAR(created_at) = YEAR(CURDATE())');
            $query->groupByRaw('DAY(created_at)');
        } elseif ($interval == 'last_month') {
            $query->select(
                DB::Raw('CONCAT(DATE_FORMAT(created_at, "%Y"), "-", DATE_FORMAT(created_at, "%m"), "-", DATE_FORMAT(created_at, "%d")) AS labels'),
                DB::Raw('ROUND(SUM(price_paid), 2) AS valuess'),
            );
            $query->whereRaw('MONTH(created_at) = MONTH(CURDATE()) - 1');
            $query->whereRaw('YEAR(created_at) = YEAR(CURDATE())');
            $query->groupByRaw('DAY(created_at)');
        } elseif ($interval == 'this_year') {
            $query->select(
                DB::Raw('CONCAT(DATE_FORMAT(created_at, "%Y"), "-", DATE_FORMAT(created_at, "%m")) AS labels'),
                DB::Raw('ROUND(SUM(price_paid), 2) AS valuess'),
            );
            $query->whereRaw('YEAR(created_at) = YEAR(CURDATE())');
            $query->groupByRaw('MONTH(created_at)');
        
        } elseif ($interval == 'last_year') {
            $query->select(
                DB::Raw('CONCAT(DATE_FORMAT(created_at, "%Y"), "-", DATE_FORMAT(created_at, "%m")) AS labels'),
                DB::Raw('ROUND(SUM(price_paid), 2) AS valuess'),
            );
            $query->whereRaw('YEAR(created_at) = YEAR(CURDATE()) - 1');
            $query->groupByRaw('MONTH(created_at)');
        }

        $result = $query->get();
        $result = $result ? Self::sortSalesData($result->toArray(), $interval) : array();
        return $result;
    }

    public static function sortSalesData($data, $interval)
    {
        $labels = array();
        $values = array();

        if ($interval == 'this_month') {
            $dates = datesOfMonth();
        } elseif ($interval == 'last_month') {
            $dates = datesOfMonth('', date('m')-1);
        } elseif ($interval == 'this_year') {
            $dates = monthsOfYear();
        } elseif ($interval == 'last_year') {
            $dates = monthsOfYear(date('Y')-1);
        }

        //Sorting data from db
        $sorted = array();
        foreach ($data as $d) {
            $sorted[$d['labels']] = $d['valuess'];
        }

        //Populating with dates
        foreach ($dates as $date) {
            $labels[] = $date;
            if (isset($sorted[$date])) {
                $values[] = $sorted[$date];
            } else {
                $values[] = 0.00;
            }
        }

        return json_encode(array('labels' => $labels, 'values' => $values));
    }    

    public static function membershipsList($request)
    {
        $columns = array(
            '',
            'employer_id',
            'package_id',
            'title',
            'payment_type',
            'package_type',
            'price_paid',
            'expiry',
            'created_at',
            'status',
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('memberships.membership_id');
        $query->select(
            'memberships.*',
            'packages.title as package_title',
            DB::Raw('CONCAT('.dbprfx().'employers.first_name, " ", '.dbprfx().'employers.last_name, " (", '.dbprfx().'employers.company, ")") AS employer')
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('memberships.status', $request['status']);
        }
        if (isset($request['employer_id']) && $request['employer_id'] != '') {
            $query->where('memberships.employer_id', $request['employer_id']);
        }
        if (isset($request['package_id']) && $request['package_id'] != '') {
            $query->where('memberships.package_id', $request['package_id']);
        }
        if (isset($request['payment_type']) && $request['payment_type'] != '') {
            $query->where('memberships.payment_type', $request['payment_type']);
        }
        if (isset($request['package_type']) && $request['package_type'] != '') {
            $query->where('memberships.package_type', $request['package_type']);
        }
        $query->leftJoin('employers','employers.employer_id', '=', 'memberships.employer_id');
        $query->leftJoin('packages','packages.package_id', '=', 'memberships.package_id');
        $query->groupBy('memberships.membership_id');
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
        $query = Self::whereNotNull('memberships.membership_id');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('memberships.title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('memberships.status', $request['status']);
        }
        if (isset($request['employer_id']) && $request['employer_id'] != '') {
            $query->where('memberships.employer_id', $request['employer_id']);
        }
        if (isset($request['package_id']) && $request['package_id'] != '') {
            $query->where('memberships.package_id', $request['package_id']);
        }
        if (isset($request['payment_type']) && $request['payment_type'] != '') {
            $query->where('memberships.payment_type', $request['payment_type']);
        }
        if (isset($request['package_type']) && $request['package_type'] != '') {
            $query->where('memberships.package_type', $request['package_type']);
        }
        $query->leftJoin('employers','employers.employer_id', '=', 'memberships.employer_id');
        $query->leftJoin('packages','packages.package_id', '=', 'memberships.package_id');
        $query->groupBy('memberships.membership_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($memberships)
    {
        $sorted = array();
        foreach ($memberships as $u) {
            $actions = '';
            $u = objToArr($u);
            $id = $u['membership_id'];
            if ($u['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (allowedTo('edit_membership')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-membership" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (allowedTo('delete_membership')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-membership" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $file = $u['file'] ? membershipgReceiptThumb($u['file']) : '';
            $file = $file ? ' <a target="_blank" href="'.$file['image'].'">'.__('message.file').'</a>' : '';
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($u['employer']),
                esc_output($u['package_title']),
                esc_output($u['title']),
                $u['payment_type'].$file,
                esc_output($u['package_type']),
                esc_output($u['price_paid']),
                date('d M, Y', strtotime($u['expiry'])),
                date('d M, Y', strtotime($u['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-membership-status" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }

    public static function getMembershipsForCSV($ids)
    {
        $query = Self::whereNotNull('memberships.membership_id');
        $query->from('memberships');
        $query->select(
            'memberships.*',
            'packages.title as package_title',
            DB::Raw('CONCAT('.dbprfx().'employers.first_name, " ", '.dbprfx().'employers.last_name, " (", '.dbprfx().'employers.company, ")") AS employer')
        );
        $query->leftJoin('employers','employers.employer_id', '=', 'memberships.employer_id');
        $query->leftJoin('packages','packages.package_id', '=', 'memberships.package_id');
        $query->whereIn('memberships.membership_id', explode(',', $ids));
        $query->groupBy('memberships.membership_id');
        $query->orderBy('memberships.created_at', 'DESC');
        return $query->get();
    }    
}