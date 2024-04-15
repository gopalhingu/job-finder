<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Package extends Model
{
    protected $table = 'packages';
    protected static $tbl = 'packages';
    protected $primaryKey = 'package_id';

    public static function getPackage($column, $value)
    {
    	$value = $column == 'package_id' || $column == 'packages.package_id' ? decode($value) : $value;
    	$package = Self::where($column, $value)->first();
    	return $package ? objToArr($package) : emptyTableColumns(Self::$tbl);
    }
}