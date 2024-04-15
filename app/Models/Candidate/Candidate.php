<?php

namespace App\Models\Candidate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class Candidate extends Model
{
    protected $table = 'candidates';
    protected static $tbl = 'candidates';
    protected $primaryKey = 'candidate_id';

    public static function getFirst($column, $value)
    {
        $result = Self::where($column, $value)->first();
        return $result ? objToArr($result->toArray()) : emptyTableColumns(Self::$tbl);
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->where($field, $value);
        if ($edit) {
            $query->where('candidate_id', '!=', $edit);
        }
        $query = $query->get();
        return $query->count() > 0 ? true : false;
    }

    public static function login($email, $password)
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->where('email', $email);
        $query->where('status', 1);
        $candidate = $query->first();
        if ($candidate) {
            if (\Hash::check($password, $candidate->password)) {
                return $candidate->toArray();
            }
        }
        return false;
    }

    public static function checkExistingPassword($password)
    {
        $candidate = Self::where('candidate_id', candidateSession())->first();
        return \Hash::check($password, $candidate->password);
    }    

    public static function createTokenForCandidate($email)
    {
        Self::where('email', $email)->update(array('token' => token()));
    }

    public static function createCandidate($data, $verification = false)
    {
        if ($verification) {
            $data['token'] = token();
            $data['status'] = 0;
        } else {
            $data['token'] = '';
            $data['status'] = 1;
        }
        unset($data['retype_password'], $data['_token'], $data['csrf_token']);
        $data['image'] = '';
        $data['password'] = \Hash::make($data['password']);
        $data['account_type'] = 'site';
        $data['external_id'] = '';
        $data['created_at'] = date('Y-m-d G:i:s');
        Self::insert($data);
        $id = DB::getPdo()->lastInsertId();
        return Self::getFirst('candidates.candidate_id', $id);
    }

    public static function updateProfile($data, $image)
    {
        unset($data['_token'], $data['csrf_token'], $data['image']);
        $data['updated_at'] = date('Y-m-d G:i:s');
        if ($image) {
            $data['image'] = $image;
        }
        return Self::where('candidate_id', candidateSession())->update($data);
    }

    public static function activateAccount($token)
    {
        $result = Self::getFirst('candidates.token', $token);
        if ($result['candidate_id']) {
            $update = array('status' => 1, 'token' => '', 'updated_at' => date('Y-m-d G:i:s'));
            Self::where('candidates.token', $token)->update($update);
            return true;
        } else {
            return false;
        }
    }

    public static function updatePasswordByField($field, $value, $password)
    {
        $update = array('password' => $password, 'token' => '');
        Self::where($field, $value)->update($update);
        setSession('candidate.password', $password);
        return true;
    }

    public static function internalCandidate($email, $type)
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->where('candidates.email', $email);
        $query->where('candidates.account_type', '!=', $type);
        $result = $query->first();
        return $result ? true : false;
    }

    public static function existingExternalCandidate($id, $email)
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->where('candidates.email', $email);
        $query->where('candidates.external_id', $id);
        $result = $query->first();
        return $result ? objToArr($result->toArray()) : array();
    }

    public static function createGoogleCandidateIfNotExist($id, $email, $name, $image)
    {
        if (Self::internalCandidate($email, 'google')) {
            return false;
        } elseif (Self::existingExternalCandidate($id, $email)) {
            return Self::existingExternalCandidate($id, $email);
        } else {
            Self::insertCandidateImage($image, $id);
            $name = explode(' ', $name);
            $candidate['first_name'] = $name[0];
            $candidate['last_name'] = $name[1];
            $candidate['slug'] = Self::getSlug($name[0].' '.$name[1], '', '');
            $candidate['email'] = $name[0].$name[1];
            $candidate['email'] = $email;
            $candidate['image'] = config('constants.upload_dirs.candidates').$id.'.jpg';
            $candidate['password'] = \Hash::make($name[0].$name[1].$email);
            $candidate['status'] = 1;
            $candidate['account_type'] = 'google';
            $candidate['external_id'] = $id;
            $candidate['created_at'] = date('Y-m-d G:i:s');
            Self::insert($candidate);
            return Self::existingExternalCandidate($id, $email);
        }
    }

    public static function createLinkedinCandidateIfNotExist($apiData)
    {
        $id = $apiData['id'];
        $email = $apiData['email'];
        $first_name = $apiData['first_name'];
        $last_name = $apiData['last_name'];
        $image = $apiData['image'];
        if (Self::internalCandidate($email, 'linkedin')) {
            return false;
        } elseif (Self::existingExternalCandidate($id, $email)) {
            return Self::existingExternalCandidate($id, $email);
        } else {
            Self::insertCandidateImage($image, $id);
            $candidate['first_name'] = $first_name;
            $candidate['last_name'] = $last_name;
            $candidate['slug'] = Self::getSlug($first_name.' '.$last_name, '', '');
            $candidate['email'] = $email;
            $candidate['image'] = config('constants.upload_dirs.candidates').$id.'.jpg';
            $candidate['password'] = \Hash::make($first_name.$last_name.$email);
            $candidate['status'] = 1;
            $candidate['account_type'] = 'linkedin';
            $candidate['external_id'] = $id;
            $candidate['created_at'] = date('Y-m-d G:i:s');
            Self::insert($candidate);
            return Self::existingExternalCandidate($id, $email);
        }
    }

    private static function insertCandidateImage($image, $id)
    {
        if (!empty($image)) {
            $name = $id.'.jpg';
            $full_path = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.candidates').$name);
            $storage_dir = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.candidates'));
            $content = remoteRequest($image);
            $fp = fopen($full_path, "w");
            fwrite($fp, $content);
            fclose($fp);
        }
    }

    public static function storeRememberMeToken($email, $token)
    {
        Self::where('email', $email)->update(array('token' => $token));
    }

    public static function getCandidateWithRememberMeToken($token)
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->where('candidates.token', $token);
        $result = $query->first();
        return $result ? objToArr($result->toArray()) : array();
    }   

    public static function getSlug($title, $slug, $edit)
    {
        $slug = $slug ? $slug : slugify($title);
        $numbers = range(1, 500);
        $edit = decode($edit);
        array_unshift($numbers , '');
        foreach ($numbers as $number) {
            $completeSlug = $slug.($number ? '-'.$number : '');
            $query = Self::where('slug', $completeSlug);
            if ($edit) {
                $query->where('candidate_id', '!=', $edit);
            }
            $count = $query->get()->count();
            if ($count == 0) {
                return $completeSlug;
            }
        }
    }

}