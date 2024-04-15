<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Admin\CandidatePackage;

class CandidateMembership extends Model
{
    protected $table = 'candidate_memberships';
    protected static $tbl = 'candidate_memberships';
    protected $primaryKey = 'candidate_membership_id';    

    public static function getFirst($column, $value)
    {
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : array();
    }

    public static function getActiveMembership($value = '')
    {
        $query = Self::whereNotNull('candidate_memberships.candidate_membership_id');
        $query->select('candidate_memberships.*');
        $query->where('candidate_memberships.candidate_id', candidateSession());
        $query->where('candidate_memberships.status', 1);
        $result = $query->first();
        $result = isset($result->details) ? objToArr(json_decode($result->details)) : array();
        if ($value && isset($result[$value])) {
            return $result[$value];
        }
        return "0";
    }

    public static function getItemsList()
    {
        $query = Self::whereNotNull('candidate_memberships.candidate_membership_id');
        $query->select('candidate_memberships.*');
        $query->where('candidate_memberships.candidate_id', candidateSession());
        $query->orderBy('candidate_memberships.updated_at', 'DESC');
        $query->groupBy('candidate_memberships.candidate_membership_id');
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function getPackages()
    {
        $query = DB::table('candidate_packages')->whereNotNull('candidate_packages.candidate_package_id');
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
        Self::deactivateAllOtherMemberships($data['candidate_id']);
        Self::insert($data);
        $payment_id = DB::getPdo()->lastInsertId();
        return encode($payment_id);
    }

    public static function storeMembership($data, $edit = null)
    {
        unset($data['membership_id'], $data['_token']);

        //Getting candidate_package details and cleaning
        $candidate_package = CandidatePackage::getSingle('candidate_packages.candidate_package_id', decode($data['candidate_package_id']));
        unset($candidate_package['candidate_package_id'], $candidate_package['currency'], $candidate_package['monthly_price'], $candidate_package['yearly_price'], 
        $candidate_package['is_free'], $candidate_package['is_top_sale'], $candidate_package['status'], $candidate_package['created_at'], $candidate_package['updated_at']);

        if ($edit) {
            if ($data['status'] == 1) {
                Self::deactivateAllOtherMemberships(decode($data['candidate_id']));
            }

            $data['updated_at'] = date('Y-m-d G:i:s');
            $data['show_hide_personal_info'] = $candidate_package['show_hide_personal_info'];
            Self::where('membership_id', decode($edit))->update($data);

        } else {
            //First deactivating all other memberships of particular candidate
            Self::deactivateAllOtherMemberships(decode($data['candidate_id']));

            //Second Inserting new one
            $data['details'] = json_encode(objToArr($candidate_package));
            $data['show_hide_personal_info'] = $candidate_package['show_hide_personal_info'];
            $data['transaction_id'] = strtotime(date('Y-m-d G:i:s').appId());
            $data['created_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
        }
    }

    private static function deactivateAllOtherMemberships($candidate_id)
    {
        Self::where('candidate_id', $candidate_id)->update(array('status' => '0'));
    }

}