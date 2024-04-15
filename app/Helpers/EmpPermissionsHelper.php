<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Employer\Role as EmployerRole;

class EmpPermissionsHelper
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
            Self::$values = EmployerRole::getEmployerPermissions($employer);
        }
        return Self::$values;
    }
}
    