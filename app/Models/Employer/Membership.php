<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Employer\Package;

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

    public static function getMembership($column, $value)
    {
        $value = $column == 'membership_id' || $column == 'memberships.membership_id' ? decode($value) : $value;
    	$membership = Self::where($column, $value)->first();
    	return $membership ? $membership : emptyTableColumns(Self::$tbl);
    }

    public static function storeMembership($data, $edit = null)
    {
        unset($data['membership_id'], $data['_token']);

        //Getting package details and cleaning
        $package = Package::getPackage('packages.package_id', decode($data['package_id']));
        unset($package['package_id'], $package['currency'], $package['monthly_price'], $package['yearly_price'], 
        $package['is_free'], $package['is_top_sale'], $package['status'], $package['created_at'], $package['updated_at']);

        if ($edit) {
            if ($data['status'] == 1) {
                Self::deactivateAllOtherMemberships(decode($data['employer_id']));
            }

            $data['updated_at'] = date('Y-m-d G:i:s');
            $data['separate_site'] = $package['separate_site'];
            Self::where('membership_id', decode($edit))->update($data);

        } else {
            //First deactivating all other memberships of particular employer
            Self::deactivateAllOtherMemberships(decode($data['employer_id']));

            //Second Inserting new one
            $data['details'] = json_encode(objToArr($package));
            $data['separate_site'] = $package['separate_site'];
            $data['transaction_id'] = strtotime(date('Y-m-d G:i:s').appId());
            $data['created_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
        }
    }

    private static function deactivateAllOtherMemberships($employer_id)
    {
        Self::where('employer_id', $employer_id)->update(array('status' => '0'));
    }

    public static function getPackages()
    {
        $query = DB::table('packages')->whereNotNull('packages.package_id');
        $query->where('status', 1);
        $query->where('is_free', 0);
        $result = $query->get();
        return $result ? objToArr($result->toArray()) : array();
    }

    public static function checkTransactionId($transaction_id)
    {
        $query = DB::table('memberships')->whereNotNull('memberships.membership_id');
        $query->where('memberships.transaction_id', $transaction_id);
        $result = $query->get();
        return ($result->count() > 0) ? false : true;
    }

    public static function addPayment($data)
    {
        Self::deactivateAllOtherMemberships($data['employer_id']);
        Self::insert($data);
        $payment_id = DB::getPdo()->lastInsertId();
        return encode($payment_id);
    }

    public static function membershipsList($request)
    {
        $columns = array(
            'package_id',
            'title',
            'payment_type',
            'package_type',
            'price_paid',
            'status',
            'expiry',
            'created_at',
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('memberships.membership_id');
        $query->from('memberships');
        $query->select(
            'memberships.*',
            'packages.title as package_title',
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('title', 'like', '%'.$srh.'%');
            });
        }
        $query->where('memberships.employer_id', employerId());
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
        $query->from('memberships');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('memberships.title', 'like', '%'.$srh.'%');
            });
        }
        $query->where('memberships.employer_id', employerId());
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
            $sorted[] = array(
                esc_output($u['package_title']),
                esc_output($u['title']),
                esc_output($u['payment_type']),
                esc_output($u['package_type']),
                esc_output($u['price_paid']),
                esc_output($u['status']) == 1 ? __('message.current') : __('message.inactive'),
                date('d M, Y', strtotime($u['expiry'])),
                date('d M, Y', strtotime($u['created_at'])),
            );
        }
        return $sorted;
    }
}