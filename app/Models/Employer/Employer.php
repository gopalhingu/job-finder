<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Employer  extends Model
{
    protected $table = 'employers';
    protected static $tbl = 'employers';
    protected $primaryKey = 'employer_id';

    protected $fillable = [
        'employer_id',
        'company',
        'slug',
        'first_name',
        'last_name',
        'employername',
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

    public static function login($email, $password)
    {
    	$query = Self::whereNotNull('employers.employer_id');
        $query->where('email', $email);
        $query->where('status', 1);
        $employer = $query->first();
        if ($employer) {
        	if (\Hash::check($password, $employer->password)) {
        		return $employer->toArray();
        	}
        }
        return false;
    }

    public static function checkEmployerByEmail($email)
    {
    	return Self::where('email', $email)->first();
    }

    public static function checkExistingPassword($password)
    {
        $employer = Self::where('employer_id', employerSession())->first();
        return \Hash::check($password, $employer->password);
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

    public static function updateProfile($data, $image, $logo)
    {
        if ($image) {
            $data['image'] = $image;
        }
        if ($logo) {
            $data['logo'] = $logo;
        }
        unset($data['_token'], $data['csrf_token']);
        Self::where('employer_id', employerSession())->update($data);
    }

    public static function getEmployer($column, $value)
    {
        $value = $column == 'employer_id' || $column == 'employers.employer_id' ? decode($value) : $value;
    	$employer = Self::where($column, $value)->first();
    	return $employer ? objToArr($employer) : Self::getTableColumns();
    }

    public static function getEmployerBySlug($slug)
    {
        $query = Self::whereNotNull('employers.employer_id');
        $query->select('employers.*', 'memberships.separate_site');
        $query->where(array('slug' => $slug, 'type' => 'main'));
        $query->leftJoin('memberships', function($join) {
            $join->on('memberships.employer_id', '=', 'employers.employer_id');
            $join->where('memberships.status', '=', '1');
            $join->where('memberships.expiry', '>', \DB::raw('NOW()'));
        });
        $employer = $query->first();
        return $employer ? $employer->toArray() : '';
    }

    public static function checkExistingRole($role_id, $employer_id)
    {
        $result = DB::table('employer_roles')->where(array(
            'role_id' => decode($role_id),
            'employer_id' => decode($employer_id)
        ))->count();
        return ($result > 0) ? true : false;
    }

    public static function storeEmployerRolesBulk($data)
    {
        $roles = $data['roles'];
        $employer_ids = json_decode($data['employer_ids']);
        foreach ($roles as $role_id) {
            foreach ($employer_ids as $employer_id) {
                $role_id = decode($role_id);
                $employer_id = decode($employer_id);
                $existing = Self::checkExistingRole($role_id, $employer_id);
                if (!$existing) {
                    $d['employer_id'] = $employer_id;
                    $d['role_id'] = $role_id;
                    DB::table('employer_roles')->insert($d);
                }
            }
        }
    }

    public static function storeEmployer($data, $edit = null, $image = '')
    {
        $roles = isset($data['roles']) ? $data['roles'] : array();

        unset($data['roles'], $data['employer_id'], $data['_token'], $data['image'], $data['notify_team_member']);

        if ($image) {
            $data['image'] = $image;
        }

        $data['parent_id'] = employerId();
        $data['employername'] = employerId('slug').'-'.strtotime(date('Y-m-d G:i:s'));
        $data['slug'] = employerId('slug').'-'.curRand();
        $data['company'] = employerId('company').' ('.$data['first_name'].' '.$data['first_name'].')';
        $data['type'] = 'team';
        if ($data['password']) {
            $data['password'] = \Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('employer_id', decode($edit))->update($data);
            Self::insertRoles($roles, $edit);
            return $edit;
        } else {
            $data['password'] = \Hash::make($data['password']);
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['status'] = 1;
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            Self::insertRoles($roles, $id);
            return $id;
        }
    }

    private static function insertRoles($data, $id)
    {
        $id = decode($id);
        DB::table('employer_roles')->where(array('employer_id' => $id))->delete();
        foreach ($data as $d) {
            $d = decode($d);
            DB::table('employer_roles')->insert(array('employer_id' => $id, 'role_id' => $d));
        }
    }

    public static function changeStatus($employer_id, $status)
    {
        Self::where('employer_id', decode($employer_id))->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($employer_id)
    {
        $condition = array('employer_id' => decode($employer_id));
        Self::where($condition)->delete();
        DB::table('employer_roles')->where($condition)->delete();
    }

    public static function getAll($active = true, $srh = '')
    {
        $query = Self::whereNotNull('employers.employer_id');
        if ($active) {
            $query->where('status', 1);
        }
        if ($srh) {
            $query->where('company', 'like', '%'.$srh.'%');
        }
        $query->where('parent_id', employerId());        
        $query->where('type', 'team');
        return $query->get();
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'employer_id' || $field == 'employers.employer_id' ? decode($value) : $value;
        $query = Self::where($field, $value);
        if ($edit) {
            $query->where('employer_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;
    }

    public static function employersList($request)
    {
        $columns = array(
            "",
            "",
            "employers.first_name",
            "employers.last_name",
            "employers.email",
            "",
            "employers.created_at",
            "employers.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('employers.employer_id');
        $query->select(
            'employers.*',
            DB::Raw('GROUP_CONCAT('.dbprfx().'roles.title SEPARATOR ", ") as employer_roles'),
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('company', 'like', '%'.$srh.'%');
                $q->orWhere('first_name', 'like', '%'.$srh.'%');
                $q->orWhere('last_name', 'like', '%'.$srh.'%');
                $q->orWhere('email', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('employers.status', $request['status']);
        }
        if (isset($request['role']) && $request['role'] != '') {
            $query->where('employer_roles.role_id', decode($request['role']));
        }
        $query->leftJoin('employer_roles','employer_roles.employer_id', '=', 'employers.employer_id');
        $query->leftJoin('roles', function($join) {
            $join->on('roles.role_id', '=', 'employer_roles.role_id')->where('roles.type', '=', 'employer');
        });
        $query->where('employers.type', 'team');
        $query->where('employers.parent_id', employerId());
        $query->groupBy('employers.employer_id');
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
        $query = Self::whereNotNull('employers.employer_id');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('company', 'like', '%'.$srh.'%');
                $q->orWhere('first_name', 'like', '%'.$srh.'%');
                $q->orWhere('last_name', 'like', '%'.$srh.'%');
                $q->orWhere('email', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('employers.status', $request['status']);
        }
        if (isset($request['role']) && $request['role'] != '') {
            $query->where('employer_roles.role_id', decode($request['role']));
        }
        $query->leftJoin('employer_roles','employer_roles.employer_id', '=', 'employers.employer_id');
        $query->leftJoin('roles', function($join) {
            $join->on('roles.role_id', '=', 'employer_roles.role_id')->where('roles.type', '=', 'employer');
        });
        $query->where('employers.type', 'team');
        $query->where('employers.parent_id', employerId());
        $query->groupBy('employers.employer_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($employers)
    {
        $sorted = array();
        foreach ($employers as $u) {
            $actions = '';
            $u = objToArr($u);
            $id = encode($u['employer_id']);
            if ($u['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (empAllowedTo('edit_team_member')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-team" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (empAllowedTo('delete_team_member')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-team" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $thumb = employerThumb($u['image']);
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                "<img class='employer-thumb-table' src='".$thumb['image']."' onerror='this.src=\"".$thumb['error']."\"'/>",
                esc_output($u['first_name']),
                esc_output($u['last_name']),
                esc_output($u['email']),
                ($u['employer_roles'] && empMembership(employerId(), 'role_permissions') == 1) ? esc_output($u['employer_roles']) : '---',
                date('d M, Y', strtotime($u['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-team-status" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }

    public static function getTotalTeam($id = '')
    {
        $query = Self::whereNotNull('employers.employer_id');
        $query->where('status', 1);
        $query->where('type', 'team');
        $query->where('parent_id', employerId());
        if ($id) {
            $query->where('employers.employer_id', '!=', decode($id));
        }
        return $query->get()->count();
    }    

    public static function storeRememberMeToken($email, $token)
    {
    	Self::where('email', $email)->update(array('token' => $token));
    }

    public static function getEmployerWithRememberMeToken($token)
    {
    	$query = Self::whereNotNull('employers.employer_id');
        $query->where('employers.token', $token);
        $query->select('employers.*');
        $result = $query->first();
        return $result ? $result->toArray() : array();
    }

    public static function storeAdminEmployer($data)
    {
        $data['password'] = \Hash::make($data['password']);
        $data['created_at'] = date('Y-m-d G:i:s');
        $data['type'] = 'main';
        $data['status'] = 1;
        unset($data['retype_password']);
        return Self::insert($data);
    }

    private static function getTableColumns()
    {
        $model = new Self;
        $table = $model->getTable();
        $columns = \DB::getSchemaBuilder()->getColumnListing($table);
        $columns = array_flip($columns);
        $columns2 = array();
        foreach ($columns as $key => $value) {
            $columns2[$key] = '';
        }
        return $columns2;
    }

    public static function activateAccount($token)
    {
        $result = Self::where('employers.token', $token)->first();
        if ($result) {
            Self::where('employers.token', $token)->update(array('token' => '', 'status' => 1, 'updated_at' => date('Y-m-d G:i:s')));
            return $result->employer_id;
        }
        return false;
    }

    public static function internalEmployer($email, $type)
    {
        $query = Self::whereNotNull('employers.employer_id');
        $query->where('employers.email', $email);
        $query->where('employers.account_type', '!=', $type);
        $result = $query->first();
        return $result ? true : false;
    }

    public static function existingExternalEmployer($id, $email)
    {
        $query = Self::whereNotNull('employers.employer_id');
        $query->where('employers.email', $email);
        $query->where('employers.external_id', $id);
        $result = $query->first();
        return $result ? objToArr($result->toArray()) : array();
    }

    public static function createGoogleEmployerIfNotExist($id, $email, $name, $image)
    {
        if (Self::internalEmployer($email, 'google')) {
            return false;
        } elseif (Self::existingExternalEmployer($id, $email)) {
            return Self::existingExternalEmployer($id, $email);
        } else {
            Self::insertEmployerImage($image, $id);
            $name = explode(' ', $name);
            $employer['first_name'] = $name[0];
            $employer['last_name'] = $name[1];
            $employer['email'] = $name[0].$name[1];
            $employer['email'] = $email;
            $employer['image'] = config('constants.upload_dirs.employers').$id.'.jpg';
            $employer['password'] = \Hash::make($name[0].$name[1].$email);
            $employer['employername'] = strtolower($name[0].$name[1].$email);
            $employer['company'] = $name[0].$name[1];
            $employer['slug'] = strtolower($name[0].'-'.$name[1]);            
            $employer['status'] = 1;
            $employer['account_type'] = 'google';
            $employer['external_id'] = $id;
            $employer['created_at'] = date('Y-m-d G:i:s');
            Self::insert($employer);
            return Self::existingExternalEmployer($id, $email);
        }
    }

    public static function createLinkedinEmployerIfNotExist($apiData)
    {
        $id = $apiData['id'];
        $email = $apiData['email'];
        $first_name = $apiData['first_name'];
        $last_name = $apiData['last_name'];
        $image = $apiData['image'];
        if (Self::internalEmployer($email, 'linkedin')) {
            return false;
        } elseif (Self::existingExternalEmployer($id, $email)) {
            return Self::existingExternalEmployer($id, $email);
        } else {
            Self::insertEmployerImage($image, $id);
            $employer['first_name'] = $first_name;
            $employer['last_name'] = $last_name;
            $employer['email'] = $email;
            $employer['image'] = config('constants.upload_dirs.employers').$id.'.jpg';
            $employer['password'] = \Hash::make($first_name.$last_name.$email);
            $employer['employername'] = strtolower($first_name.$last_name.$email);
            $employer['company'] = $first_name.$last_name;
            $employer['slug'] = strtolower($first_name.'-'.$last_name);
            $employer['status'] = 1;
            $employer['account_type'] = 'linkedin';
            $employer['external_id'] = $id;
            $employer['created_at'] = date('Y-m-d G:i:s');
            Self::insert($employer);
            return Self::existingExternalEmployer($id, $email);
        }
    }

    private static function insertEmployerImage($image, $id)
    {
        if (!empty($image)) {
            $name = $id.'.jpg';
            $full_path = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.employers').$name);
            $storage_dir = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.employers'));
            $content = remoteRequest($image);
            $fp = fopen($full_path, "w");
            fwrite($fp, $content);
            fclose($fp);
        }
    }    
}