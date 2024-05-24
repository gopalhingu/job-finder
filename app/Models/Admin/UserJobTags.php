<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserJobTags extends Model
{
    protected $table = 'user_job_tags';
    protected static $tbl = 'user_job_tags';
    protected $primaryKey = 'id';

    public static function getJobTags($column, $value)
    {
    	$role = Self::where($column, $value)->first();
    	return $role ? $role->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function storeJobTags($data, $edit = null)
    {
    	unset($data['_token'], $data['id'], $data['csrf_token']);

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
        	Self::where('id', $edit)->update($data);
        	$id = $edit;
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $insert = Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
        }

        return array('id' => $id, 'name' => $data['name']);
    }

    public static function remove($id)
    {
    	Self::where('id', $id)->delete();
    }

    public static function valueExist($field, $value, $edit = false)
    {
    	return Self::where(array(
    		$field => $value, 
    	))->where('id', '!=' , $edit)
    	->first();
    }

    public static function getAll($type = 'admin')
    {
    	$query = Self::whereNotNull('job_tags.id');
        $query->select(
        	"job_tags.*",
        );
        $query->from(Self::$tbl);
        $query->groupBy('job_tags.id');
        $query->orderBy('job_tags.created_at', 'DESC');
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

}