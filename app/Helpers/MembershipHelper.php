<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MembershipHelper
{
    private static $values = null;

    /**
     * Call this method to get singleton
     *
     * @param $employer (init|string) - (employer_id|slug)
     * @return UserFactory
     */
    public static function Instance($employer, $index = '')
    {
        if (Self::$values === null) {
            $query = DB::table('memberships')->whereNotNull('memberships.membership_id');
            $query->select('memberships.*');
            if (is_int($employer)) {
                $query->where('memberships.employer_id', $employer);
            } else {
                $query->where('employers.slug', $employer);
                $query->join('employers', function($join) {
                    $join->on('employers.employer_id', '=', 'memberships.employer_id')
                    ->where('employers.type', '=', 'main');
                });
            }
            $query->where('memberships.status', 1);
            $query->whereDate('memberships.expiry', '>=', Carbon::now());
            $result = $query->first();
            Self::$values = $result ? objToArr($result) : array();
        }

        if (Self::$values) {
            $membershipFeatures = Self::membershipFeatures();
            if ($index == '') {
                return Self::$values;
            } else {
                if (in_array($index, $membershipFeatures)) {
                    $details = objToArr(json_decode(Self::$values['details']));
                    return issetVal($details, $index);
                }
                if ($index == 'details') {
                    return objToArr(json_decode(Self::$values['details']));
                }
                return issetVal(Self::$values, $index);
            }
        } else {
            $zeroValues = Self::membershipFeatures(true);
            if ($index == 'details') {
                return $zeroValues;
            }
            return issetVal($zeroValues, $index);
        }
    }

    private static function membershipFeatures($empty = false)
    {
        if ($empty) {
            return array(
                'active_jobs' => '0',
                'active_users' => '0',
                'active_custom_filters' => '0',
                'active_quizes' => '0',
                'active_interviews' => '0',
                'active_traites' => '0',
                'branding' => '0',
                'role_permissions' => '0',
                'custom_emails' => '0',

            );            
        } else {
            return array(
                'active_jobs',
                'active_users',
                'active_custom_filters',
                'active_quizes',
                'active_interviews', 
                'active_traites',
                'branding',
                'role_permissions',
                'custom_emails',
            );
        }
    }
}
    