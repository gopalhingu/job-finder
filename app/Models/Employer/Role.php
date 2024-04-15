<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    protected $table = 'roles';
    protected static $tbl = 'roles';
    protected $primaryKey = 'role_id';

    public static function storeRole($data, $edit = null)
    {
        unset($data['_token'], $data['role_id']);
        $data['employer_id'] = employerId();
        $data['type'] = 'employer';

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('role_id', decode($edit))->update($data);
            $id = $edit;
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $insert = Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            $id = encode($id);
        }
        
        return array('id' => $id, 'title' => $data['title']);
    }

    public static function remove($role_id)
    {
        $role_id = decode($role_id);
        Self::where('role_id', $role_id)->delete();
        DB::table('role_permissions')->where('role_id', $role_id)->delete();        
        DB::table('employer_roles')->where('role_id', $role_id)->delete();        
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'role_id' || $field == 'roles.role_id' ? decode($value) : $value;
        $query = Self::where(array(
            $field => $value, 
            'type' => 'employer',
        ))->where('role_id', '!=' , decode($edit))
        ->where('employer_id', employerId())
        ->where('roles.type', 'employer')
        ->count();
        return $query > 0 ? true : false;
    }

    public static function getAll()
    {
        $query = Self::whereNotNull('roles.role_id');
        $query->select(
            "roles.*",
            DB::Raw('COUNT(DISTINCT('.dbprfx().'role_permissions.permission_id)) as permissions_count')
        );
        $query->where('roles.employer_id', employerId());
        $query->where('roles.type', 'employer');
        $query->leftJoin('role_permissions', 'role_permissions.role_id', '=', 'roles.role_id');
        $query->groupBy('roles.role_id');
        $query->orderBy('roles.created_at', 'DESC');
        $result = $query->get();
        return $result ? $result->toArray() : array();        
    }

    public static function getEmployerRoles($employer_id)
    {
        $query = DB::table('employer_roles')->whereNotNull('employer_roles.role_id');
        $query->select(
            DB::Raw('GROUP_CONCAT('.dbprfx().'employer_roles.role_id) AS role_ids')
        );
        $query->leftJoin('roles', 'roles.role_id', '=', 'employer_roles.role_id');
        $query->where('employer_roles.employer_id', $employer_id);
        $query->groupBy('employer_roles.employer_id');
        $query = $query->first();
        $result = $query ? $query->role_ids : '';
        return $result;
    }

    public static function getPermissions($role_id)
    {
        $role_id = $role_id ? decode($role_id) : 0;
        $query = DB::table('permissions')->whereNotNull('permissions.permission_id');
        $query->select(
            'permissions.*',
            DB::Raw('('.dbprfx().'role_permissions.permission_id IS NOT NULL) AS selected')
        );
        $query->leftJoin('role_permissions', function($join) use ($role_id) {
            $join->on('role_permissions.permission_id', '=', 'permissions.permission_id')
            ->where('role_permissions.role_id', '=', $role_id);
        });
        $query->where('permissions.type', 'employer');
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

    public static function addPermission($data)
    {
        $data = objToArr(json_decode($data));
        $role_id = $data['role_id'];
        $permission_ids = $data['permissions'];
        
        $role_permissions['employer_id'] = employerId();
        foreach ($permission_ids as $p_id) {
            $role_permissions['role_id'] = decode($role_id);
            $role_permissions['permission_id'] = decode($p_id);
            DB::table('role_permissions')->insert($role_permissions);
        }
    }

    public static function removePermission($data)
    {
        $data = objToArr(json_decode($data));
        $role_id = $data['role_id'];
        $permission_ids = $data['permissions'];

        $conditions['employer_id'] = employerId();
        foreach ($permission_ids as $p_id) {
            $conditions['role_id'] = decode($role_id);
            $conditions['permission_id'] = decode($p_id);
            DB::table('role_permissions')->where($conditions)->delete();
        }
    }

    public static function getEmployerPermissions($employer_id)
    {
        $employer_roles = Self::getEmployerRoles($employer_id);
        $role_ids = explode(',', $employer_roles);
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
        $query = DB::table('role_permissions')->whereNotNull('role_permissions.role_id');
        $query->select(
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'role_permissions.permission_id)) AS permissions')
        );
        $query->from('role_permissions');
        $query->whereIn('role_permissions.role_id', $role_ids);
        $query = $query->first();
        return isset($query->permissions) ? $query->permissions : 0;
    }
}