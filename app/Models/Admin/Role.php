<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    protected $table = 'roles';
    protected static $tbl = 'roles';
    protected $primaryKey = 'role_id';

    public static function getRole($column, $value)
    {
    	$role = Self::where($column, $value)->first();
    	return $role ? $role->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function storeRole($data, $edit = null)
    {
    	unset($data['_token'], $data['role_id'], $data['csrf_token']);

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
        	Self::where('role_id', $edit)->update($data);
        	$id = $edit;
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['employer_id'] = 0;
            $insert = Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
        }
        
        return array('id' => $id, 'title' => $data['title']);
    }

    public static function remove($role_id)
    {
    	Self::where('role_id', $role_id)->delete();
    	DB::table('role_permissions')->where('role_id', $role_id)->delete();
    }

    public static function valueExist($field, $value, $edit = false)
    {
    	return Self::where(array(
    		$field => $value, 
    		'type' => 'admin',
    	))->where('role_id', '!=' , $edit)
    	->first();
    }

    public static function getAll($type = 'admin')
    {
    	$query = Self::whereNotNull('roles.role_id');
        $query->select(
        	"roles.*",
            DB::Raw('COUNT(DISTINCT('.dbprfx().'role_permissions.permission_id)) as permissions_count')
        );
        $query->from(Self::$tbl);
        $query->leftJoin('role_permissions', 'role_permissions.role_id', '=', 'roles.role_id');
        if ($type != 'all') {
        $query->where('roles.type', $type);
        }
        $query->groupBy('roles.role_id');
        $query->orderBy('roles.created_at', 'DESC');
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function getUserRoles($user_id)
    {
        $query = DB::table('user_roles')->whereNotNull('user_roles.role_id');
        $query->select(
            DB::Raw('GROUP_CONCAT('.dbprfx().'user_roles.role_id) AS role_ids')
        );
        $query->where('user_roles.user_id', $user_id);
        $query->groupBy('user_roles.user_id');
        $result = $query->first();
        return isset($result->role_ids) ? $result->role_ids : '';
    }

    public static function getEmployerRoles($employer_id)
    {
        $query = DB::table('employer_roles')->whereNotNull('employer_roles.role_id');
        $query->select(
            DB::Raw('GROUP_CONCAT('.dbprfx().'employer_roles.role_id) AS role_ids')
        );
        $query->where('employer_roles.employer_id', $employer_id);
        $query->groupBy('employer_roles.employer_id');
        $result = $query->first();
        return isset($result->role_ids) ? $result->role_ids : '';
    }

    public static function getPermissions($role_id)
    {
        $role_id = $role_id ? $role_id : 0;
        $role = Self::getRole('role_id', $role_id);
        $query = Self::whereNotNull('permissions.permission_id');
        $query->select(
            "permissions.*",
        	DB::Raw('('.dbprfx().'role_permissions.permission_id IS NOT NULL) AS selected')
        );
        $query->from('permissions');
		$query->leftJoin('role_permissions', function($join) use ($role_id) {
			$join->on('role_permissions.permission_id', '=', 'permissions.permission_id')
			->where('role_permissions.role_id', '=', $role_id);
		});
        $query->where('permissions.type', $role['type']);
        $query->groupBy('permissions.permission_id');
        $permissions = $query->get();
        $sorted = array();
        foreach ($permissions as $key => $value) {
            $sorted[$value->category][] = array(
                'id' => $value->permission_id, 
                'title' => $value->title,
                'selected' => $value->selected
            );
        }
        return $sorted;
    }

    public static function updatePermissions($data)
    {
    	$data = json_decode($data);
    	$perms = json_decode($data->ids);
    	$role_id = $data->role_id;
        foreach ($perms as $p_id) {
        	$new = array('role_id' => $role_id, 'permission_id' => $p_id);
        	$existing = DB::table('role_permissions')->where($new)->first();
        	if (!$existing) {
        		DB::table('role_permissions')->insert($new);
        	}
        }
        DB::table('role_permissions')->where('role_id', $role_id)->whereNotIn('permission_id', $perms)->delete();
    }

    public static function getUserPermissions($user_id)
    {
        $roles = Self::getUserRoles($user_id);
        $role_ids = explode(',', $roles);
        $permission_ids = Self::getPermissionIds($role_ids);
        $query = DB::table('permissions')->whereNotNull('permissions.permission_id');
        $query->select('permissions.slug');
        $query->whereIn('permissions.permission_id', explode(',', $permission_ids));
        $query->groupBy('permissions.permission_id');
        $result = $query->get();
        $permissions = array();
        foreach ($result as $value) {
            $permissions[] = $value->slug;
        }
        return $permissions;
    }

    public static function getPermissionIds($role_ids)
    {
    	$query = Self::whereNotNull('role_permissions.role_id');
        $query->select(
        	DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'role_permissions.permission_id)) AS permissions'),
        );
        $query->from('role_permissions');
        $query->whereIn('role_permissions.role_id', $role_ids);
        $result = $query->first();
        return isset($result->permissions) ? $result->permissions : 0;
    }

}