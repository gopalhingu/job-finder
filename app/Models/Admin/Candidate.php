<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Candidate extends Model
{
    protected $table = 'candidates';
    protected static $tbl = 'candidates';
    protected $primaryKey = 'candidate_id';

    protected $fillable = [
        'candidate_id',
        'first_name',
        'last_name',
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

    public static function getCandidate($column, $value)
    {
    	$candidate = Self::where($column, $value)->first();
    	return $candidate ? $candidate : Self::getTableColumns();
    }

    public static function storeCandidate($data, $edit = null, $image = '')
    {
        unset($data['candidate_id'], $data['_token'], $data['image']);
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
            Self::where('candidate_id', $edit)->update($data);
        } else {
            $data['password'] = \Hash::make($data['password']);
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['account_type'] = 'site';
            $data['status'] = 1;
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            return $id;
        }
    }

    public static function changeStatus($candidate_id, $status)
    {
        Self::where('candidate_id', $candidate_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($candidate_id)
    {
        Self::where(array('candidate_id' => $candidate_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('candidate_id', $ids)->update(array('status' => '1'));
            break;
            case "deactivate":
                Self::whereIn('candidate_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function getAll($active = true, $srh = '')
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        if ($active) {
            $query->where('status', 1);
        }
        if ($srh) {
            $query->where('company', 'like', '%'.$srh.'%');
        }
        $query->where('account_type', '!=', 'admin');
        $query->from(Self::$tbl);
        return $query->get();
    }

    public static function candidatesList($request)
    {
        $columns = array(
            "",
            "",
            "candidates.first_name",
            "candidates.last_name",
            "candidates.email",
            "",
            "resumes.experience",
            "candidates.account_type",
            "candidates.created_at",
            "candidates.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('candidates.candidate_id');
        $query->from('candidates');
        $query->select(
            'candidates.*',
            'resumes.experience',
            'resumes.file as static_resume',
            DB::Raw('GROUP_CONCAT('.dbprfx().'resume_experiences.title) as job_title')
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->orWhere('first_name', 'like', '%'.$srh.'%');
                $q->orWhere('last_name', 'like', '%'.$srh.'%');
                $q->orWhere('email', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('candidates.status', $request['status']);
        }
        if (isset($request['account_type']) && $request['account_type'] != '') {
            $query->where('candidates.account_type', $request['account_type']);
        }
        if (isset($request['job_title']) && $request['job_title'] != '') {
            $query->like('resume_experiences.title', $request['job_title']);
        }
        if (isset($request['experience']) && $request['experience'] != '') {
            $query->where('resumes.experience >=', $request['experience']);
        }
        $query->leftJoin('resumes', function($join) {
            $join->on('resumes.candidate_id', '=', 'candidates.candidate_id')->where('resumes.is_default', '=', '1');
        });
        $query->leftJoin('resume_experiences', 'resume_experiences.resume_id', '=', 'resumes.resume_id');
        $query->groupBy('candidates.candidate_id');
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
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->from('candidates');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->orWhere('first_name', 'like', '%'.$srh.'%');
                $q->orWhere('last_name', 'like', '%'.$srh.'%');
                $q->orWhere('email', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('candidates.status', $request['status']);
        }
        if (isset($request['account_type']) && $request['account_type'] != '') {
            $query->where('candidates.account_type', $request['account_type']);
        }
        if (isset($request['job_title']) && $request['job_title'] != '') {
            $query->like('resume_experiences.title', $request['job_title']);
        }
        if (isset($request['experience']) && $request['experience'] != '') {
            $query->where('resumes.experience >=', $request['experience']);
        }
        $query->leftJoin('resumes', function($join) {
            $join->on('resumes.candidate_id', '=', 'candidates.candidate_id')->where('resumes.is_default', '=', '1');
        });
        $query->leftJoin('resume_experiences', 'resume_experiences.resume_id', '=', 'resumes.resume_id');
        $query->groupBy('candidates.candidate_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($candidates)
    {
        $sorted = array();
        foreach ($candidates as $u) {
            $u = objToArr($u);
            $id = $u['candidate_id'];
            $actions = '';
            if ($u['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (allowedTo('edit_candidate')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-candidate" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (allowedTo('delete_candidate')) {
                $actions .= '
                    <button type="button" class="btn btn-danger btn-xs delete-candidate" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
                ';
            }
            if (allowedTo('login_as_candidate')) {
            $actions .= '
                <a target="_blank" href="'.url(route('admin-candidates-loginas', array('candidate_id' => encode($id), 'user_id' =>  encode(adminSession())))).'" title="'.__('message.login_as_candidate').'" class="btn btn-warning btn-xs"><i class="fas fa-external-link-alt"></i></button>
            ';
            }
            $thumb = candidateThumb($u['image']);
            $resume = $u['static_resume'] ? '<br /><a target="_blank" href="'.resumeThumb($u['static_resume']).'">'.__('message.file').'</a>' : '';
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                "<img class='candidate-thumb-table' src='".$thumb['image']."' onerror='this.src=\"".$thumb['error']."\"'/>",
                $u['first_name'].$resume,
                $u['last_name'],
                $u['email'],
                $u['job_title'] ? $u['job_title'] : '---',
                $u['experience'],
                $u['account_type'],
                date('d M, Y', strtotime($u['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-candidate-status" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }

    public static function getCompleteResume($id = '')
    {
        $query = DB::table('resumes')->whereNotNull('resumes.resume_id');
        $query->select('resumes.*', 'candidates.*');
        $query->from('resumes');
        $query->where('resumes.candidate_id', $id);
        $query->where('resumes.status', 1);
        $query->leftJoin('candidates', 'candidates.candidate_id', '=', 'resumes.candidate_id');

        $result = $query->first();
        $result = isset($result) ? objToArr($result) : array();
        if ($result) {
            $resume_id = isset($result['resume_id']) ? $result['resume_id'] : '';
            $result['experiences'] = Self::getResumeEntities('resume_experiences', $resume_id);
            $result['qualifications'] = Self::getResumeEntities('resume_qualifications', $resume_id);
            $result['languages'] = Self::getResumeEntities('resume_languages', $resume_id);
            $result['achievements'] = Self::getResumeEntities('resume_achievements', $resume_id);
            $result['references'] = Self::getResumeEntities('resume_references', $resume_id);
        }
        return $result;
    }

    public static function getResumeEntities($table, $resume_id)
    {
        $query = DB::table($table)->whereNotNull($table.'.resume_id');
        $query->where($table.'.resume_id', $resume_id);
        $query->select($table.'.*');
        $query->from($table);
        $result = objToArr($query->get());
        return $result;
    }

    public static function getCandidatesForCSV($ids)
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->from('candidates');
        $query->select(
            'candidates.*',
            'resumes.*',
            DB::Raw('GROUP_CONCAT('.dbprfx().'resume_experiences.title) as job_title')
        );
        $query->whereIn('candidates.candidate_id', explode(',', $ids));
        $query->leftJoin('resumes', function($join) {
            $join->on('resumes.candidate_id', '=', 'candidates.candidate_id')->where('resumes.is_default', '=', '1');
        });
        $query->leftJoin('resume_experiences', 'resume_experiences.resume_id', '=', 'resumes.resume_id');
        $query->groupBy('candidates.candidate_id');
        $query->orderBy('candidates.created_at', 'DESC');
        return $query->get();
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
}