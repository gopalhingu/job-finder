<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    protected $table = 'users';
    protected static $tbl = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'username',
        'email',
        'image',
        'phone',
        'password',
        'status',
        'token',
        'user_type',
        'created_at',
        'updated_at',
    ];    

    public static function login($email, $password)
    {
    	$query = Self::whereNotNull('users.user_id');
        $query->where('email', $email);
        $user = $query->first();
        if ($user) {
        	if (\Hash::check($password, $user->password)) {
        		return $user->toArray();
        	}
        }
        return false;
    }

    public static function checkUserByEmail($email)
    {
    	return Self::where('email', $email)->first();
    }

    public static function checkExistingPassword($password)
    {
        $user = Self::where('user_id', adminSession())->first();
        return \Hash::check($password, $user->password);
    }

    public static function saveTokenForPasswordReset($email)
    {
        $token = base64_encode(date('Y-m-d G:i:s')) . appId();
        Self::where('email', $email)->update(array('token' => $token));
        return $token;
    }

    public static function checkIfTokenExist($token)
    {
    	return Self::where('token', $token)->first();
    }

    public static function updatePasswordByField($field, $value, $password)
    {
    	Self::where($field, $value)->update(array('password' => \Hash::make($password), 'token' => ''));
        return true;
    }

    public static function updateProfile($data, $image)
    {
        if ($image) {
            $data['image'] = $image;
        }
        unset($data['_token'], $data['csrf_token']);
        Self::where('user_id', adminSession())->update($data);
    }

    public static function getUser($column, $value)
    {
    	$user = Self::where($column, $value)->first();
    	return $user ? $user : emptyTableColumns(Self::$tbl);
    }

    public static function checkExistingRole($role_id, $user_id)
    {
        $result = DB::table('user_roles')->where(array(
            'role_id' => $role_id,
            'user_id' => $user_id
        ))->count();
        return ($result > 0) ? true : false;
    }

    public static function storeUserRolesBulk($data)
    {
        $roles = $data['roles'];
        $user_ids = json_decode($data['user_ids']);
        foreach ($roles as $role_id) {
            foreach ($user_ids as $user_id) {
                $existing = Self::checkExistingRole($role_id, $user_id);
                if (!$existing) {
                    $d['user_id'] = $user_id;
                    $d['role_id'] = $role_id;
                    DB::table('user_roles')->insert($d);
                }
            }
        }
    }

    public static function storeUser($data, $edit = null, $image = '')
    {
        $roles = isset($data['roles']) ? $data['roles'] : array();
        unset($data['roles'], $data['user_id'], $data['_token'], $data['image']);
        if ($image) {
            $data['image'] = $image;
        }
        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            $data['updated_at'] = date('Y-m-d G:i:s');
            if ($data['password']) {
                $data['password'] = \Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            Self::where('user_id', $edit)->update($data);
            Self::insertRoles($roles, $edit);
        } else {
            $data['password'] = \Hash::make($data['password']);
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['user_type'] = 'user';
            $data['status'] = 1;
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            Self::insertRoles($roles, $id);
            return $id;
        }
    }

    private static function insertRoles($data, $id)
    {
        DB::table('user_roles')->where(array('user_id' => $id))->delete();
        foreach ($data as $d) {
            DB::table('user_roles')->insert(array('user_id' => $id, 'role_id' => $d));
        }
    }

    public static function changeStatus($user_id, $status)
    {
        Self::where('user_id', $user_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($user_id)
    {
        Self::where(array('user_id' => $user_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('user_id', $ids)->update(array('status' => '1'));
            break;
            case "deactivate":
                Self::whereIn('user_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function getAll($active = true, $srh = '')
    {
        $query = Self::whereNotNull('users.user_id');
        if ($active) {
            $query->where('status', 1);
        }
        if ($srh) {
            $query->where('username', 'like', '%'.$srh.'%');
        }
        $query->where('user_type', '!=', 'admin');
        $query->from(Self::$tbl);
        return $query->get();
    }

    public static function usersList($request)
    {
        $columns = array(
            "",
            "",
            "users.first_name",
            "users.last_name",
            "users.email",
            "users.username",
            "",
            "users.created_at",
            "users.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('users.user_id');
        $query->from('users');
        $query->where('user_type', '!=', 'admin');
        $query->select(
            'users.*',
            DB::Raw('GROUP_CONCAT('.dbprfx().'roles.title SEPARATOR ", ") as user_roles'),
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('username', 'like', '%'.$srh.'%');
                $q->orWhere('first_name', 'like', '%'.$srh.'%');
                $q->orWhere('last_name', 'like', '%'.$srh.'%');
                $q->orWhere('email', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('users.status', $request['status']);
        }
        if (isset($request['role']) && $request['role'] != '') {
            $query->where('user_roles.role_id', $request['role']);
        }
        $query->leftJoin('user_roles','user_roles.user_id', '=', 'users.user_id');
        $query->leftJoin('roles', function($join) {
            $join->on('roles.role_id', '=', 'user_roles.role_id')->where('roles.type', '=', 'admin');
        });
        $query->groupBy('users.user_id');
        $query->orderBy($orderColumn, $orderDirection);
        $query->skip($offset);
        $query->take($limit);
        $result = $query->get();
        $result = $result ? $result->toArray() : array();
        $result = array(
            'data' => Self::prepareDataForTable($result),
            'recordsTotal' => Self::getTotal(),
            'recordsFiltered' => Self::getTotal($srh, $request),
        );

        return $result;
    }

    public static function getTotal($srh = false, $request = '')
    {
        $query = Self::whereNotNull('users.user_id');
        $query->from('users');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('username', 'like', '%'.$srh.'%');
                $q->orWhere('first_name', 'like', '%'.$srh.'%');
                $q->orWhere('last_name', 'like', '%'.$srh.'%');
                $q->orWhere('email', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('users.status', $request['status']);
        }
        if (isset($request['role']) && $request['role'] != '') {
            $query->where('user_roles.role_id', $request['role']);
        }
        $query->leftJoin('user_roles','user_roles.user_id', '=', 'users.user_id');
        $query->leftJoin('roles', function($join) {
            $join->on('roles.role_id', '=', 'user_roles.role_id')->where('roles.type', '=', 'admin');
        });
        $query->where('user_type', '!=', 'admin');
        $query->groupBy('users.user_id');
        return $query->get()->count();
    }

    public static function getRangeTotal($from = '', $to = '')
    {
        $query = Self::whereNotNull('users.user_id');
        $query->where('created_at', '>=', $from);
        $query->where('created_at', '<=', $to);
        $query->where('user_type', '<>', 'admin');
        return $query->num_rows();
    }

    private static function prepareDataForTable($users)
    {
        $sorted = array();
        foreach ($users as $u) {
            $actions = '';
            $u = objToArr($u);
            if ($u['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (allowedTo('edit_user')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-user" data-id="'.$u['user_id'].'"><i class="far fa-edit"></i></button>
            ';
            }
            if (allowedTo('delete_user')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-user" data-id="'.$u['user_id'].'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $thumb = userThumb($u['image']);
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$u['user_id']."' />",
                "<img class='user-thumb-table' src='".$thumb['image']."' onerror='this.src=\"".$thumb['error']."\"'/>",
                esc_output($u['first_name'], 'html'),
                esc_output($u['last_name'], 'html'),
                esc_output($u['email'], 'html'),
                esc_output($u['username'], 'html'),
                esc_output($u['user_roles'], 'html'),
                date('d M, Y', strtotime($u['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-user-status" data-status="'.$u['status'].'" data-id="'.$u['user_id'].'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }

    public static function storeRememberMeToken($email, $token)
    {
    	Self::where('email', $email)->update(array('token' => $token));
    }

    public static function getUserWithRememberMeToken($token)
    {
    	$query = self::whereNotNull('users.user_id');
        $query->where('users.token', $token);
        $query->select('users.*');
        $query->from('users');
        $result = $query->first();
        return $result ? $result->toArray() : array();
    }

    public static function storeAdminUser($data)
    {
        $data['password'] = \Hash::make($data['password']);
        $data['created_at'] = date('Y-m-d G:i:s');
        $data['user_type'] = 'admin';
        $data['status'] = 1;
        unset($data['retype_password']);
        return Self::insert($data);
    }

    public static function getUsersForCSV($ids)
    {
        $query = Self::whereNotNull('users.user_id');
        $query->from('users');
        $query->select(
            'users.*'
        );
        $query->whereIn('users.user_id', explode(',', $ids));
        $query->groupBy('users.user_id');
        $query->orderBy('users.created_at', 'DESC');
        return $query->get();
    }
}