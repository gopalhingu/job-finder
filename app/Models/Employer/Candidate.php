<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Candidate  extends Model
{
    protected $table = 'candidates';
    protected static $tbl = 'candidates';
    protected $primaryKey = 'candidate_id';

    public static function getCandidate($column, $value)
    {
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function getCandidatesForCSV($ids)
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->select(
            'candidates.first_name',
            'candidates.last_name',
            'candidates.email',
            'candidates.phone1',
            'candidates.phone2',
            'candidates.state',
            'candidates.city',
            'candidates.country',
            'candidates.address',
            'candidates.gender',
            'candidates.dob',
            'candidates.bio',
            'resumes.title as resume_title',
            'resumes.designation',
            'resumes.objective',
            'resumes.file',
            'resumes.experience',
            'resumes.experiences',
            'resumes.qualifications',
            'resumes.languages',
            'resumes.achievements',
            'resumes.references',
            DB::Raw('GROUP_CONCAT('.dbprfx().'resume_experiences.title) as current_job_title')
        );
        $query->whereIn('candidates.candidate_id', explode(',', decodeArray($ids)));
        $query->leftJoin('resumes', function($join) {
            $join->on('resumes.candidate_id', '=', 'candidates.candidate_id')->where('resumes.is_default', '=', '1');
        });
        $query->leftJoin('resume_experiences','resume_experiences.resume_id', '=', 'resumes.resume_id',);
        $query->groupBy('candidates.candidate_id');
        $query->orderBy('candidates.created_at', 'DESC');
        return $query->get();
    }

    public static function getAll($active = true, $srh = '')
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->where('employer_id', employerId());        
        if ($active) {
            $query->where('status', 1);
        }
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('candidates.candidatename', 'like', '%'.$srh.'%');
            });
        }
        $query->join('employer_candidates', function($join) {
            $join->on('employer_candidates.candidate_id', '=', 'candidates.candidate_id')
                ->where('employer_candidates.employer_id', '=', employerId());
        });
        return $query->get();
    }

    public static function getTotalCandidates()
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->where('candidates.status', 1);
        $query->join('employer_candidates', function($join) {
            $join->on('employer_candidates.candidate_id', '=', 'candidates.candidate_id')
                ->where('employer_candidates.employer_id', '=', employerId());
        });        
        return $query->get()->count();
    }    

    public static function matchedCandidatesList($request)
    {
        $columns = array(
            "",
            "",
            "candidates.first_name",
            "candidates.last_name",
            "candidates.email",
            "",
            "candidates.first_name",
            "",
            "candidates.account_type",
            "candidates.created_at",
            "candidates.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 || $request['order'][0]['column'] == 7 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        // CANDIDATE
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->select(
            'candidates.*',
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_tags.name)) as candidate_tag'),
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('candidates.first_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidates.last_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidates.email', 'like', '%'.$srh.'%');
            });
        }
        $query->join('employer_candidates', function($join) {
            $join->on('employer_candidates.candidate_id', '=', 'candidates.candidate_id')
                ->where('employer_candidates.employer_id', '=', employerId());
        });
        $query->leftJoin('candidate_job_tags', function($join) {
            $join->on('candidate_job_tags.candidate_id', '=', 'candidates.candidate_id');
        });
        $query->leftJoin('job_tags', function($join) {
            $join->on('candidate_job_tags.job_tags_id', '=', 'job_tags.id');
        });
        $query->groupBy('candidates.candidate_id');
        $query->orderBy($orderColumn, $orderDirection);
        $query->skip($offset);
        $query->take($limit);
        $result = $query->get();
        $resultCandidate = $result ? $result->toArray() : array();

        // JOB
        $query = DB::table('jobs')->whereNotNull('jobs.job_id');
        $query->select(
            // 'jobs.*',
            'jobs.job_id',
            'jobs.title',
            'jobs.last_date',
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_tags.name)) as job_tag'),
            'jobs_candidates.job_id as job_id_check',
            'jobs_candidates.candidate_id as candidate_id_check',
        );
        $query->join('job_follow', function($join) {
            $join->on('job_follow.job_id', '=', 'jobs.job_id')
                ->where('job_follow.employer_id', '=', employerId())->orWhere('jobs.employer_id', '=', employerId());
        });
        $query->leftJoin('user_job_tags', function($join) {
            $join->on('user_job_tags.job_id', '=', 'jobs.job_id');
        });
        $query->leftJoin('job_tags', function($join) {
            $join->on('job_tags.id', '=', 'user_job_tags.job_tags_id');
        });
        $query->leftJoin('jobs_candidates', function($join) {
            $join->on('jobs_candidates.job_id', '=', 'jobs.job_id');
        });
        $query->where('jobs.status', '=', 1);
        $query->groupBy('jobs.job_id');
        $result = $query->get();
        $resultJob = $result ? $result->toArray() : array();

        $returnData = [];
        foreach ($resultCandidate as $key => $value) {
            $u = objToArr($value);
            $id = encode($u['candidate_id']);
            $thumb = candidateThumb($u['image']);
            $candidate_tag = explode(",", $u['candidate_tag']);
            foreach ($candidate_tag as $can_v) {
                foreach ($resultJob as $k => $v) {
                    $j_id = encode($v->job_id);
                    // if (!$v->job_tag) {
                    //     continue;
                    // }
                    $job_tag = explode(",", $v->job_tag);
                    if(count($job_tag) == 0) {
                        continue;
                    }
                    $candidateOperation = '---';
                    if($v->job_id_check != $v->job_id || $v->candidate_id_check != $u['candidate_id']) {
                        $candidateOperation = '<button type="button" class="btn btn-success btn-xs send-mail" data-job="'.$j_id.'" data-id="'.$id.'"><b>Send Mail</b></button>';
                    }
                    if(in_array($can_v, $job_tag)) {
                        $data = [
                            "<input type='checkbox' class='minimal single-check' data-id='".$id."' job-id='".$j_id."'/>", 
                            "<img class='candidate-thumb-table' src='".$thumb['image']."' onerror='this.src=\"".$thumb['error']."\"'/>", 
                            "<a class='view-resume' title='View Resume' data-id='".$id."' href='#'>".$u['first_name']."</a>",
                            $u['last_name'],
                            $u['email'],
                            "<a class='view-job-detail' title='View Job Detail' data-id='".$j_id."' href='#'>".$v->title."</a>",
                            $v->last_date ? date("d M Y", strtotime($v->last_date)) : '---',
                            $candidateOperation,
                        ];
                        $returnData[] = $data;
                    }
                }
            }
        }

        // echo "<pre>";
        // print_r($returnData);
        // die;

        $result = array(
            'data' => $returnData,
            'recordsTotal' => Self::myGetTotal(),
            'recordsFiltered' => Self::myGetTotal($srh, $request),
        );

        return $result;
    }

    public static function myCandidatesList($request)
    {
        $columns = array(
            "",
            "",
            "candidates.first_name",
            "candidates.last_name",
            "candidates.email",
            "",
            "resumes.experience",
            "",
            "candidates.account_type",
            "candidates.created_at",
            "candidates.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 || $request['order'][0]['column'] == 7 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('candidates.candidate_id');
        $query->select(
            'candidates.*',
            'resumes.experience',
            'employer_candidates.employer_id as emp_can_id', 
            DB::Raw('GROUP_CONCAT('.dbprfx().'resume_experiences.title) as job_title')
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('candidates.first_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidates.last_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidates.email', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('candidates.status', $request['status']);
        }
        if (isset($request['account_type']) && $request['account_type'] != '') {
            $query->where('candidates.account_type', $request['account_type']);
        }
        if (isset($request['job_title']) && $request['job_title'] != '') {
            $query->where('resume_experiences.title', 'like', '%'.$request['job_title'].'%');
        }
        if (isset($request['experience']) && $request['experience'] != '') {
            $query->where('resumes.experience >=', $request['experience']);
        }
        $query->leftJoin('resumes', function($join) {
            $join->on('resumes.candidate_id', '=', 'candidates.candidate_id')->where('resumes.is_default', '=', '1');
        });
        $query->join('employer_candidates', function($join) {
            $join->on('employer_candidates.candidate_id', '=', 'candidates.candidate_id')
                ->where('employer_candidates.employer_id', '=', employerId());
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
            'recordsTotal' => Self::myGetTotal(),
            'recordsFiltered' => Self::myGetTotal($srh, $request),
        );

        return $result;
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
            "",
            "candidates.account_type",
            "candidates.created_at",
            "candidates.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 || $request['order'][0]['column'] == 7 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('candidates.candidate_id');
        $query->select(
            'candidates.*',
            'resumes.experience',
            'employer_candidates.employer_id as emp_can_id', 
            DB::Raw('GROUP_CONCAT('.dbprfx().'resume_experiences.title) as job_title')
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('candidates.first_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidates.last_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidates.email', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('candidates.status', $request['status']);
        }
        if (isset($request['account_type']) && $request['account_type'] != '') {
            $query->where('candidates.account_type', $request['account_type']);
        }
        if (isset($request['job_title']) && $request['job_title'] != '') {
            $query->where('resume_experiences.title', 'like', '%'.$request['job_title'].'%');
        }
        if (isset($request['experience']) && $request['experience'] != '') {
            $query->where('resumes.experience >=', $request['experience']);
        }
        $query->leftJoin('resumes', function($join) {
            $join->on('resumes.candidate_id', '=', 'candidates.candidate_id')->where('resumes.is_default', '=', '1');
        });
        // $query->join('employer_candidates', function($join) {
        $query->leftJoin('employer_candidates', function($join) {
            $join->on('employer_candidates.candidate_id', '=', 'candidates.candidate_id')
                ->where('employer_candidates.employer_id', '=', employerId());
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
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('candidates.first_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidates.last_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidates.email', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('candidates.status', $request['status']);
        }
        if (isset($request['account_type']) && $request['account_type'] != '') {
            $query->where('candidates.account_type', $request['account_type']);
        }
        if (isset($request['job_title']) && $request['job_title'] != '') {
            $query->where('resume_experiences.title', 'like', '%'.$request['job_title'].'%');
        }
        if (isset($request['experience']) && $request['experience'] != '') {
            $query->where('resumes.experience >=', $request['experience']);
        }
        $query->leftJoin('resumes', function($join) {
            $join->on('resumes.candidate_id', '=', 'candidates.candidate_id')->where('resumes.is_default', '=', '1');
        });        
        $query->leftJoin('employer_candidates', function($join) {
            $join->on('employer_candidates.candidate_id', '=', 'candidates.candidate_id')->where('employer_candidates.employer_id', '=', employerId());
        });
        $query->leftJoin('resume_experiences', 'resume_experiences.resume_id', '=', 'resumes.resume_id');
        $query->groupBy('candidates.candidate_id');
        return $query->get()->count();
    }

    public static function myGetTotal($srh = false, $request = '')
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('candidates.first_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidates.last_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidates.email', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('candidates.status', $request['status']);
        }
        if (isset($request['account_type']) && $request['account_type'] != '') {
            $query->where('candidates.account_type', $request['account_type']);
        }
        if (isset($request['job_title']) && $request['job_title'] != '') {
            $query->where('resume_experiences.title', 'like', '%'.$request['job_title'].'%');
        }
        if (isset($request['experience']) && $request['experience'] != '') {
            $query->where('resumes.experience >=', $request['experience']);
        }
        $query->leftJoin('resumes', function($join) {
            $join->on('resumes.candidate_id', '=', 'candidates.candidate_id')->where('resumes.is_default', '=', '1');
        });        
        $query->join('employer_candidates', function($join) {
            $join->on('employer_candidates.candidate_id', '=', 'candidates.candidate_id')->where('employer_candidates.employer_id', '=', employerId());
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
            $id = encode($u['candidate_id']);
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
            if (isset($u['emp_can_id']) && !empty($u['emp_can_id'])) {
                $candidateOperation = '<button type="button" class="btn btn-danger btn-xs add-or-remove-candidate" data-id="'.$id.'"><i class="fas fa-trash-alt"></i></button>';
            } else {
                $candidateOperation = '<button type="button" class="btn btn-success btn-xs add-or-remove-candidate" data-id="'.$id.'"><i class="fas fa-check"></i></button>';
            }
            $thumb = candidateThumb($u['image']);
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                "<img class='candidate-thumb-table' src='".$thumb['image']."' onerror='this.src=\"".$thumb['error']."\"'/>",
                "<a class='view-resume' title='View Resume' data-id='".$id."' href='#'>".$u['first_name']."</a>",
                $u['last_name'],
                $u['email'],
                $u['job_title'] ? $u['job_title'] : '---',
                $u['experience'],
                $candidateOperation,
            );
        }
        return $sorted;
    }

    public static function getCompleteResume($id = '', $type = '')
    {
        $query = DB::table('resumes')->whereNotNull('resumes.resume_id');
        $query->select('resumes.*', 'candidates.*');
        if ($type) {
            $query->where('resumes.resume_id', decode($id));
        } else {
            $query->where('resumes.candidate_id', decode($id));
        }
        $query->where('resumes.status', 1);
        $query->leftJoin('candidates','candidates.candidate_id', '=', 'resumes.candidate_id');
        $result = $query->first();
        $result = $result ? objToArr($result) : array();
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

    public static function getCompleteResumeJobBoard($id = '')
    {
        $query = DB::table('resumes')->whereNotNull('resumes.resume_id');
        $query->select('resumes.*', 'candidates.*');
        $query->where('resumes.resume_id', decode($id));
        $query->where('resumes.status', 1);
        $query->leftJoin('candidates','candidates.candidate_id', '=', 'resumes.candidate_id');

        $result = $query->first();
        $result = $result ? objToArr($result) : array();

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
        $query = DB::table($table)->where($table.'.resume_id', $resume_id);
        $query->select($table.'.*');
        $result = $query->get();
        $result = $result ? objToArr($result->toArray()) : array();
        return $result;
    }

    public static function getTopCandidates($data)
    {
        //Setting session for every parameter of the request
        setSessionValues($data);
        $limit = settingEmp('charts_count_on_dashboard');

        $traites_result = getSessionValues('traites_check');
        $interviews_result = getSessionValues('interviews_check');
        $quizes_result = getSessionValues('quizes_check');
        $job_id = decode(getSessionValues('job_id'));

        $query = Self::whereNotNull('candidates.candidate_id');
        if ($job_id) {
            $query->where('job_applications.job_id', $job_id);
        }
        $query->where('candidates.status', 1);
        $query->select(
            DB::Raw('CONCAT('.dbprfx().'candidates.first_name, " ", '.dbprfx().'candidates.last_name) as label'),
            DB::Raw('SUM('.dbprfx().'job_applications.traites_result) as traites_result'),
            DB::Raw('SUM('.dbprfx().'job_applications.quizes_result) as quizes_result'),
            DB::Raw('SUM('.dbprfx().'job_applications.interviews_result) as interviews_result')
        );
        $query->where('job_applications.employer_id', employerId());
        $query->leftJoin('job_applications', 'job_applications.candidate_id', '=', 'candidates.candidate_id');
        $query->groupBy('job_applications.candidate_id');
        $query->orderBy('job_applications.job_application_id', 'DESC');
        $query->offset(0);
        $query->take($limit);
        $result = $query->get();
        $labels = array();
        $totals = array();
        foreach ($result as $key => $value) {
            $total = 0;
            $labels[] = $value->label;
            if ($traites_result) {
                $total = $total + $value->traites_result;
            }
            if ($interviews_result) {
                $total = $total + $value->interviews_result;
            }
            if ($quizes_result) {
                $total = $total + $value->quizes_result;
            }
            $totals[] = round($total/3);
        }

        $result = array(
            'labels' => $labels,
            'data' => $totals,
        );

        return json_encode($result);
    }

    public static function getCandidateResumeIds($candidate_id)
    {
        $query = DB::table('resumes')->whereNotNull('resumes.resume_id');
        $query->select(DB::Raw('GROUP_CONCAT('.dbprfx().'resumes.resume_id) as ids'));
        $query->where('candidate_id', decode($candidate_id));
        $result = $query->first();
        $result = $result ? $result->toArray() : array();
        $result = isset($result['ids']) ? explode(',', $result['ids']) : array();
        return $result;
    }
}