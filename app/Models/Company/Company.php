<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Company  extends Model
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

    public static function login($email, $password)
    {
    	$query = Self::whereNotNull('company.company_id');
        $query->where('email', $email);
        $query->where('status', 1);
        $company = $query->first();
        if ($company) {
        	if (\Hash::check($password, $company->password)) {
        		return $company->toArray();
        	}
        }
        return false;
    }

    public static function checkCompanyByEmail($email)
    {
    	return Self::where('email', $email)->first();
    }

    public static function checkExistingPassword($password)
    {
        $company = Self::where('company_id', companySession())->first();
        return \Hash::check($password, $company->password);
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
        Self::where('company_id', companySession())->update($data);
    }

    public static function getCompany($column, $value)
    {
        $value = $column == 'company_id' || $column == 'company.company_id' ? decode($value) : $value;
    	$company = Self::where($column, $value)->first();
    	return $company ? objToArr($company) : Self::getTableColumns();
    }

    public static function getCompanyBySlug($slug)
    {
        $query = Self::whereNotNull('company.company_id');
        $query->select('company.*', 'memberships.separate_site');
        $query->where(array('slug' => $slug, 'type' => 'main'));
        $query->leftJoin('memberships', function($join) {
            $join->on('memberships.company_id', '=', 'company.company_id');
            $join->where('memberships.status', '=', '1');
            $join->where('memberships.expiry', '>', \DB::raw('NOW()'));
        });
        $company = $query->first();
        return $company ? $company->toArray() : '';
    }

    public static function checkExistingRole($role_id, $company_id)
    {
        $result = DB::table('employer_roles')->where(array(
            'role_id' => decode($role_id),
            'company_id' => decode($company_id)
        ))->count();
        return ($result > 0) ? true : false;
    }

    public static function storeCompanyRolesBulk($data)
    {
        $roles = $data['roles'];
        $company_ids = json_decode($data['company_ids']);
        foreach ($roles as $role_id) {
            foreach ($company_ids as $company_id) {
                $role_id = decode($role_id);
                $company_id = decode($company_id);
                $existing = Self::checkExistingRole($role_id, $company_id);
                if (!$existing) {
                    $d['company_id'] = $company_id;
                    $d['role_id'] = $role_id;
                    DB::table('employer_roles')->insert($d);
                }
            }
        }
    }

    public static function storeCompany($data, $edit = null, $image = '')
    {
        $roles = isset($data['roles']) ? $data['roles'] : array();

        unset($data['roles'], $data['company_id'], $data['_token'], $data['image'], $data['notify_team_member']);

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
            Self::where('company_id', decode($edit))->update($data);
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
        DB::table('employer_roles')->where(array('company_id' => $id))->delete();
        foreach ($data as $d) {
            $d = decode($d);
            DB::table('employer_roles')->insert(array('company_id' => $id, 'role_id' => $d));
        }
    }

    public static function changeStatus($company_id, $status)
    {
        Self::where('company_id', decode($company_id))->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($company_id)
    {
        $condition = array('company_id' => decode($company_id));
        Self::where($condition)->delete();
        DB::table('employer_roles')->where($condition)->delete();
    }

    public static function getAll($active = true, $srh = '')
    {
        $query = Self::whereNotNull('company.company_id');
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
        $value = $field == 'company_id' || $field == 'company.company_id' ? decode($value) : $value;
        $query = Self::where($field, $value);
        if ($edit) {
            $query->where('company_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;
    }

    public static function companysList($request)
    {
        $columns = array(
            "",
            "",
            "company.first_name",
            "company.last_name",
            "company.email",
            "",
            "company.created_at",
            "company.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('company.company_id');
        $query->select(
            'company.*',
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
            $query->where('company.status', $request['status']);
        }
        if (isset($request['role']) && $request['role'] != '') {
            $query->where('employer_roles.role_id', decode($request['role']));
        }
        $query->leftJoin('employer_roles','employer_roles.company_id', '=', 'company.company_id');
        $query->leftJoin('roles', function($join) {
            $join->on('roles.role_id', '=', 'employer_roles.role_id')->where('roles.type', '=', 'employer');
        });
        $query->where('company.type', 'team');
        $query->where('company.parent_id', employerId());
        $query->groupBy('company.company_id');
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
        $query = Self::whereNotNull('company.company_id');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('company', 'like', '%'.$srh.'%');
                $q->orWhere('first_name', 'like', '%'.$srh.'%');
                $q->orWhere('last_name', 'like', '%'.$srh.'%');
                $q->orWhere('email', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('company.status', $request['status']);
        }
        if (isset($request['role']) && $request['role'] != '') {
            $query->where('employer_roles.role_id', decode($request['role']));
        }
        $query->leftJoin('employer_roles','employer_roles.company_id', '=', 'company.company_id');
        $query->leftJoin('roles', function($join) {
            $join->on('roles.role_id', '=', 'employer_roles.role_id')->where('roles.type', '=', 'employer');
        });
        $query->where('company.type', 'team');
        $query->where('company.parent_id', employerId());
        $query->groupBy('company.company_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($companys)
    {
        $sorted = array();
        foreach ($companys as $u) {
            $actions = '';
            $u = objToArr($u);
            $id = encode($u['company_id']);
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
        $query = Self::whereNotNull('company.company_id');
        $query->where('status', 1);
        $query->where('type', 'team');
        $query->where('parent_id', employerId());
        if ($id) {
            $query->where('company.company_id', '!=', decode($id));
        }
        return $query->get()->count();
    }    

    public static function storeRememberMeToken($email, $token)
    {
    	Self::where('email', $email)->update(array('token' => $token));
    }

    public static function getCompanyWithRememberMeToken($token)
    {
    	$query = Self::whereNotNull('company.company_id');
        $query->where('company.token', $token);
        $query->select('company.*');
        $result = $query->first();
        return $result ? $result->toArray() : array();
    }

    public static function storeAdminCompany($data)
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
        $result = Self::where('company.token', $token)->first();
        if ($result) {
            Self::where('company.token', $token)->update(array('token' => '', 'status' => 1, 'updated_at' => date('Y-m-d G:i:s')));
            return $result->company_id;
        }
        return false;
    }

    public static function internalCompany($email, $type)
    {
        $query = Self::whereNotNull('company.company_id');
        $query->where('company.email', $email);
        $query->where('company.account_type', '!=', $type);
        $result = $query->first();
        return $result ? true : false;
    }

    public static function existingExternalCompany($id, $email)
    {
        $query = Self::whereNotNull('company.company_id');
        $query->where('company.email', $email);
        $query->where('company.external_id', $id);
        $result = $query->first();
        return $result ? objToArr($result->toArray()) : array();
    }

    public static function createGoogleCompanyIfNotExist($id, $email, $name, $image)
    {
        if (Self::internalCompany($email, 'google')) {
            return false;
        } elseif (Self::existingExternalCompany($id, $email)) {
            return Self::existingExternalCompany($id, $email);
        } else {
            Self::insertCompanyImage($image, $id);
            $name = explode(' ', $name);
            $company['first_name'] = $name[0];
            $company['last_name'] = $name[1];
            $company['email'] = $name[0].$name[1];
            $company['email'] = $email;
            $company['image'] = config('constants.upload_dirs.companys').$id.'.jpg';
            $company['password'] = \Hash::make($name[0].$name[1].$email);
            $company['companyname'] = strtolower($name[0].$name[1].$email);
            $company['company'] = $name[0].$name[1];
            $company['slug'] = strtolower($name[0].'-'.$name[1]);            
            $company['status'] = 1;
            $company['account_type'] = 'google';
            $company['external_id'] = $id;
            $company['created_at'] = date('Y-m-d G:i:s');
            Self::insert($company);
            return Self::existingExternalCompany($id, $email);
        }
    }

    public static function createLinkedinCompanyIfNotExist($apiData)
    {
        $id = $apiData['id'];
        $email = $apiData['email'];
        $first_name = $apiData['first_name'];
        $last_name = $apiData['last_name'];
        $image = $apiData['image'];
        if (Self::internalCompany($email, 'linkedin')) {
            return false;
        } elseif (Self::existingExternalCompany($id, $email)) {
            return Self::existingExternalCompany($id, $email);
        } else {
            Self::insertCompanyImage($image, $id);
            $company['first_name'] = $first_name;
            $company['last_name'] = $last_name;
            $company['email'] = $email;
            $company['image'] = config('constants.upload_dirs.companys').$id.'.jpg';
            $company['password'] = \Hash::make($first_name.$last_name.$email);
            $company['companyname'] = strtolower($first_name.$last_name.$email);
            $company['company'] = $first_name.$last_name;
            $company['slug'] = strtolower($first_name.'-'.$last_name);
            $company['status'] = 1;
            $company['account_type'] = 'linkedin';
            $company['external_id'] = $id;
            $company['created_at'] = date('Y-m-d G:i:s');
            Self::insert($company);
            return Self::existingExternalCompany($id, $email);
        }
    }

    private static function insertCompanyImage($image, $id)
    {
        if (!empty($image)) {
            $name = $id.'.jpg';
            $full_path = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.companys').$name);
            $storage_dir = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.companys'));
            $content = remoteRequest($image);
            $fp = fopen($full_path, "w");
            fwrite($fp, $content);
            fclose($fp);
        }
    }    
}