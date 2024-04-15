<?php

namespace App\Models\Candidate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Candidate\Quiz;
use App\Models\Candidate\Resume;
use App\Models\Candidate\JobFilter;

class Job extends Model
{
    protected $table = 'jobs';
    protected static $tbl = 'jobs';
    protected $primaryKey = 'job_id';    
    protected $candidate_id;

    public static function getJob($value, $bySlug = false)
    {       
        $query = Self::whereNotNull('jobs.job_id');
        if (!$bySlug) {
            $query->where('jobs.job_id', decode($value));
        } else {
            $query->where('jobs.slug', $value);
        }
        $query->where('jobs.employer_id', employerIdBySlug());
        $query->where('jobs.status', 1);
        $query->select(
            'jobs.*',
            'departments.title as department',
            'employers.slug as employer_slug', 
            'employers.image as employer_image',
            'employers.email as employer_email',
            'settings.value as display_jobs_last_date',
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_quizes.job_quiz_id)) as quizes_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_traites.job_traite_id)) as traites_count'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_traites.traite_id)) as traites'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_custom_fields.label) SEPARATOR "-=-++-=-") as field_labels'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_custom_fields.value) SEPARATOR "-=-++-=-") as field_values'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_traites.job_traite_id) SEPARATOR "-=-++-=-") as job_traite_ids'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_traites.title) SEPARATOR "-=-++-=-") as traite_titles'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'job_filter_values.job_filter_value_id, "(--)", '.dbprfx().'job_filter_values.title)) SEPARATOR "-=-++-=-") as job_filter_values'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'job_filters.job_filter_id, "(--)", '.dbprfx().'job_filters.title, "(--)", '.dbprfx().'job_filters.icon)) SEPARATOR "-=-++-=-") as job_filter_titles'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'job_filter_value_assignments.job_filter_id, "-", '.dbprfx().'job_filter_value_assignments.job_filter_value_id))) AS combined') 
        );
        if (setting('departments_creation') == 'only_admin') {
            $query->leftJoin('departments', function($join) {
                $join->on('departments.department_id', '=', 'jobs.department_id');
                $join->where('departments.employer_id', '=', 0);
            });
        } else {
            if (canHideAdminDepartments('candidate_area')) {
                $query->leftJoin('departments', function($join) {
                    $join->on('departments.job_id', '=', 'jobs.job_id');
                    $join->where('departments.employer_id', '=', employerIdBySlug());
                });
            } else {
                $query->leftJoin('departments', 'departments.department_id', '=', 'jobs.department_id');
            }
        }
        if (setting('job_filters_creation') == 'only_admin') {
            $query->leftJoin('job_filter_value_assignments', function($join) {
                $join->on('job_filter_value_assignments.job_id', '=', 'jobs.job_id');
                $join->where('job_filter_value_assignments.employer_id', '=', 0);
            });
        } else {
            if (canHideAdminJobFilters('candidate_area')) {
                $query->leftJoin('job_filter_value_assignments', function($join) {
                    $join->on('job_filter_value_assignments.job_id', '=', 'jobs.job_id');
                    $join->where('job_filter_value_assignments.employer_id', '=', employerIdBySlug());
                });
            } else {
                $query->leftJoin('job_filter_value_assignments', 'job_filter_value_assignments.job_id', '=', 'jobs.job_id');
            }
        }
        
        $query->leftJoin('employers', 'employers.employer_id', '=', 'jobs.employer_id');
        $query->leftJoin('job_filter_values', 'job_filter_values.job_filter_value_id', '=', 'job_filter_value_assignments.job_filter_value_id');
        $query->leftJoin('job_filters', 'job_filters.job_filter_id', '=', 'job_filter_values.job_filter_id');
        $query->leftJoin('job_traites', 'job_traites.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_custom_fields', 'job_custom_fields.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_quizes', 'job_quizes.job_id', '=', 'jobs.job_id');
        $query->leftJoin('settings', function($join) {
            $join->on('settings.employer_id', '=', 'employers.employer_id');
            $join->where('settings.key', '=', 'display_jobs_last_date');
        });
        $query->groupBy('jobs.job_id');
        $result = $query->get();
        $result = ($result->count() == 1) ? Self::sorted($result->toArray()) : emptyTableColumns(Self::$tbl);
        return isset($result[0]) ? $result[0] : array();
    }

    public static function getJobQuizes($id)
    {
        $query = DB::table('job_quizes')->whereNotNull('job_quizes.job_quiz_id');
        $query->where('job_quizes.job_id', decode($id));
        $query->select('job_quizes.*');
        $result = $query->get();
        return $result ? objToArr($result->toArray()) : array();
    }

    public static function getAll($srh, $departments, $job_filters)
    {     
        $combined_filters = makeCombined($job_filters, 'array');
        $limit = settingEmpSlug('jobs_per_page') ? settingEmpSlug('jobs_per_page') : 5;

        $query = Self::whereNotNull('jobs.job_id');
        $query->select(
            'jobs.*',
            'departments.title as department',
            'settings.value as display_jobs_last_date',
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_quizes.job_quiz_id)) as quizes_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_traites.job_traite_id)) as traites_count'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_custom_fields.label) SEPARATOR "-=-++-=-") as field_labels'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_custom_fields.value) SEPARATOR "-=-++-=-") as field_values'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_traites.traite_id) SEPARATOR "-=-++-=-") as traite_ids'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_traites.title) SEPARATOR "-=-++-=-") as traite_titles'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'job_filter_values.job_filter_value_id, "(--)", '.dbprfx().'job_filter_values.title)) SEPARATOR "-=-++-=-") as job_filter_values'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'job_filters.job_filter_id, "(--)", '.dbprfx().'job_filters.title, "(--)", '.dbprfx().'job_filters.icon)) SEPARATOR "-=-++-=-") as job_filter_titles'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'job_filter_value_assignments.job_filter_id, "-", '.dbprfx().'job_filter_value_assignments.job_filter_value_id))) AS combined') 
        );
        $query->where('jobs.status', 1);
        $query->where('jobs.employer_id', employerIdBySlug());
        if ($departments) {
            $query->whereIn('jobs.department_id', Self::sortForSearch($departments));
        }
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('jobs.title', 'like', '%'.$srh.'%')
                ->orWhere('jobs.description', 'like', '%'.$srh.'%');
            });
        }
        if ($combined_filters) {
            $query->where(function($q) use ($combined_filters) {
                foreach ($combined_filters as $comb) {
                    $q->where('jobs.combined_filters', 'like', '%'.$comb.'%');
                }
            });
        }
        if (setting('departments_creation') == 'only_admin') {
            $query->leftJoin('departments', function($join) {
                $join->on('departments.department_id', '=', 'jobs.department_id');
                $join->where('departments.employer_id', '=', 0);
            });
        } else {
            if (canHideAdminDepartments('candidate_area')) {
                $query->leftJoin('departments', function($join) {
                    $join->on('departments.job_id', '=', 'jobs.job_id');
                    $join->where('departments.employer_id', '=', employerIdBySlug());
                });
            } else {
                $query->leftJoin('departments', 'departments.department_id', '=', 'jobs.department_id');
            }
        }
        $query->leftJoin('employers', 'employers.employer_id', '=', 'jobs.employer_id');
        $query->leftJoin('job_custom_fields', 'job_custom_fields.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_traites', 'job_traites.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_quizes', 'job_quizes.job_id', '=', 'jobs.job_id');
        $query->leftJoin('settings', function($join) {
            $join->on('settings.employer_id', '=', 'employers.employer_id');
            $join->where('settings.key', '=', 'display_jobs_last_date');
        });
        if (setting('job_filters_creation') == 'only_admin') {
            $query->leftJoin('job_filter_value_assignments', function($join) {
                $join->on('job_filter_value_assignments.job_id', '=', 'jobs.job_id');
                $join->where('job_filter_value_assignments.employer_id', '=', 0);
            });
        } else {
            if (canHideAdminJobFilters('candidate_area')) {
                $query->leftJoin('job_filter_value_assignments', function($join) {
                    $join->on('job_filter_value_assignments.job_id', '=', 'jobs.job_id');
                    $join->where('job_filter_value_assignments.employer_id', '=', employerIdBySlug());
                });
            } else {
                $query->leftJoin('job_filter_value_assignments', 'job_filter_value_assignments.job_id', '=', 'jobs.job_id');
            }
        }

        $query->leftJoin('job_filter_values', 'job_filter_values.job_filter_value_id', '=', 'job_filter_value_assignments.job_filter_value_id');
        $query->leftJoin('job_filters', 'job_filters.job_filter_id', '=', 'job_filter_values.job_filter_id');
        $query->orderBy('jobs.job_id', 'DESC');
        $query->groupBy('jobs.job_id');
        $query = $query->paginate($limit);
        $results = $query->toArray();
        return array(
            'results' => Self::sorted($results['data']),
            'pagination' => $query->links('candidate'.viewPrfx().'partials.pagination-jobs')
        );        

    }

    public static function getAppliedJobs()
    {
        $query = DB::table('job_applications')->whereNotNull('job_applications.job_application_id');
        $query->select(DB::Raw('GROUP_CONCAT('.dbprfx().'job_applications.job_id) as applied'));
        $query->where('job_applications.candidate_id', candidateSession());
        $query->groupBy('job_applications.candidate_id');
        $result = objToArr($query->first());
        return isset($result['applied']) ? explode(',', $result['applied']) : array();
    }

    public static function getAppliedJobsList($limit = '')
    {
        $limit = $limit ? $limit : 5;
        $query = DB::table('job_applications')->whereNotNull('job_applications.job_application_id');
        $query->select(
            'jobs.*',
            'job_applications.status as job_status',
            'job_applications.created_at as applied_on',
            'departments.title as department',
            'employers.slug as employer_slug',
            'employers.image as employer_image',
            'employers.company', 
            'memberships.details',            
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_custom_fields.label) SEPARATOR "-=-++-=-") as field_labels'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_custom_fields.value) SEPARATOR "-=-++-=-") as field_values')
        );
        $query->where('job_applications.candidate_id', candidateSession());
        $query->leftJoin('jobs', 'jobs.job_id', '=', 'job_applications.job_id');
        $query->leftJoin('departments', 'departments.department_id', '=', 'jobs.department_id');
        $query->leftJoin('job_custom_fields', 'job_custom_fields.job_id', '=', 'jobs.job_id');
        $query->leftJoin('employers', 'employers.employer_id', '=', 'jobs.employer_id');
        $query->leftJoin('memberships', function($join) {
            $join->on('memberships.employer_id', '=', 'employers.employer_id');
            $join->where('memberships.status', '=', '1');
            $join->where('memberships.expiry', '>', \DB::raw('NOW()'));
        });        
        $query->orderBy('job_applications.created_at', 'DESC');
        $query->groupBy('job_applications.job_id');
        
        $query = $query->paginate($limit);
        $results = $query->toArray();
        $results = $results ? objToArr($results['data']) : array();

        return array(
            'results' => Self::sorted($results),
            'pagination' => $query->links('candidate'.viewPrfx().'partials.pagination-account')
        );
    }   

    public static function getFavoriteJobsList($limit = '')
    {
        $limit = $limit ? $limit : 5;
        $query = DB::table('job_favorites')->whereNotNull('job_favorites.job_id');
        $query->select(
            'jobs.*',
            'job_favorites.created_at as favorited_on',
            'departments.title as department',
            'employers.slug as employer_slug',
            'employers.image as employer_image',
            'employers.company', 
            'memberships.details',
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_custom_fields.label) SEPARATOR "-=-++-=-") as field_labels'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_custom_fields.value) SEPARATOR "-=-++-=-") as field_values')
        );
        $query->where('job_favorites.candidate_id', candidateSession());
        $query->from('job_favorites');
        $query->leftJoin('jobs', 'jobs.job_id', '=', 'job_favorites.job_id');
        $query->leftJoin('departments', 'departments.department_id', '=', 'jobs.department_id');
        $query->leftJoin('job_custom_fields', 'job_custom_fields.job_id', '=', 'jobs.job_id');
        $query->leftJoin('employers', 'employers.employer_id', '=', 'jobs.employer_id');
        $query->leftJoin('memberships', function($join) {
            $join->on('memberships.employer_id', '=', 'employers.employer_id');
            $join->where('memberships.status', '=', '1');
            $join->where('memberships.expiry', '>', \DB::raw('NOW()'));
        });
        $query->orderBy('job_favorites.created_at', 'DESC');
        $query->groupBy('job_favorites.job_id');

        $query = $query->paginate($limit);
        $results = $query->toArray();
        $results = $results ? objToArr($results['data']) : array();

        return array(
            'results' => Self::sorted($results),
            'pagination' => $query->links('candidate'.viewPrfx().'partials.pagination-account')
        );
    }

    public static function getReferredJobsList($limit = '')
    {
        $limit = $limit ? $limit : 5;
        $query = DB::table('job_referred')->whereNotNull('job_referred.job_id');
        $query->select(
            'jobs.*',
            'job_referred.created_at as referred_on',
            'job_referred.name',
            'job_referred.email',
            'job_referred.phone',
            'departments.title as department',
            'employers.slug as employer_slug',
            'employers.image as employer_image',
            'employers.company', 
            'memberships.details',            
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_custom_fields.label) SEPARATOR "-=-++-=-") as field_labels'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_custom_fields.value) SEPARATOR "-=-++-=-") as field_values')
        );
        $query->where('job_referred.candidate_id', candidateSession());
        $query->from('job_referred');
        $query->leftJoin('jobs', 'jobs.job_id', '=', 'job_referred.job_id');
        $query->leftJoin('departments', 'departments.department_id', '=', 'jobs.department_id');
        $query->leftJoin('job_custom_fields', 'job_custom_fields.job_id', '=', 'jobs.job_id');
        $query->leftJoin('employers', 'employers.employer_id', '=', 'jobs.employer_id');
        $query->leftJoin('memberships', function($join) {
            $join->on('memberships.employer_id', '=', 'employers.employer_id');
            $join->where('memberships.status', '=', '1');
            $join->where('memberships.expiry', '>', \DB::raw('NOW()'));
        });        
        $query->orderBy('job_referred.created_at', 'DESC');
        $query->groupBy('job_referred.job_id');

        $query = $query->paginate($limit);
        $results = $query->toArray();
        $results = $results ? objToArr($results['data']) : array();

        return array(
            'results' => Self::sorted($results),
            'pagination' => $query->links('candidate'.viewPrfx().'partials.pagination-account')
        );
    }

    public static function ifAlreadyReferred($data)
    {
        $query = DB::table('job_referred')->whereNotNull('job_referred.job_id');
        $query->where('job_id', decode($data['job_id']));
        $query->where('email', $data['email']);
        $query->where('candidate_id', candidateSession());
        if ($query->first()) {
            return true;
        } else {
            return false;
        }
    }

    public static function referJob($data)
    {
        unset($data['_token'], $data['csrf_token']);

        $job_detail = Self::where('job_id', decode($data['job_id']))->first();
        $employer_id = $job_detail->employer_id;

        $data['candidate_id'] = candidateSession();
        $data['job_id'] = decode($data['job_id']);
        $data['employer_id'] = $employer_id;
        $data['created_at'] = date('Y-m-d G:i:s');
        $data['updated_at'] = date('Y-m-d G:i:s');
        DB::table('job_referred')->insert($data);

        //Getting employer detail for email
        $employer_detail = DB::table('employers')->where('employer_id', $employer_id)->first();
        $employer_logo = DB::table('settings')->where('employer_id', $employer_id)->where('key', 'site_logo')->first();
        $employer_site_name = DB::table('settings')->where('employer_id', $employer_id)->where('key', 'site_name')->first();
        if (empMembership($employer_id, 'branding')) {
            $site_logo = route('uploads-view', config('constants.upload_dirs.employers').$employer_detail->slug.'/branding/'.$employer_logo->value);
            $site_name = $employer_site_name->value;
        } else {
            $site_logo = setting('site_logo');
            $site_name = setting('site_name');
        }
        $return = array(
            'site_logo' => $site_logo, 
            'site_name' => $site_name,
            'url' => empUrlByValue($employer_detail->slug)
        );
        return $return;
    }

    public static function applyJob($data)
    {
        //Getting the job detail and setting employer_id
        $job_detail = Self::where('job_id', decode($data['job_id']))->first();
        $employer_id = $job_detail->employer_id;

        $traites = isset($data['traites']) ? $data['traites'] : array();
        $traite_ts = isset($data['traite_titles']) ? $data['traite_titles'] : array();
        $traite_titles = array();
        foreach ($traite_ts as $key => $value) {
            $traite_titles[decode($key)] = $value;
        }

        $traites_result = array();

        //First : Inserting into job application table
        $apply['candidate_id'] = candidateSession();
        $apply['created_at'] = date('Y-m-d G:i:s');
        $apply['job_id'] = decode($data['job_id']);
        $apply['employer_id'] = $employer_id;
        if (setting('enable-multiple-resume') == 'no') {
            $apply['resume_id'] = Resume::getFirstDetailedResume();
        } else {
            $apply['resume_id'] = decode($data['resume']);
        }
        DB::table('job_applications')->insert($apply);
        $job_application_id = DB::getPdo()->lastInsertId();

        //Second : Inserting traites to job traites answers
        if ($traites) {
            foreach ($traites as $key => $value) {
                $key = decode($key);
                $traites_result[] = $value;
                $answer['candidate_id'] = candidateSession();
                $answer['job_application_id'] = $job_application_id;
                $answer['created_at'] = date('Y-m-d G:i:s');
                $answer['job_traite_id'] = $key;
                $answer['job_traite_title'] = isset($traite_titles[$key]) ? $traite_titles[$key] : 'null';
                $answer['rating'] = $value;
                $answer['employer_id'] = $employer_id;
                DB::table('job_traite_answers')->insert($answer);
            }
        }

        //Third : inserting overall traite results to job_applications table //For Job Board results
        $total = array_sum($traites_result);
        $div = count($traites_result)*5;
        $traites_result = $div > 0 ? ceil(($total/$div)*100) : 0;
        DB::table('job_applications')->where('job_application_id', $job_application_id)->update(array('traites_result' => $traites_result));

        //Forth : copying any assigned quiz from job_quizes to candidate_quizes
        $job_quizes = Self::getJobQuizes($data['job_id']);
        foreach ($job_quizes as $quiz) {
            $candidate_quiz['candidate_id'] = candidateSession();
            $candidate_quiz['job_id'] = decode($data['job_id']);
            $candidate_quiz['job_quiz_id'] = $quiz['job_quiz_id'];
            $candidate_quiz['quiz_title'] = $quiz['quiz_title'];
            $candidate_quiz['quiz_data'] = $quiz['quiz_data'];
            $candidate_quiz['total_questions'] = $quiz['total_questions'];
            $candidate_quiz['allowed_time'] = $quiz['allowed_time'];
            $candidate_quiz['attempt'] = 0;
            $candidate_quiz['correct_answers'] = 0;
            $candidate_quiz['created_at'] = date('Y-m-d G:i:s');
            $candidate_quiz['employer_id'] = $employer_id;
            DB::table('candidate_quizes')->insert($candidate_quiz);
        }

        //Fifth : updating overall results
        Self::updateOverallResultInJobApplication(
            array('candidate_id' => candidateSession(), 'job_id' => decode($data['job_id']))
        );

        //Sixth : saving associating candidate with the employer
        Self::insertEmployerCandidateIfNotExist($employer_id, candidateSession());

        //Seventh : collecting details for email templates and then returning
        $employer_detail = DB::table('employers')->where('employer_id', $employer_id)->first();
        $settings = DB::table('settings')->where('employer_id', $employer_id)->whereIn('key', array('site_logo', 'site_name', 'candidate_job_app', 'employer_job_app', 'admin_email'))->select('key', 'value')->get();
        $settings = $settings ? $settings->toArray() : array();
        $sortedSettings = array();
        foreach ($settings as $s) {
            $sortedSettings[$s->key] = $s->value;
        }
        if (empMembership($employer_id, 'branding')) {
            $site_logo = route('uploads-view', config('constants.upload_dirs.employers').$employer_detail->slug.'/branding/'.issetVal($sortedSettings, 'site_logo'));
            $site_name = issetVal($sortedSettings, 'site_name');
            $candidate_job_app = issetVal($sortedSettings, 'candidate_job_app');
            $employer_job_app = issetVal($sortedSettings, 'employer_job_app');
        } else {
            $site_logo = setting('site_logo');
            $site_name = setting('site_name');
            $candidate_job_app = setting('candidate_job_app');
            $employer_job_app = setting('employer_job_app');
        }
        $return = array(
            'site_logo' => $site_logo, 
            'site_name' => $site_name,
            'url' => empUrlByValue($employer_detail->slug),
            'candidate_job_app' => $candidate_job_app,
            'employer_job_app' => $employer_job_app,
            'admin_email' => issetVal($sortedSettings, 'admin_email'),
        );
        return $return;
    }

    public static function getCompleteQuiz($quiz_id)
    {
        $result = array();
        $result['quiz'] = Quiz::get('quiz_id', $quiz_id);
        $result['questions'] = Quiz::quizQuestions($quiz_id);
        foreach ($result['questions'] as $key => $question) {
            $answers = Quiz::quizQuestionAnswers($question['quiz_question_id']);
            $result['questions'][$key]['answers'] = $answers;
        }
        return objToArr($result);
    }    

    public static function markFavorite($job_id)
    {
        $existing = DB::table('job_favorites')->where('job_id', decode($job_id))
            ->where('candidate_id', candidateSession())
            ->first();
        if (!$existing) {
            $data['candidate_id'] = candidateSession();
            $data['job_id'] = decode($job_id);
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['employer_id'] = employerIdBySlug();
            DB::table('job_favorites')->insert($data);
            return true;
        } else {
            return false;
        }
    }

    public static function unmarkFavorite($job_id)
    {
        $data['job_id'] = decode($job_id);
        $data['candidate_id'] = candidateSession();
        DB::table('job_favorites')->where($data)->delete();
    }

    public static function getFavorites()
    {
        $query = DB::table('job_favorites')->whereNotNull('job_favorites.job_id');
        $query->select(DB::Raw('GROUP_CONCAT('.dbprfx().'job_favorites.job_id) as ids'));
        $query->where('candidate_id', candidateSession());
        $result = $query->first();
        $result = isset($result->ids) && $result->ids !== null ? explode(',', $result->ids) : array();
        return $result;
    }

    public static function remove($job_id)
    {
        Self::where(array('job_id' => $job_id))->delete();
    }

    private static function updateOverallResultInJobApplication($data)
    {
        $query = DB::table('job_applications')->whereNotNull('job_applications.job_application_id');
        $query->where('job_applications.candidate_id', $data['candidate_id']);
        $query->where('job_applications.job_id', $data['job_id']);
        $query->update(array(
            'overall_result' => 
            DB::Raw('ROUND(('.dbprfx().'job_applications.traites_result+'.dbprfx().'job_applications.quizes_result+'.dbprfx().'job_applications.interviews_result)/3)')
        ));
    }

    private static function insertEmployerCandidateIfNotExist($employer_id, $candidate_id)
    {
        $condition = array('employer_id' => $employer_id, 'candidate_id' => $candidate_id);
        $existing = DB::table('employer_candidates')->where($condition)->first();
        if (!$existing) {
            DB::table('employer_candidates')->insert($condition);
        }
    }

    public static function sorted($jobs)
    {
        $return = array();
        $jobs = objToArr($jobs);
        foreach ($jobs as $job) {
            $labels = explode('-=-++-=-', $job['field_labels']);
            $values = explode('-=-++-=-', $job['field_values']);
            if (isset($job['job_traite_ids'])) {
                $job_traite_ids = explode('-=-++-=-', $job['job_traite_ids']);
                $traite_titles = explode('-=-++-=-', $job['traite_titles']);
            }
            if (isset($labels[0])) {
                $fields = arrangeSections(array('label' => $labels, 'value' => $values));
            } else {
                $fields = array();
            }
            if (isset($job_traite_ids[0])) {
                $traites = arrangeSections(array('id' => $job_traite_ids, 'title' => $traite_titles));
            } else {
                $traites = array();
            }
            $job['fields'] = $fields;
            $job['traites'] = $traites;
            if (isset($job['job_filter_titles'])) {
            $job['job_filters'] = Self::sortJobFilters($job['job_filter_titles'], $job['job_filter_values'], $job['combined']);
            }
            unset(
                $job['field_labels'],
                $job['field_values'],
                $job['job_traite_ids'],
                $job['traite_titles'],
                $job['job_filter_titles'],
                $job['job_filter_values']
            );
            $return[] = $job;
        }
        return $return;
    }    

    private static function sortJobFilters($titles, $values, $combined)
    {
        $explodedTitles = explode('-=-++-=-', $titles);
        $explodedValues = explode('-=-++-=-', $values);
        $explodedCombined = explode(',', $combined);
        $results = array();
        $cleanedTitles = array();
        $cleanedValues = array();
        $cleanedIcons = array();
        $cleanedCombined = array();
        foreach ($explodedTitles as $t) {
            $exploded = explode('(--)', $t);
            if (isset($exploded[1])) {
                $cleanedTitles[$exploded[0]] = $exploded[1]; 
            }
            if (isset($exploded[2])) {
                $cleanedIcons[$exploded[0]] = $exploded[2]; 
            }
        }
        foreach ($explodedValues as $t) {
            $exploded = explode('(--)', $t);
            if (isset($exploded[1])) {
                $cleanedValues[$exploded[0]] = $exploded[1]; 
            }
        }
        foreach ($explodedCombined as $t) {
            $exploded = explode('-', $t);
            if (isset($exploded[1])) {
                $cleanedCombined[$exploded[0]][] = $exploded[1];
            }
        }
        foreach ($cleanedCombined as $job_filter_id => $job_filter_value_ids) {
            $results[$job_filter_id]['title'] = isset($cleanedTitles[$job_filter_id]) ? $cleanedTitles[$job_filter_id] : '';
            $results[$job_filter_id]['icon'] = isset($cleanedIcons[$job_filter_id]) ? $cleanedIcons[$job_filter_id] : '';
            foreach ($job_filter_value_ids as $id) {
                if (isset($cleanedValues[$id])) {
                    $results[$job_filter_id]['values'][] = $cleanedValues[$id];

                }
            }
        }
        return $results;
    }   
   
    private static function sortForSearch($data)
    {
        $return = array();
        $array = explode(',', $data);
        foreach ($array as $value) {
            if ($value) {
                $return[] = decode($value);
            }
        }
        return $return;
    }
}