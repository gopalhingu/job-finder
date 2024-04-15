<?php

namespace App\Models\Candidate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Resume extends Model
{
    protected $table = 'resumes';
    protected static $tbl = 'resumes';
    protected $primaryKey = 'resume_id';    

    public static function getFirst($column, $value)
    {
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : array();
    }

    public static function getResumeItem($resume_id, $table, $first_condition = array(), $second_condition = array())
    {
        $query = DB::table($table)->whereNotNull($table.'.resume_id');
        if ($first_condition) {
            $query->where($first_condition[0], $first_condition[1]);
        }
        if ($second_condition) {
            $query->where($second_condition[0], $second_condition[1]);
        }
        $query->where($table.'.resume_id', $resume_id);
        $result = $query->get();
        return ($result->count() > 0) ? true : false;
    }

    public static function getCandidateResumesList()
    {
        if (candidateSession()) {
            $query = Self::whereNotNull('resumes.resume_id');
            $query->select('resumes.title', 'resumes.resume_id');
            $query->where('resumes.candidate_id', candidateSession());
            $query->where('resumes.status', 1);
            $query->orderBy('resumes.updated_at', 'DESC');
            $query->groupBy('resumes.resume_id');
            $result = $query->get();
            return $result ? $result->toArray() : array();
        } else {
            return array();
        }
    }

