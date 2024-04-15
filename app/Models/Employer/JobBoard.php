<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Employer\Quiz;
use App\Models\Employer\Interview;
use App\Models\Employer\Job;

class JobBoard  extends Model
{
    protected $table = 'jobs';
    protected static $tbl = 'jobs';
    protected $primaryKey = 'job_id';

    public static function getJobs($data)
    {
        //Setting session for every parameter of the request
        setSessionValues($data);

        //First getting total records
        $total = Self::getTotalJobs();
        
        //Setting filters, search and pagination via posted session variables
        $srh = getSessionValues('jobs_search');
        $department_id = getSessionValues('jobs_department_id');
        $status = getSessionValues('jobs_status');
        $page = getSessionValues('jobs_page', 1);
        $per_page = getSessionValues('jobs_per_page', 10);

        $per_page = $per_page < $total ? $per_page : $total;
        $limit = $per_page;
        $offset = ($page == 1 ? 0 : ($page-1)) * $per_page;
        $offset = $offset < 0 ? 0 : $offset;

        $query = Self::whereNotNull('jobs.job_id');
        $query->select(
            'jobs.*',
            'departments.title as department',
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_traites.traite_id)) as traites_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_quizes.quiz_id)) as quizes_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_applications.job_application_id)) as hired_count')
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('jobs.title', 'like', '%'.$srh.'%');
            });
        }        
        if ($department_id) {
            $query->where('jobs.department_id', decode($department_id));
        }
        if ($status) {
            $query->where('jobs.status', $status);
        } else if ($status == 'zero') {
            $query->where('jobs.status', 0);
        }
        $query->where('jobs.employer_id', employerId());
        $query->leftJoin('departments', 'departments.department_id', '=', 'jobs.department_id');
        $query->leftJoin('job_traites', 'job_traites.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_quizes', 'job_quizes.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_applications', function($join) {
            $join->on('job_applications.job_id', '=', 'jobs.job_id')
            ->where('job_applications.status', '=', 'hired');
        });
        $query->groupBy('jobs.job_id');
        $query->orderBy('jobs.created_at', 'DESC');
        $query->skip($offset);
        $query->take($limit);
        $result = $query->get();
        $records = $result ? $result->toArray() : array();

        //Making pagination for display
        $total_pages = $total != 0 ? ceil($total/$per_page) : 0;
        $pagination = ($offset == 0 ? 1 : ($offset+1));
        $pagination .= ' - ';
        $pagination .= $total_pages == $page ? $total : ($limit*$page);
        $pagination .= ' of ';
        $pagination .= $total;

        //Returning final results
        return array(
            'records' => $records,
            'total' =>  $total,
            'total_pages' => $total_pages,
            'pagination' => $pagination
        );
    }

    public static function getTotalJobs()
    {
        $srh = getSessionValues('jobs_search');
        $department_id = getSessionValues('jobs_department_id');
        $status = getSessionValues('jobs_status');

        $query = Self::whereNotNull('jobs.job_id');
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('jobs.title', 'like', '%'.$srh.'%');
            });
        }
        if ($department_id) {
            $query->where('jobs.department_id', decode($department_id));
        }
        if ($status) {
            $query->where('jobs.status', $status);
        } else if ($status == 'zero') {
            $query->where('jobs.status', 0);
        }
        $query->where('jobs.employer_id', employerId());
        $query->leftJoin('departments', 'departments.department_id', '=', 'jobs.department_id');
        $query->groupBy('jobs.job_id');
        return $query->get()->count();
    }

    public static function getCandidates($data, $job_id)
    {
        //Setting session for every parameter of the request
        $job_id = decode($job_id);
        setSessionValues($data);

        //First getting total records
        $total = Self::getTotalCandidatesWithFilters($job_id);
        
        //Search and filters
        $srh = getSessionValues('candidates_search');
        $min_experience = getSessionValues('candidates_min_experience');
        $max_experience = getSessionValues('candidates_max_experience');
        $min_overall = getSessionValues('candidates_min_overall');
        $max_overall = getSessionValues('candidates_max_overall');
        $min_interview = getSessionValues('candidates_min_interview');
        $max_interview = getSessionValues('candidates_max_interview');
        $min_quiz = getSessionValues('candidates_min_quiz');
        $max_quiz = getSessionValues('candidates_max_quiz');
        $min_self = getSessionValues('candidates_min_self');
        $max_self = getSessionValues('candidates_max_self');
        $status = getSessionValues('candidates_status');

        //Pagination and sorting
        $sort = getSessionValues('candidates_sort', 'overall');
        $page = getSessionValues('candidates_page', 1);
        $per_page = getSessionValues('candidates_per_page', 10);

        //Calculating limit and offset
        $limit = $per_page;
        $per_page = $per_page < $total ? $per_page : $total;
        $offset = ($page == 1 ? 0 : ($page-1)) * $per_page;
        $offset = $offset < 0 ? 0 : $offset;

        $query = DB::table('job_applications')->whereNotNull('job_applications.job_application_id');
        $query->select(
            'job_applications.*',
            'job_applications.resume_id',
            'resumes.designation',
            'resumes.experience',
            'resumes.experiences',
            'resumes.qualifications',
            'resumes.languages',
            'resumes.achievements',
            'resumes.references',
            'resumes.type as resume_type',
            'resumes.file',
            'candidates.image',
            'candidates.first_name',
            'candidates.last_name',
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'job_traite_answers.job_traite_answer_id,"-",'.dbprfx().'job_traite_answers.rating)) SEPARATOR "-=-++-=-") AS traite_ratings'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'job_traite_answers.job_traite_id,"*-*",'.dbprfx().'job_traite_answers.job_traite_title)) SEPARATOR "-=-++-=-") AS traite_titles'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'candidate_quizes.candidate_quiz_id, "-", '.dbprfx().'candidate_quizes.total_questions, "-", '.dbprfx().'candidate_quizes.correct_answers)) SEPARATOR "-=-++-=-") AS quizes'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'candidate_quizes.candidate_quiz_id, "*-*", '.dbprfx().'candidate_quizes.quiz_title)) SEPARATOR "-=-++-=-") AS quizes_titles'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'candidate_interviews.candidate_interview_id, "-", '.dbprfx().'candidate_interviews.total_questions, "-", '.dbprfx().'candidate_interviews.overall_rating)) SEPARATOR "-=-++-=-") AS interviews'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'candidate_interviews.candidate_interview_id, "*-*", '.dbprfx().'candidate_interviews.interview_title)) SEPARATOR "-=-++-=-") AS interviews_titles')
        );

        //Applying fiters and search
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('candidates.first_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidates.last_name', 'like', '%'.$srh.'%');
            });            
        }
        if($min_experience) {
            $query->where('resumes.experience', '>=', $min_experience);
        }
        if($max_experience) {
            $query->where('resumes.experience', '<=', $max_experience);
        }
        if($min_overall) {
            $query->where('job_applications.overall_result', '>=', $min_overall);
        }
        if($max_overall) {
            $query->where('job_applications.overall_result', '<=', $max_overall);
        }
        if($min_interview) {
            $query->where('job_applications.interviews_result', '>=', $min_interview);
        }
        if($max_interview) {
            $query->where('job_applications.interviews_result', '<=', $max_interview);
        }
        if($min_quiz) {
            $query->where('job_applications.quizes_result', '>=', $min_quiz);
        }
        if($max_quiz) {
            $query->where('job_applications.quizes_result', '<=', $max_quiz);
        }
        if($min_self) {
            $query->where('job_applications.traites_result', '>=', $min_self);
        }
        if($max_self) {
            $query->where('job_applications.traites_result', '<=', $max_self);
        }
        if($status) {
            $query->where('job_applications.status', $status);
        }

        $query->where('job_applications.job_id', $job_id);
        $query->leftJoin('resumes', 'resumes.resume_id', '=', 'job_applications.resume_id');
        $query->leftJoin('candidates', 'candidates.candidate_id', '=', 'job_applications.candidate_id');
        $query->leftJoin('job_traite_answers', 'job_traite_answers.job_application_id', '=', 'job_applications.job_application_id');
        $query->leftJoin('job_traites', 'job_traites.job_traite_id', '=', 'job_traite_answers.job_traite_id');
        $query->leftJoin('candidate_quizes', function($join) {
            $join->on('candidate_quizes.job_id', '=', 'job_applications.job_id')
            ->on('candidate_quizes.candidate_id', '=', 'job_applications.candidate_id');
        });
        $query->leftJoin('candidate_interviews', function($join) {
            $join->on('candidate_interviews.job_id', '=', 'job_applications.job_id')
            ->on('candidate_interviews.candidate_id', '=', 'job_applications.candidate_id');
        });        
        $query->groupBy('job_applications.job_application_id');

        //Setting order by as per preference
        if ($sort == 'applied') {
            $query->orderBy('job_applications.created_at', 'DESC');
        } elseif ($sort == 'overall') {
            $query->orderBy('job_applications.overall_result', 'DESC');
        } elseif ($sort == 'quiz') {
            $query->orderBy('job_applications.quizes_result', 'DESC');
        } elseif ($sort == 'self') {
            $query->orderBy('job_applications.traites_result', 'DESC');
        } elseif ($sort == 'interview') {
            $query->orderBy('job_applications.interviews_result', 'DESC');
        } elseif ($sort == 'experience') {
            $query->orderBy('resumes.experience', 'DESC');
        }
        
        $query->skip($offset);
        $query->take($limit);
        $result = $query->get();
        $result = $result ? $result->toArray() : array();
        $records = Self::sorted($result);

        //Making pagination for display
        $total_pages = $total != 0 ? ceil($total/$per_page) : 0;
        $pagination = ($offset == 0 ? 1 : ($offset+1));
        $pagination .= ' - ';
        $pagination .= $total_pages == $page ? $total : ($limit*$page);
        $pagination .= ' of ';
        $pagination .= $total;

        //Returning final results
        return array(
            'records' => $records,
            'total' =>  $total,
            'candidates_all' => Self::getTotalCandidates($job_id),
            'total_pages' => $total_pages,
            'pagination' => $pagination
        );
    }

    public static function traitesResult($data)
    {
        $job_id = decode($data['job']);
        $candidate_ids = decodeArray(explode(',', $data['ids']));
        $query = DB::table('job_applications')->whereNotNull('job_applications.job_application_id');
        $query->select(
            'candidates.candidate_id',
            'candidates.first_name',
            'candidates.last_name',
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'job_traite_answers.job_traite_answer_id,"-",'.dbprfx().'job_traite_answers.rating)) SEPARATOR "-=-++-=-") AS traite_ratings'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'job_traite_answers.job_traite_id,"*-*",'.dbprfx().'job_traite_answers.job_traite_title)) SEPARATOR "-=-++-=-") AS traite_titles')
        );
        $query->where('job_applications.employer_id', employerId());       
        $query->where('job_applications.job_id', $job_id);
        $query->whereIn('job_applications.candidate_id', $candidate_ids);
        $query->leftJoin('candidates', 'candidates.candidate_id', '=', 'job_applications.candidate_id');
        $query->leftJoin('job_traite_answers', 'job_traite_answers.job_application_id', '=', 'job_applications.job_application_id');
        $query->leftJoin('job_traites', 'job_traites.job_traite_id', '=', 'job_traite_answers.job_traite_id');
        $query->leftJoin('candidate_quizes', function($join) {
            $join->on('candidate_quizes.job_id', '=', 'job_applications.job_id')
            ->on('candidate_quizes.candidate_id', '=', 'job_applications.candidate_id');
        });
        $query->groupBy('job_applications.job_application_id');        
        $result = $query->get();
        $result = $result ? $result->toArray() : array();
        return Self::sorted($result);
    }

    public static function quizesResult($data)
    {
        $query = DB::table('job_applications')->whereNotNull('job_applications.job_application_id');
        $query->select(
            'job_applications.quizes_result',
            'candidates.candidate_id',
            'candidates.first_name',
            'candidates.last_name',
            'candidate_quizes.quiz_title',
            'candidate_quizes.quiz_data',
            'candidate_quizes.answers_data',
            'candidate_quizes.total_questions',
            'candidate_quizes.correct_answers'
        );
        $query->where('job_applications.employer_id', employerId());        
        $query->where('job_applications.job_id', decode($data['job']));
        $query->whereIn('job_applications.candidate_id', decodeArray(explode(',', $data['ids'])));
        $query->leftJoin('candidates', 'candidates.candidate_id', '=', 'job_applications.candidate_id');
        $query->leftJoin('candidate_quizes', function($join) {
            $join->on('candidate_quizes.candidate_id', '=', 'job_applications.candidate_id')
            ->on('candidate_quizes.job_id', '=', 'job_applications.job_id');
        });

        $result = $query->get();
        $result = $result ? objToArr($result->toArray()) : array();

        //Arranging by candidate
        $final = array();
        foreach ($result as $value) {
            $final[$value['candidate_id']][] = $value;
        }
        return $final;
    }

    public static function interviewsResult($data)
    {
        $query = DB::table('job_applications')->whereNotNull('job_applications.job_application_id');
        $query->select(
            'job_applications.interviews_result',
            'candidates.candidate_id',
            'candidates.first_name',
            'candidates.last_name',
            'candidate_interviews.interview_title',
            'candidate_interviews.interview_data',
            'candidate_interviews.answers_data',
            'candidate_interviews.total_questions',
            'candidate_interviews.overall_rating'
        );
        $query->where('job_applications.employer_id', employerId());
        $query->where('job_applications.job_id', decode($data['job']));
        $query->whereIn('job_applications.candidate_id', decodeArray(explode(',', $data['ids'])));
        $query->join('candidates', 'candidates.candidate_id', '=', 'job_applications.candidate_id', 'left');
        $query->leftJoin('candidate_interviews', function($join) {
            $join->on('candidate_interviews.candidate_id', '=', 'job_applications.candidate_id')
            ->on('candidate_interviews.job_id', '=', 'job_applications.job_id');
        });
        $query = $query->get();
        $result = $query ? objToArr($query->toArray()) : array();

        //Arranging by candidate
        $final = array();
        foreach ($result as $value) {
            $final[$value['candidate_id']][] = $value;
        }
        return $final;
    }

    public static function overallResult($data)
    {
        $query = DB::table('job_applications')->whereNotNull('job_applications.job_application_id');
        $query->select(
            'candidates.candidate_id',
            'candidates.first_name',
            'candidates.last_name',
            'job_applications.created_at as applied_on',
            'job_applications.status',
            'resumes.designation',
            'resumes.objective',
            'resumes.experience',
            'resumes.experiences',
            'resumes.qualifications',
            'resumes.languages',
            'resumes.achievements',
            'resumes.references',
            'job_applications.traites_result as self_assesment',
            'job_applications.quizes_result',
            'job_applications.interviews_result',
            'job_applications.overall_result'
        );
        $query->where('job_applications.employer_id', employerId());
        $query->where('job_applications.job_id', decode($data['job']));
        $query->whereIn('job_applications.candidate_id', decodeArray(explode(',', $data['ids'])));
        $query->leftJoin('resumes', 'resumes.resume_id', '=', 'job_applications.resume_id');
        $query->leftJoin('candidates', 'candidates.candidate_id', '=', 'job_applications.candidate_id');
        $query->groupBy('job_applications.job_application_id');
        $query->orderBy('resumes.experience', 'DESC');
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function getTotalCandidatesWithFilters($job_id)
    {
        //Search and filters
        $srh = getSessionValues('candidates_search');
        $min_experience = getSessionValues('candidates_min_experience');
        $max_experience = getSessionValues('candidates_max_experience');
        $min_overall = getSessionValues('candidates_min_overall');
        $max_overall = getSessionValues('candidates_max_overall');
        $min_interview = getSessionValues('candidates_min_interview');
        $max_interview = getSessionValues('candidates_max_interview');
        $min_quiz = getSessionValues('candidates_min_quiz');
        $max_quiz = getSessionValues('candidates_max_quiz');
        $min_self = getSessionValues('candidates_min_self');
        $max_self = getSessionValues('candidates_max_self');
        $status = getSessionValues('candidates_status');

        $query = DB::table('job_applications')->whereNotNull('job_applications.job_application_id');

        //Applying fiters and search
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('candidates.first_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidates.last_name', 'like', '%'.$srh.'%');
            });            
        }
        if($min_experience) {
            $query->where('resumes.experience', '>=', $min_experience);
        }
        if($max_experience) {
            $query->where('resumes.experience', '<=', $max_experience);
        }
        if($min_overall) {
            $query->where('job_applications.overall_result', '>=', $min_overall);
        }
        if($max_overall) {
            $query->where('job_applications.overall_result', '<=', $max_overall);
        }
        if($min_interview) {
            $query->where('job_applications.interviews_result', '>=', $min_interview);
        }
        if($max_interview) {
            $query->where('job_applications.interviews_result', '<=', $max_interview);
        }
        if($min_quiz) {
            $query->where('job_applications.quizes_result', '>=', $min_quiz);
        }
        if($max_quiz) {
            $query->where('job_applications.quizes_result', '<=', $max_quiz);
        }
        if($min_self) {
            $query->where('job_applications.traites_result', '>=', $min_self);
        }
        if($max_self) {
            $query->where('job_applications.traites_result', '<=', $max_self);
        }
        if($status) {
            $query->where('job_applications.status', $status);
        }
        $query->where('job_applications.employer_id', employerId());
        $query->where('job_applications.job_id', $job_id);
        $query->leftJoin('candidates', 'candidates.candidate_id', '=', 'job_applications.candidate_id');
        $query->leftJoin('resumes', 'resumes.resume_id', '=', 'job_applications.resume_id');
        $query->groupBy('job_applications.job_application_id');
        return $query->get()->count();
    }

    public static function getTotalCandidates($job_id)
    {
        $query = DB::table('job_applications')->whereNotNull('job_applications.job_application_id');        
        $query->where('job_applications.employer_id', employerId());
        $query->where('job_applications.job_id', $job_id);
        $query->groupBy('job_applications.job_application_id');
        return $query->get()->count();
    }

    public static function assignToCandidates($data)
    {
        $candidates = json_decode($data['candidates']);
        $title = '';
        $job_id = decode($data['job_id']);
        $job = Job::where('jobs.job_id', $job_id)->first();
        $job = isset($job->title) ? $job->title : '';

        if ($data['type'] == 'quiz') {
            $qdata = Quiz::getCompleteQuiz($data['quiz_id']);
            $title = $qdata['quiz']['title'];
            foreach ($candidates as $candidate) {
                $candidate = decode($candidate);
                $candidate_quiz['candidate_id'] = $detail['candidate_id'] = $candidate;
                $candidate_quiz['job_id'] = $detail['job_id'] = $job_id;
                $candidate_quiz['job_quiz_id'] = '';
                $candidate_quiz['quiz_title'] = $qdata['quiz']['title'];
                $candidate_quiz['quiz_data'] = json_encode($qdata);
                $candidate_quiz['total_questions'] = count($qdata['questions']);
                $candidate_quiz['allowed_time'] = $qdata['quiz']['allowed_time'];
                $candidate_quiz['correct_answers'] = 0;
                $candidate_quiz['attempt'] = 0;
                $candidate_quiz['created_at'] = date('Y-m-d G:i:s');
                $candidate_quiz['employer_id'] = employerId();
                DB::table('candidate_quizes')->insert($candidate_quiz);
                Self::updateQuizResultInJobApplication($detail);
                Self::updateOverallResultInJobApplication($detail);
            }
        } else {
            $idata = Interview::getCompleteInterview($data['interview_id']);
            $title = $idata['interview']['title'];
            foreach ($candidates as $candidate) {
                $candidate = decode($candidate);
                $candidate_interview['candidate_id'] = $detail['candidate_id'] = $candidate;
                $candidate_interview['job_id'] = $detail['job_id'] = $job_id;
                $candidate_interview['interview_title'] = $idata['interview']['title'];
                $candidate_interview['interview_data'] = json_encode($idata);
                $candidate_interview['interview_time'] = $data['interview_time'];
                $candidate_interview['description'] = $data['description'];
                $candidate_interview['total_questions'] = count($idata['questions']);
                $candidate_interview['overall_rating'] = 0;
                $candidate_interview['created_at'] = date('Y-m-d G:i:s');
                $candidate_interview['interviewer_id'] = decode($data['interviewer_id']);
                $candidate_interview['employer_id'] = employerId();
                DB::table('candidate_interviews')->insert($candidate_interview);
                Self::updateInterviewResultInJobApplication($detail);
                Self::updateOverallResultInJobApplication($detail);
            }
        }

        return array('title' => $title, 'job' => $job,);
    }

    public static function updateCandidateStatus($data)
    {
        $candidates = json_decode($data['data']);
        $data = objToArr(json_decode($data['data']));
        $action = $data['action'];
        $ids = decodeArray($data['ids']);
        $job = decode($data['job']);

        foreach ($ids as $id) {
            DB::table('job_applications')
            ->where('job_applications.job_id', $job)
            ->where('job_applications.candidate_id', $id)
            ->update(array('status' => $action));
        }
    }

    public static function deleteCandidateApplication($data)
    {
        $data = objToArr(json_decode($data['data']));
        $ids = decodeArray($data['ids']);
        $job = decode($data['job']);

        foreach ($ids as $id) {
            DB::table('job_applications')->where(array('candidate_id' => $id, 'job_id' => $job))->delete();
            DB::table('candidate_quizes')->where(array('candidate_id' => $id, 'job_id' => $job))->delete();
            DB::table('candidate_interviews')->where(array('candidate_id' => $id, 'job_id' => $job))->delete();
        }
    }

    public static function updateInterviewResultInJobApplication($data)
    {
        $query = DB::table('candidate_interviews')->whereNotNull('candidate_interviews.candidate_interview_id');
        $query->select(
            DB::Raw('ROUND((SUM('.dbprfx().'candidate_interviews.overall_rating)/(SUM('.dbprfx().'candidate_interviews.total_questions)*10))*100) as percent')
        );
        $query->where('candidate_interviews.candidate_id', $data['candidate_id']);
        $query->where('candidate_interviews.job_id', $data['job_id']);
        $result = $query->first();
        $result = $result ? objToArr($result) : array();
        $percent = isset($result['percent']) ? $result['percent'] : 0;

        DB::table('job_applications')
            ->where('job_applications.candidate_id', $data['candidate_id'])
            ->where('job_applications.job_id', $data['job_id'])
            ->update(array('interviews_result' => $percent));
    }

    public static function updateQuizResultInJobApplication($data)
    {
        $query = DB::table('candidate_quizes')->whereNotNull('candidate_quizes.candidate_quiz_id');
        $query->select(
            DB::Raw('ROUND((SUM('.dbprfx().'candidate_quizes.correct_answers)/SUM('.dbprfx().'candidate_quizes.total_questions))*100) as percent')
        );
        $query->where('candidate_quizes.candidate_id', $data['candidate_id']);
        $query->where('candidate_quizes.job_id', $data['job_id']);
        $result = $query->first();
        $result = $result ? objToArr($result) : array();
        $percent = isset($result['percent']) ? $result['percent'] : 0;

        DB::table('job_applications')
            ->where('job_applications.candidate_id', $data['candidate_id'])
            ->where('job_applications.job_id', $data['job_id'])
            ->update(array('quizes_result' => $percent));
    }

    public static function updateTraiteResultInJobApplication($traites_result, $data)
    {
        $total = array_sum($traites_result);
        $div = count($traites_result)*5;
        $traites_result = ceil(($total/$div)*100);

        DB::table('job_applications')
            ->where('job_applications.candidate_id', $data['candidate_id'])
            ->where('job_applications.job_id', $data['job_id'])
            ->update(array('traites_result' => $traites_result));
    }

    public static function updateOverallResultInJobApplication($data)
    {
        DB::table('job_applications')
        ->where('job_applications.candidate_id', $data['candidate_id'])
        ->where('job_applications.job_id', $data['job_id'])
        ->update(array(
            'overall_result' => DB::Raw('ROUND(('.dbprfx().'job_applications.traites_result+'.dbprfx().'job_applications.quizes_result+'.dbprfx().'job_applications.interviews_result)/3)')
        ));
    }

    public static function sorted($candidates)
    {
        $return = array();
        $candidates = objToArr($candidates);
        foreach ($candidates as $candidate) {
            //Refreshing all
            $traite_ratings = array();
            $traite_titles = array();
            $quizes = array();
            $interviews = array();
            $quiz_titles = array();
            $interview_titles = array();
            //For Traites
            if (isset($candidate['traite_ratings'])) {
                $traite_ratings = explode('-=-++-=-', $candidate['traite_ratings']);
                $traite_titles = explode('-=-++-=-', $candidate['traite_titles']);
            }
            if (isset($traite_ratings[0])) {
                $ids = array();
                $titles = array();
                $ratings = array();
                foreach ($traite_ratings as $value) {
                    $exploded = explode('-', $value);
                    $ratings[] = $exploded[1];
                }
                foreach ($traite_titles as $value) {
                    $exploded = explode('*-*', $value);
                    $titles[] = $exploded[1];
                }
                $candidate['traites'] = arrangeSections(array('title' => $titles, 'rating' => $ratings));
                $candidate['traite_overall'] = array_sum($ratings);
            } else {
                $candidate['traites'] = array();
            }
            //For Quizes
            if (isset($candidate['quizes'])) {
                $quizes = explode('-=-++-=-', $candidate['quizes']);
                $quiz_titles = explode('-=-++-=-', $candidate['quizes_titles']);
            }
            if (isset($quizes[0])) {
                $ids = array();
                $questions = array();
                $corrects = array();
                $titles = array();
                foreach ($quizes as $value) {
                    $exploded = explode('-', $value);
                    $questions[] = $exploded[1];
                    $corrects[] = $exploded[2];
                }
                foreach ($quiz_titles as $value) {
                    $exploded = explode('*-*', $value);
                    $ids[] = $exploded[0];
                    $titles[] = $exploded[1];
                }
                $candidate['quizes'] = arrangeSections(
                    array('questions' => $questions, 'corrects' => $corrects, 'title' => $titles, 'id' => $ids)
                );
            } else {
                $candidate['quizes'] = array();
            }
            //For Interviews
            if (isset($candidate['interviews'])) {
                $interviews = explode('-=-++-=-', $candidate['interviews']);
                $interview_titles = explode('-=-++-=-', $candidate['interviews_titles']);
            }
            if (isset($interviews[0])) {
                $ids = array();
                $questions = array();
                $ratings = array();
                $titles = array();
                foreach ($interviews as $value) {
                    $exploded = explode('-', $value);
                    $questions[] = $exploded[1];
                    $ratings[] = $exploded[2];
                }
                foreach ($interview_titles as $value) {
                    $exploded = explode('*-*', $value);
                    $ids[] = $exploded[0];
                    $titles[] = $exploded[1];
                }
                $candidate['interviews'] = arrangeSections(
                    array('questions' => $questions, 'ratings' => $ratings, 'title' => $titles, 'id' => $ids)
                );
            } else {
                $candidate['interviews'] = array();
            }
            unset($candidate['traite_ratings'],$candidate['traite_titles'],$candidate['quizes_titles'],$candidate['interviews_titles']);
            $return[] = $candidate;
        }
        return $return;
    }

    public static function getJobApplicationsCount($status = '')
    {
        $query = DB::table('job_applications')->whereNotNull('job_applications.job_application_id');
        $query->where('job_applications.employer_id', employerId());        
        if ($status) {
            $query->where('status', $status);
        }
        return $query->get()->count();
    }    

}