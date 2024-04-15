<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CandidateInterview extends Model
{
    protected $table = 'candidate_interviews';
    protected static $tbl = 'candidate_interviews';
    protected $key = 'candidate_interview_id';

    public static function getCandidateInterview($column, $value)
    {
        $value = $column == 'candidate_interview_id' || $column == 'candidate_interviews.candidate_interview_id' ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function storeCandidateInterview($data)
    {
        unset($data['_token']);

        //Getting original candidate interview
        $interview = Self::getCandidateInterview('candidate_interview_id', $data['candidate_interview_id']);

        //Separaring out variables
        $result['overall_rating'] = array_sum($data['ratings']);
        $result['answers_data'] = json_encode(arrangeSections(array('rating' => $data['ratings'], 'comment' => $data['comments'])));
        $result['updated_at'] = date('Y-m-d G:i:s');
        $result['status'] = 1;
        $result['employer_id'] = employerId();

        Self::where('candidate_interview_id', decode($data['candidate_interview_id']))->update($result);
        return array('job_id' => $interview['job_id'], 'candidate_id' => $interview['candidate_id']);
    }

    public static function deleteCandidateInterview($candidate_interview_id)
    {
        $interview = Self::getCandidateInterview('candidate_interview_id', $candidate_interview_id);
        Self::where(array('candidate_interview_id' => decode($candidate_interview_id)))->delete();
        return array('job_id' => $interview['job_id'], 'candidate_id' => $interview['candidate_id']);
    }

    public static function candidateInterviewsList($request)
    {
        $columns = array(
            "candidate_interviews.title",
            "candidates.candidate_id",
            "jobs.job_id",
            "interviewers.employer_id",
            "candidate_interviews.created_at",
            "candidate_interviews.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('candidate_interviews.candidate_interview_id');
        $query->select(
            'candidate_interviews.*',
            'jobs.title as job',
            DB::Raw('CONCAT('.dbprfx().'interviewers.first_name," ",'.dbprfx().'interviewers.last_name) as interviewer'),
            DB::Raw('CONCAT('.dbprfx().'candidates.first_name," ",'.dbprfx().'candidates.last_name) as candidate')
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('candidate_interviews.first_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidate_interviews.last_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidate_interviews.interview_title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('candidate_interviews.status', $request['status']);
        }
        if (isset($request['job_id']) && $request['job_id'] != '') {
            $query->where('candidate_interviews.job_id', decode($request['job_id']));
        }
        if (isset($request['interviewer_id']) && $request['interviewer_id'] != '') {
            $query->where('candidate_interviews.interviewer_id', decode($request['interviewer_id']));
        }
        if (!empAllowedTo('all_candidate_interviews')) {
            $query->where('candidate_interviews.interviewer_id', employerSession());
        }
        $query->where('candidate_interviews.employer_id', employerId());
        $query->leftJoin('jobs', 'jobs.job_id', '=', 'candidate_interviews.job_id');
        $query->leftJoin('employers as interviewers', 'interviewers.employer_id', '=', 'candidate_interviews.interviewer_id');
        $query->leftJoin('candidates', 'candidates.candidate_id', '=', 'candidate_interviews.candidate_id');
        $query->groupBy('candidate_interviews.candidate_interview_id');
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
        $query = Self::whereNotNull('candidate_interviews.candidate_interview_id');
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('candidate_interviews.first_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidate_interviews.last_name', 'like', '%'.$srh.'%');
                $q->orWhere('candidate_interviews.interview_title', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('candidate_interviews.status', $request['status']);
        }
        if (isset($request['job_id']) && $request['job_id'] != '') {
            $query->where('candidate_interviews.job_id', decode($request['job_id']));
        }
        if (isset($request['interviewer_id']) && $request['interviewer_id'] != '') {
            $query->where('candidate_interviews.interviewer_id', decode($request['interviewer_id']));
        }
        if (!empAllowedTo('all_candidate_interviews')) {
            $query->where('candidate_interviews.interviewer_id', employerSession());
        }
        $query->where('candidate_interviews.employer_id', employerId());
        $query->leftJoin('jobs', 'jobs.job_id', '=', 'candidate_interviews.job_id');
        $query->leftJoin('employers as interviewers', 'interviewers.employer_id', '=', 'candidate_interviews.interviewer_id');
        $query->leftJoin('candidates', 'candidates.candidate_id', '=', 'candidate_interviews.candidate_id');
        $query->groupBy('candidate_interviews.candidate_interview_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($candidate_interviews)
    {
        $sorted = array();
        foreach ($candidate_interviews as $c) {
            $actions = '';
            $id = encode($c['candidate_interview_id']);
            if ($c['status'] == 1) {
                $button_text = __('message.done');
                $button_class = 'success';
                $button_title = __('message.done');
            } else {
                $button_text = __('message.pending');
                $button_class = 'warning';
                $button_title = __('message.pending');
            }
            if (empAllowedTo('view_conduct_interviews')) {
                $actions = '
                    <button type="button" class="btn btn-primary btn-xs view-or-conduct-candidate-interview" data-id="'.$id.'">'.__('message.view_conduct').'</button>
                ';
            }
            $sorted[] = array(
                esc_output($c['interview_title'], 'html'),
                esc_output($c['candidate'], 'html'),
                esc_output($c['job'], 'html'),
                esc_output($c['interviewer'], 'html'),
                date('d M, Y', strtotime($c['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-candidate-interview-status" data-status="'.$c['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }
}