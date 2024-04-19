<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';
    protected static $tbl = 'company';
    protected $primaryKey = 'company_id';

    protected $fillable = [
        'company_id',
        'slug',
        'first_name',
        'last_name',
        'companyname',
        'email',
        'password',
        'image',
        'phone1',
        'phone2',
        'city',
        'state',
        'country',
        'address',
        'gender',
        'dob',
        'status',
        'token',
        'created_at',
        'updated_at',
    ];
}
