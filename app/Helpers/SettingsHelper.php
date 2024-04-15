<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class SettingsHelper
{
    private static $values = null;

    /**
     * Call this method to get singleton
     *
     * @return UserFactory
     */
    public static function Instance($index = '', $sorted = true)
    {
        if (Self::$values === null) {
            $result = DB::table('settings')->where('employer_id', 0)->get();
            if ($sorted) {
                Self::$values = Self::sort(objToArr($result->toArray()));
            } else {
                Self::$values = objToArr($result->toArray());
            }
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
    