<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Admin\Role;

class PermissionsHelper
{
    private static $values = null;

    /**
     * Call this method to get singleton
     *
     * @param $user
     * @param $index
     * @return UserFactory
     */
    public static function Instance($user, $index = '')
    {
        if (Self::$values === null) {
            Self::$values = Role::getUserPermissions($user);
        }
        return Self::$values;
    }
}
    