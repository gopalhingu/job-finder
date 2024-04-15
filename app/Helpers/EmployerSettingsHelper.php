<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class EmployerSettingsHelper
{
    private static $values = null;

    /**
     * Call this method to get singleton
     *
     * @return UserFactory
     */
    public static function Instance($index = '', $bySlug = false)
    {
        if (Self::$values === null) {
            if ($bySlug) {
                $slugEmp = getSession('slug_emp');
                $employer_id = issetVal($slugEmp, 'employer_id');
            } else {
                $employer_id = employerId();
            }
            $dbValues = DB::table('settings')->where('employer_id', $employer_id)->get();
            Self::$values = Self::sort(objToArr($dbValues->toArray()));
        }
        if ($index == '') {
            return Self::$values;
        } else {
            return isset(Self::$values[$index]) ? Self::$values[$index] : '';
        }
    }

    private static function sort($settings)
    {
        $sorted = array();
        foreach ($settings as $s) {
            $sorted[$s['key']] = $s['value'];
        }
        return $sorted;
    }

}
    