    public static function getCandidateResumes($candidate_id)
    {
        $query = Self::whereNotNull('resumes.resume_id');
        $query->where('resumes.candidate_id', $candidate_id);
        $query->select(
            'resumes.*',
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_experiences.resume_experience_id)) as experience'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_qualifications.resume_qualification_id)) as qualification'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_languages.resume_language_id)) as language'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_achievements.resume_achievement_id)) as achievement'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'resume_references.resume_reference_id)) as reference')
        );        
        $query->leftJoin('resume_experiences','resume_experiences.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_qualifications','resume_qualifications.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_languages','resume_languages.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_achievements','resume_achievements.resume_id', '=', 'resumes.resume_id');
        $query->leftJoin('resume_references','resume_references.resume_id', '=', 'resumes.resume_id');
        $query->orderBy('resumes.created_at', 'DESC');
        $query->groupBy('resumes.resume_id');
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function getCompleteResume($resume_id)
    {
        $query = Self::whereNotNull('resumes.resume_id');
        $query->where('resumes.resume_id', $resume_id);
        $query->select('resumes.*');
        $result = $query->first();
        $result = $result ? objToArr($result->toArray()) : array();
        $result['experiences'] = Self::getResumeEntities('resume_experiences', $resume_id);
        $result['qualifications'] = Self::getResumeEntities('resume_qualifications', $resume_id);
        $result['languages'] = Self::getResumeEntities('resume_languages', $resume_id);
        $result['achievements'] = Self::getResumeEntities('resume_achievements', $resume_id);
        $result['references'] = Self::getResumeEntities('resume_references', $resume_id);
        $result['skills'] = Self::getResumeEntities('resume_skills', $resume_id);
        if (setting('enable_multiple_resume') != 'yes') {
            $result['type'] = 'detailed';
        }
        $result['resume_id'] = $resume_id;
        return $result;
    }

    public static function getFirstDetailedResume()
    {
        $query = Self::whereNotNull('resumes.resume_id');
        $query->where('resumes.candidate_id', candidateSession());
        $query->where('resumes.type', 'detailed');
        $query->select('resumes.*');
        $result = $query->first();
        $result = $result ? objToArr($result->toArray()) : array();
        $resume_id = isset($result['resume_id']) ? $result['resume_id'] : '';
        if ($resume_id) {
            return $resume_id;
        } else {
            return Self::createFirstDetailedResumeIfNotExist();
        }
    }

    private static function createFirstDetailedResumeIfNotExist()
    {
        $data['candidate_id'] = candidateSession();
        $data['title'] = 'My Resume';
        $data['designation'] = 'My Designation';
        $data['objective'] = 'My Objective';
        $data['status'] = 1;
        $data['type'] = 'detailed';
        $data['created_at'] = date('Y-m-d G:i:s');
        $data['updated_at'] = date('Y-m-d G:i:s');
        Self::insert($data);
        return Self::getFirstDetailedResume();
    }

    public static function getResumeEntities($table, $resume_id)
    {
        $query = DB::table($table)->whereNotNull($table.'.resume_id');
        $query->where($table.'.resume_id', $resume_id);
        $query->select($table.'.*');
        $result = $query->get();
        $result = $result ? objToArr($result->toArray()) : array();
        return $result;
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $query = Self::whereNotNull('resumes.resume_id');
        $query->where($field, $value);
        if ($edit) {
            $query->where('resume_id', '!=', $edit);
        }
        return $query->first() ? true : false;
    }

    public static function createResume($data, $verification = false)
    {
        unset($data['email'], $data['_token'], $data['csrf_token']);
        $data['candidate_id'] = candidateSession();
        $data['status'] = 1;
        $data['created_at'] = date('Y-m-d G:i:s');
        $data['updated_at'] = date('Y-m-d G:i:s');
        Self::insert($data);
        $id = DB::getPdo()->lastInsertId();
        return Self::getFirst('resumes.resume_id', $id);
    }

    private static function insertResumeImage($image, $id)
    {
        $name = $id.'.jpg';
        $asset_path = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.resumes'));
        $full_path = $asset_path.'/'.$name;
        $content = file_get_contents($image);
        $fp = fopen($full_path, "w");
        fwrite($fp, $content);
        fclose($fp);
        $controllerInstance = Controller();
        $controllerInstance->resizeByWidthAndCropByHeight($asset_path, $id, 'jpg', 60, 60);
        $controllerInstance->resizeByWidthAndCropByHeight($asset_path, $id, 'jpg', 120, 120);
    }

    public static function updateResumeMain($resume_id, $experienceTotal = '')
    {   
        if ($experienceTotal == '') {
            $data = array(
                'experiences' => Self::getResumeItemsCount('resume_experiences', $resume_id),
                'qualifications' => Self::getResumeItemsCount('resume_qualifications', $resume_id),
                'languages' => Self::getResumeItemsCount('resume_languages', $resume_id),
                'achievements' => Self::getResumeItemsCount('resume_achievements', $resume_id),
                'references' => Self::getResumeItemsCount('resume_references', $resume_id)
            );
        } else {
            $data = array('experience' => $experienceTotal);
        }
        Self::where('resumes.resume_id', $resume_id)->update($data);
    }

    public static function getResumeItemsCount($table, $resume_id)
    {
        $query = Self::whereNotNull('resumes.resume_id');
        $query->where('resume_id', $resume_id);
        $result = $query->get();
        return $result->count();
    }

    public static function updateResumeGeneral($data, $file)
    {
        $id = decode($data['id']);
        unset($data['id'], $data['_token'], $data['csrf_token']);
        $data['updated_at'] = date('Y-m-d G:i:s');
        if ($file) {
            $data['file'] = $file;
        }
        return Self::where('resumes.resume_id', $id)->update($data);
    }

    public static function updateResumeExperience($data)
    {
        $data = arrangeSections($data);
        foreach ($data as $d) {
            $d['resume_id'] = decode($d['resume_id']);
            if ($d['resume_experience_id']) {
                $id = decode($d['resume_experience_id']);
                unset($d['resume_experience_id'], $d['_token'], $d['csrf_token']);
                $d['updated_at'] = date('Y-m-d G:i:s');
                DB::table('resume_experiences')->where('resume_experiences.resume_experience_id', $id)->update($d);
            } else {
                $existing = Self::getResumeItem(
                    $d['resume_id'], 'resume_experiences', array('title', $d['title']), array('company', $d['company'])
                );
                if (!$existing) {
                    unset($d['resume_experience_id'], $d['_token'], $d['csrf_token']);
                    $new['created_at'] = date('Y-m-d G:i:s');
                    $new['updated_at'] = date('Y-m-d G:i:s');
                    $new = array_merge($new, $d);
                    DB::table('resume_experiences')->insert($new);
                }
            }
        }
        $resume_id = decode($data[0]['resume_id']);
        Self::updateResumeMain($resume_id);
        Self::updateResumeMain($resume_id, Self::getExprienceInMonths($resume_id));
    }

    public static function updateResumeQualification($data)
    {
        $data = arrangeSections($data);
        foreach ($data as $d) {
            $d['resume_id'] = decode($d['resume_id']);
            if ($d['resume_qualification_id']) {
                $id = decode($d['resume_qualification_id']);
                unset($d['resume_qualification_id'], $d['_token'], $d['csrf_token']);
                $d['updated_at'] = date('Y-m-d G:i:s');
                DB::table('resume_qualifications')->where('resume_qualifications.resume_qualification_id', $id)
                ->update($d);
            } else {
                $existing = Self::getResumeItem(
                    $d['resume_id'], 'resume_qualifications', array('title', $d['title']), array('institution', $d['institution'])
                );
                if (!$existing) {
                    unset($d['resume_qualification_id'], $d['_token'], $d['csrf_token']);
                    $new['created_at'] = date('Y-m-d G:i:s');
                    $new['updated_at'] = date('Y-m-d G:i:s');
                    $new = array_merge($new, $d);
                    DB::table('resume_qualifications')->insert($new);
                }
            }
        }
        Self::updateResumeMain(decode($data[0]['resume_id']));
    }

    public static function updateResumeLanguage($data)
    {
        $data = arrangeSections($data);
        foreach ($data as $d) {
            $d['resume_id'] = decode($d['resume_id']);
            if ($d['resume_language_id']) {
                $id = decode($d['resume_language_id']);
                unset($d['resume_language_id'], $d['_token'], $d['csrf_token']);
                $d['updated_at'] = date('Y-m-d G:i:s');
                DB::table('resume_languages')->where('resume_languages.resume_language_id', $id)->update($d);
            } else {
                $existing = Self::getResumeItem($d['resume_id'], 'resume_languages', array('title', $d['title']));
                if (!$existing) {
                    unset($d['resume_language_id'], $d['_token'], $d['csrf_token']);
                    $new['created_at'] = date('Y-m-d G:i:s');
                    $new['updated_at'] = date('Y-m-d G:i:s');
                    $new = array_merge($new, $d);
                    DB::table('resume_languages')->insert($new);
                }
            }
        }
        Self::updateResumeMain(decode($data[0]['resume_id']));
    }

    public static function updateResumeSkill($data)
    {
        $data = arrangeSections($data);
        foreach ($data as $d) {
            $d['resume_id'] = decode($d['resume_id']);
            if ($d['resume_skill_id']) {
                $id = decode($d['resume_skill_id']);
                unset($d['resume_skill_id'], $d['_token'], $d['csrf_token']);
                $d['updated_at'] = date('Y-m-d G:i:s');
                DB::table('resume_skills')->where('resume_skills.resume_skill_id', $id)->update($d);
            } else {
                $existing = Self::getResumeItem($d['resume_id'], 'resume_skills', array('title', $d['title']));
                if (!$existing) {
                    unset($d['resume_skill_id'], $d['_token'], $d['csrf_token']);
                    $new['created_at'] = date('Y-m-d G:i:s');
                    $new['updated_at'] = date('Y-m-d G:i:s');
                    $new = array_merge($new, $d);
                    DB::table('resume_skills')->insert($new);
                }
            }
        }
        Self::updateResumeMain(decode($data[0]['resume_id']));
    }

    public static function updateResumeAchievement($data)
    {
        $data = arrangeSections($data);
        foreach ($data as $d) {
            $d['resume_id'] = decode($d['resume_id']);
            if ($d['resume_achievement_id']) {
                $id = decode($d['resume_achievement_id']);
                unset($d['resume_achievement_id'], $d['_token'], $d['csrf_token']);
                $d['updated_at'] = date('Y-m-d G:i:s');
                DB::table('resume_achievements')->where('resume_achievements.resume_achievement_id', $id)->update($d);
            } else {
                $existing = Self::getResumeItem($d['resume_id'], 'resume_achievements', array('title', $d['title']));
                if (!$existing) {
                    unset($d['resume_achievement_id'], $d['_token'], $d['csrf_token']);
                    $new['created_at'] = date('Y-m-d G:i:s');
                    $new['updated_at'] = date('Y-m-d G:i:s');
                    $new = array_merge($new, $d);
                    DB::table('resume_achievements')->insert($new);
                }
            }
        }
        Self::updateResumeMain(decode($data[0]['resume_id']));
    }

    public static function updateResumeReference($data)
    {
        $data = arrangeSections($data);
        foreach ($data as $d) {
            $d['resume_id'] = decode($d['resume_id']);
            if ($d['resume_reference_id']) {
                $id = decode($d['resume_reference_id']);
                unset($d['resume_reference_id'], $d['_token'], $d['csrf_token']);
                $d['updated_at'] = date('Y-m-d G:i:s');
                DB::table('resume_references')->where('resume_references.resume_reference_id', $id)->update($d);
            } else {
                $existing = Self::getResumeItem(
                    $d['resume_id'], 'resume_references', array('title', $d['title']), array('relation', $d['relation'])
                );
                if (!$existing) {
                    unset($d['resume_reference_id'], $d['_token'], $d['csrf_token']);
                    $new['created_at'] = date('Y-m-d G:i:s');
                    $new['updated_at'] = date('Y-m-d G:i:s');
                    $new = array_merge($new, $d);
                    DB::table('resume_references')->insert($new);
                }
            }
        }
        Self::updateResumeMain(decode($data[0]['resume_id']));
    }

    public static function removeSection($section_id, $type)
    {
        switch ($type) {
            case 'experience':
                DB::table('resume_experiences')->where(array('resume_experience_id' => decode($section_id)))->delete();
                break;
            case 'qualification':
                DB::table('resume_qualifications')->where(array('resume_qualification_id' => decode($section_id)))->delete();
                break;
            case 'language':
                DB::table('resume_languages')->where(array('resume_language_id' => decode($section_id)))->delete();
                break;
            case 'skill':
                DB::table('resume_skills')->where(array('resume_skill_id' => decode($section_id)))->delete();
                break;
            case 'achievement':
                DB::table('resume_achievements')->where(array('resume_achievement_id' => decode($section_id)))->delete();
                break;
            case 'reference':
                DB::table('resume_references')->where(array('resume_reference_id' => decode($section_id)))->delete();
                break;
            default:
                break;
        }
    }

    public static function updateDocResume($data, $file)
    {
        $id = decode($data['resume_id']);
        unset($data['resume_id'], $data['_token'], $data['csrf_token'], $data['file']);
        $data['updated_at'] = date('Y-m-d G:i:s');
        if ($file) {
            $data['file'] = $file;
        }
        return Self::where('resume_id', $id)->update($data);
    } 

    private static function getExprienceInMonths($resume_id)
    {
        $query = DB::table('resume_experiences')->whereNotNull('resume_experiences.resume_experience_id');
        $query->where('resume_id', $resume_id);
        $query->select('from', 'to');
        $data = $query->get();

        $experience = 0;
        foreach ($data as $key => $value) {
            $experience = $experience + getMonthsBetweenDates($value->from, $value->to) + 1;
        }
        return $experience;
    }

}