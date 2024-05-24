<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Admin\CandidatePackage;
use App\Models\Front\CandidateMembership;

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

    public static function getProfilewithJob($column, $value)
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->from('candidates');
        $query->select(
            'candidates.*',
            'candidate_memberships.show_hide_personal_info',
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'candidate_job_tags.job_tags_id)) as candidateJobTags')
        );
        $query->where($column, $value);
        $query->where('candidates.status', 1);
        if (setting('enable_candidate_packages') == 'yes') {
            $default = setting('default_settings_for_candidates');
            if ($default == 'display') {
                $query->where(function($q) {
                    $q->where('candidates.show_profile', 'yes')->where('candidate_memberships.show_hide_personal_info', 1)->orWhere('candidate_memberships.candidate_membership_id', null);
                });
            } else {
                $query->where(function($q) {
                    $q->where('candidates.show_profile', 'yes')->where('candidate_memberships.show_hide_personal_info', 1)->orWhere('candidate_memberships.candidate_membership_id', '<>', null);
                });                
            }
        }
        $query->leftJoin('candidate_memberships', function($join) {
            $join->on('candidate_memberships.candidate_id', '=', 'candidates.candidate_id')
            ->where('candidate_memberships.status', '=', '1');
        });        
        $query->leftJoin('candidate_job_tags', 'candidate_job_tags.candidate_id', '=', 'candidates.candidate_id');
        $query->groupBy('candidates.candidate_id');
        $result = $query->first();
        $result = $result ? Self::sortResumeElements($result->toArray()) : array();
        return $result;
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

    public static function storeRememberMeToken($email, $token)
    {
        Self::where('email', $email)->update(array('token' => $token));
    }

    public static function updateProfile($data, $image = NULL)
    {
        unset($data['_token'], $data['csrf_token'], $data['image']);
        $data['updated_at'] = date('Y-m-d G:i:s');
        if ($image) {
            $data['image'] = $image;
        }
        
        $jobTags = isset($data['jobTags']) ? $data['jobTags'] : array();
        unset($data['jobTags']);

        Self::insertCandidateJobTags($jobTags, candidateSession());
        
        return Self::where('candidate_id', candidateSession())->update($data);
    }

    public static function insertCandidateJobTags($jobTags, $candidate_id)
    {
        //First deleting
        DB::table('candidate_job_tags')->where('candidate_id', $candidate_id)->delete();

        //Getting job tags for new
        $jobTagsData = DB::table('job_tags')->whereIn('id', ($jobTags ? decodeArray($jobTags) : array(0)))->get();
        $jobTagsData = $jobTagsData ? objToArr($jobTagsData->toArray()) : array();

        //Inserting new jobTags
        foreach ($jobTagsData as $key => $value) {
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['job_tags_id'] = $value['id'];
            $data['candidate_id'] = $candidate_id;
            DB::table('candidate_job_tags')->insert($data);
        }
    }

    public static function checkExistingPassword($password)
    {
        $candidate = Self::where('candidate_id', candidateSession())->first();
        return \Hash::check($password, $candidate->password);
    }    

    public static function updatePasswordByField($field, $value, $password)
    {
        $update = array('password' => $password, 'token' => '');
        Self::where($field, $value)->update($update);
        setSession('candidate.password', $password);
        return true;
    }

    public static function createCandidate($data, $verification = false)
    {
        //Preparing insert data
        if ($verification) {
            $data['token'] = token();
            $data['status'] = 0;
        } else {
            $data['token'] = '';
            $data['status'] = 1;
        }
        unset($data['retype_password'], $data['_token'], $data['csrf_token'], $data['type'], $data['company']);
        $data['image'] = '';
        $data['password'] = \Hash::make($data['password']);
        $data['account_type'] = 'site';
        $data['slug'] = slugify($data['first_name'].' '.$data['last_name']);
        $data['external_id'] = '';
        $data['created_at'] = date('Y-m-d G:i:s');
        Self::insert($data);
        $id = DB::getPdo()->lastInsertId();
        $candidate = Self::getFirst('candidates.candidate_id', $id);

        //Checking if packages are enabled
        if (setting('enable_candidate_packages') == 'yes') {        
            $freePackage = objToArr(CandidatePackage::where('is_free', 1)->first()->toArray());

            //Recording payment record to our database
            $membership['candidate_package_id'] = $freePackage['candidate_package_id'];
            $membership['payment_type'] = 'free';
            $membership['package_type'] = 'free';
            $membership['details'] = json_encode($freePackage);
            $membership['show_hide_personal_info'] = $freePackage['show_hide_personal_info'];
            $membership['price_paid'] = '0';
            $membership['title'] = $freePackage['title'];
            $membership['candidate_id'] = $candidate['candidate_id'];
            $membership['payer_email'] = $candidate['email'];
            $membership['receiver_email'] = setting('admin_email');
            $membership['created_at'] = date('Y-m-d G:i:s');
            $membership['transaction_id'] = slugify().'-free';
            $membership['payment_status'] = 'paid';
            $membership['payment_currency'] = $freePackage['currency_for_api'];
            $membership['file'] = '';
            $membership['message'] = '';
            $membership['status'] = 1;
            $membership['expiry'] = packageExpiry('free');
            $membership['response'] = '';

            //Adding to database
            CandidateMembership::addPayment($membership);
        }


        return $candidate;
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

    public static function createTokenForCandidate($email)
    {
        Self::where('email', $email)->update(array('token' => token()));
    }        

    public static function getSingle($column, $value)
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->from('candidates');
        $query->select(
            'candidates.*',
            'candidate_memberships.show_hide_personal_info',
            'resumes.experience',
            'resumes.designation',
            'resumes.resume_id',
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'resume_experiences.title) SEPARATOR "-)(-") as job_titles'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'resume_skills.title) SEPARATOR "-)(-") as skill_titles'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_experiences.resume_experience_id)) as experiences_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_achievements.resume_achievement_id)) as achievements_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_languages.resume_language_id)) as languages_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_qualifications.resume_qualification_id)) as qualifications_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_references.resume_reference_id)) as references_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_skills.resume_skill_id)) as skills_count'),
        );
        $query->where($column, $value);
        $query->where('candidates.status', 1);
        if (setting('enable_candidate_packages') == 'yes') {
            $default = setting('default_settings_for_candidates');
            if ($default == 'display') {
                $query->where(function($q) {
                    $q->where('candidates.show_profile', 'yes')->where('candidate_memberships.show_hide_personal_info', 1)->orWhere('candidate_memberships.candidate_membership_id', null);
                });
            } else {
                $query->where(function($q) {
                    $q->where('candidates.show_profile', 'yes')->where('candidate_memberships.show_hide_personal_info', 1)->orWhere('candidate_memberships.candidate_membership_id', '<>', null);
                });                
            }
        }
        $query->leftJoin('candidate_memberships', function($join) {
            $join->on('candidate_memberships.candidate_id', '=', 'candidates.candidate_id')
            ->where('candidate_memberships.status', '=', '1');
        });        
        $query->leftJoin('resumes', function($join) {
            $join->on('resumes.candidate_id', '=', 'candidates.candidate_id')->where('resumes.is_default', '=', '1');
        });
        $query->leftJoin('resume_experiences', 'resume_experiences.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_achievements', 'resume_achievements.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_languages', 'resume_languages.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_qualifications', 'resume_qualifications.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_references', 'resume_references.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_skills', 'resume_skills.resume_id', '=', 'resumes.resume_id');
        $query->groupBy('candidates.candidate_id');
        $result = $query->first();
        $result = $result ? Self::sortResumeElements($result->toArray()) : array();
        return $result;
    }

    public static function getForFront($active = true, $limit, $orderByCol, $orderByDir)
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->from('candidates');
        $query->select(
            'candidates.*',
            'resumes.experience',
            'resumes.designation',
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'resume_experiences.title) SEPARATOR "-)(-") as job_titles'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'resume_skills.title) SEPARATOR "-)(-") as skill_titles'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_experiences.resume_experience_id)) as experiences_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_achievements.resume_achievement_id)) as achievements_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_languages.resume_language_id)) as languages_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_qualifications.resume_qualification_id)) as qualifications_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_references.resume_reference_id)) as references_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_skills.resume_skill_id)) as skills_count'),
        );
        if (isset($active)) {
            $query->where('candidates.status', $active);
        }
        $query->leftJoin('resumes', function($join) {
            $join->on('resumes.candidate_id', '=', 'candidates.candidate_id')->where('resumes.is_default', '=', '1');
        });
        $query->leftJoin('resume_experiences', 'resume_experiences.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_achievements', 'resume_achievements.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_languages', 'resume_languages.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_qualifications', 'resume_qualifications.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_references', 'resume_references.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_skills', 'resume_skills.resume_id', '=', 'resumes.resume_id');
        $query->groupBy('candidates.candidate_id');
        $query->orderBy($orderByCol, $orderByDir);
        $query->skip(0);
        $query->take($limit);
        $result = $query->get();
        $result = $result ? Self::sortResumeElements($result->toArray()) : array();
        return $result;
    }

    public static function getSimilar($skills)
    {
        $query = Self::whereNotNull('candidates.candidate_id');
        $query->from('candidates');
        $query->select(
            'candidates.*',
            'resumes.experience',
            'resumes.designation',
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'resume_experiences.title) SEPARATOR "-)(-") as job_titles'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'resume_skills.title) SEPARATOR "-)(-") as skill_titles'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_experiences.resume_experience_id)) as experiences_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_achievements.resume_achievement_id)) as achievements_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_languages.resume_language_id)) as languages_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_qualifications.resume_qualification_id)) as qualifications_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_references.resume_reference_id)) as references_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_skills.resume_skill_id)) as skills_count'),
        );
        $query->where('candidates.status', 1);
        $query->whereIn('resume_skills.title', $skills);
        $query->leftJoin('resumes', function($join) {
            $join->on('resumes.candidate_id', '=', 'candidates.candidate_id')->where('resumes.is_default', '=', '1');
        });
        $query->leftJoin('resume_experiences', 'resume_experiences.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_achievements', 'resume_achievements.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_languages', 'resume_languages.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_qualifications', 'resume_qualifications.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_references', 'resume_references.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_skills', 'resume_skills.resume_id', '=', 'resumes.resume_id');
        $query->groupBy('candidates.candidate_id');
        $query->orderBy('candidates.candidate_id', 'DESC');
        $query->skip(0);
        $query->take(5);
        $result = $query->get();
        $result = $result ? Self::sortResumeElements($result->toArray()) : array();
        return $result;
    }

    public static function getForListPage($r)
    {
        $search = issetVal($r, 'search');
        $sort = issetVal($r, 'sort');

        $candidates_experiences_value = issetVal($r, 'candidates_experiences_value');
        $candidates_experiences_range = issetVal($r, 'candidates_experiences_range');
        $candidates_qualifications_value = issetVal($r, 'candidates_qualifications_value');
        $candidates_qualifications_range = issetVal($r, 'candidates_qualifications_range');
        $candidates_achievements_value = issetVal($r, 'candidates_achievements_value');
        $candidates_achievements_range = issetVal($r, 'candidates_achievements_range');
        $candidates_skills_value = issetVal($r, 'candidates_skills_value');
        $candidates_skills_range = issetVal($r, 'candidates_skills_range');
        $candidates_languages_value = issetVal($r, 'candidates_languages_value');
        $candidates_languages_range = issetVal($r, 'candidates_languages_range');

        $query = Self::whereNotNull('candidates.candidate_id');
        $query->from('candidates');
        $query->select(
            'candidates.*',
            'candidate_memberships.show_hide_personal_info',
            'resumes.experience',
            'resumes.designation',
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'resume_experiences.title) SEPARATOR "-)(-") as job_titles'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'resume_skills.title) SEPARATOR "-)(-") as skill_titles'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_experiences.resume_experience_id)) as experiences_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_achievements.resume_achievement_id)) as achievements_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_languages.resume_language_id)) as languages_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_qualifications.resume_qualification_id)) as qualifications_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_references.resume_reference_id)) as references_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_skills.resume_skill_id)) as skills_count'),
        );
        if ($search) {
            $query->where(function($q) use($search) {
                $q->where('candidates.first_name', 'like', '%'.$search.'%')->orWhere('candidates.last_name', 'like', '%'.$search.'%');
            });
        }
        if ($candidates_experiences_value) {
            $query->where('resume_experiences.title', 'like', '%'.$candidates_experiences_value.'%');
        }
        if ($candidates_experiences_range) {
            $query->having('experiences_count', '>=', $candidates_experiences_range);
        }
        if ($candidates_qualifications_value) {
            $query->where('resume_qualifications.title', 'like', '%'.$candidates_qualifications_value.'%');
        }
        if ($candidates_qualifications_range) {
            $query->having('qualifications_count', '>=', $candidates_qualifications_range);
        }
        if ($candidates_achievements_value) {
            $query->where('resume_achievements.title', 'like', '%'.$candidates_achievements_value.'%');
        }
        if ($candidates_achievements_range) {
            $query->having('achievements_count', '>=', $candidates_achievements_range);
        }
        if ($candidates_skills_value) {
            $query->where('resume_skills.title', 'like', '%'.$candidates_skills_value.'%');
        }
        if ($candidates_skills_range) {
            $query->having('skills_count', '>=', $candidates_skills_range);
        }
        if ($candidates_languages_value) {
            $query->where('resume_languages.title', 'like', '%'.$candidates_languages_value.'%');
        }
        if ($candidates_languages_range) {
            $query->having('languages_count', '>=', $candidates_languages_range);
        }
        $query->where('candidates.status', 1);
        if (setting('enable_candidate_packages') == 'yes') {
            $default = setting('default_settings_for_candidates');
            if ($default == 'display') {
                $query->where(function($q) {
                    $q->where('candidates.show_profile', 'yes')->where('candidate_memberships.show_hide_personal_info', 1)->orWhere('candidate_memberships.candidate_membership_id', null);
                });
            } else {
                $query->where(function($q) {
                    $q->where('candidates.show_profile', 'yes')->where('candidate_memberships.show_hide_personal_info', 1)->orWhere('candidate_memberships.candidate_membership_id', '<>', null);
                });                
            }
        }
        $query->leftJoin('candidate_memberships', function($join) {
            $join->on('candidate_memberships.candidate_id', '=', 'candidates.candidate_id')
            ->where('candidate_memberships.status', '=', '1');
        });
        $query->leftJoin('resumes', function($join) {
            $join->on('resumes.candidate_id', '=', 'candidates.candidate_id')->where('resumes.is_default', '=', '1');
        });
        $query->leftJoin('resume_experiences', 'resume_experiences.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_achievements', 'resume_achievements.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_languages', 'resume_languages.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_qualifications', 'resume_qualifications.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_references', 'resume_references.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_skills', 'resume_skills.resume_id', '=', 'resumes.resume_id');
        if ($sort == 'sort_newer' || $sort == '') {
            $query->orderBy('candidates.created_at', 'DESC');
        } else {
            $query->orderBy('candidates.candidate_id', 'DESC');
        }        
        $query->groupBy('candidates.candidate_id');
        $query = $query->paginate(setting('candidates_per_page'));
        $results = $query->toArray();
        $results = $results['data'];
        return array(
            'total' => $query->total(),
            'perPage' => $query->perPage(),
            'currentPage' => $query->currentPage(),
            'results' => Self::sortResumeElements($results),
            'pagination' => $query->links('front'.viewPrfx().'partials.candidates-pagination')
        );        
    }

    public static function markFavorite($candidate_id)
    {
        $existing = DB::table('candidate_favorites')->where('candidate_id', decode($candidate_id))
            ->where('employer_id', employerSession())
            ->first();
        if (!$existing) {
            $candidate_id = decode($candidate_id);
            $detail = Self::getSingle('candidates.candidate_id', $candidate_id);
            $data['candidate_id'] = $candidate_id;
            $data['employer_id'] = employerSession();
            $data['created_at'] = date('Y-m-d G:i:s');
            DB::table('candidate_favorites')->insert($data);
            return true;
        } else {
            return false;
        }
    }

    public static function unmarkFavorite($candidate_id)
    {
        $data['candidate_id'] = decode($candidate_id);
        $data['employer_id'] = employerSession();
        DB::table('candidate_favorites')->where($data)->delete();
    }

    public static function getFavorites()
    {
        $query = DB::table('candidate_favorites')->whereNotNull('candidate_favorites.candidate_id');
        $query->select(DB::Raw('GROUP_CONCAT('.dbprfx().'candidate_favorites.candidate_id) as ids'));
        $query->where('employer_id', employerSession());
        $result = $query->first();
        $result = isset($result->ids) && $result->ids !== null ? explode(',', $result->ids) : array();
        return $result;
    }

    private static function sortResumeElements($data)
    {
        $sorted = array();
        foreach ($data as $km => $d) {
            if (is_array($d)) {
                foreach ($d as $k => $v) {
                    if (strpos($v, '-)(-') !== false) {
                        $d[$k] = explode('-)(-', $v);
                    }
                }
                $sorted[] = $d;
            } else {
                if (strpos($d, '-)(-') !== false) {
                    $sorted[$km] = explode('-)(-', $d);
                } else {
                    $sorted[$km] = $d;
                }
            }
        }
        return $sorted;
    }
}