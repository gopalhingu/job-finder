<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Interview  extends Model
{
    protected $table = 'interviews';
    protected static $tbl = 'interviews';
    protected $primaryKey = 'interview_id';

    public static function get($column, $value)
    {
        $value = $column == 'interview_id' || $column == 'interviews.interview_id' ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function store($data, $edit = null)
    {
        unset($data['_token'], $data['interview_id']);
        $data['employer_id'] = employerId();

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('interview_id', decode($edit))->update($data);
            return array('id' => $edit, 'title' => $data['title']);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            return array('id' => encode($id), 'title' => $data['title']);
        }
    }

    public static function cloneInterview($data)
    {
        $interview_id = decode($data['interview_id']);
        unset($data['interview_id'], $data['_token']);

        //First : create new interview
        $data['created_at'] = date('Y-m-d G:i:s');
        $data['employer_id'] = employerId();
        Self::insert($data);
        $new_interview_id = DB::getPdo()->lastInsertId();
        
        //Second : getting question of cloned interview and inserting
        $questions = Self::interviewQuestions($interview_id);
        foreach ($questions as $question) {
            $interview_question_id_original = $question['interview_question_id'];
            unset($question['interview_question_id']);
            $question['interview_id'] = $new_interview_id;
            $question['employer_id'] = employerId();
            DB::table('interview_questions')->insert($question);
            $interview_question_id = DB::getPdo()->lastInsertId();
        }
    }

    public static function remove($interview_id)
    {
        //First : deleting interview_questions
        DB::table('interview_questions')->where(array('interview_id' => decode($interview_id)))->delete();

        //Second : Finally deleting interview
        DB::table('interviews')->where(array('interview_id' => decode($interview_id)))->delete();
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'interview_id' || $field == 'interviews.interview_id' ? decode($value) : $value;
        $query = Self::whereNotNull('interviews.interview_id');
        $query->where($field, $value);
        $query->where('employer_id', employerId());        
        if ($edit) {
            $query->where('interview_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;
    }

    public static function getAll($active = false)
    {
        $query = Self::whereNotNull('interviews.interview_id');
        $query->where('employer_id', employerId());
        if ($active) {
            $query->where('status', 1);
        }
        $query->orderBy('created_at', 'DESC');
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function getDropDown($interview_category_id = '')
    {
        $query = Self::whereNotNull('interviews.interview_id');
        if ($interview_category_id) {
            $query->where('interview_category_id', decode($interview_category_id));
        }
        $query->select('interview_id', 'title', 'status');
        $query->where('employer_id', employerId());
        $query->orderBy('created_at', 'DESC');
        $result = $query->get();
        $result = $result ? $result->toArray() : array();
        $encodedArray = array();
        foreach ($result as $r) {
            $r['interview_id'] = encode($r['interview_id']);
            $encodedArray[] = $r;
        }
        return $encodedArray;

    }

    public static function getCompleteInterview($interview_id)
    {
        $result = array();
        $result['interview'] = Self::get('interview_id', $interview_id);
        $result['questions'] = Self::interviewQuestions($interview_id);
        return objToArr($result);
    }

    public static function interviewQuestions($interview_id = '')
    {
        $query = DB::table('interview_questions')->whereNotNull('interview_questions.interview_question_id');
        $query->where('interview_id', decode($interview_id));
        $query->orderBy('order', 'ASC');
        $result = $query->get();
        return $result ? objToArr($result->toArray()) : array();
    }

    public static function getInterviewsCount()
    {
        return DB::table('candidate_interviews')->where('status', 1)->where('employer_id', employerId())->get()->count();
    }

    public static function getTotalInterviews($id = '')
    {
        $query = Self::whereNotNull('interviews.interview_id');
        $query->where('status', 1);
        $query->where('employer_id', employerId());
        if ($id) {
            $query->where('interviews.interview_id', '!=', decode($id));
        }
        return $query->get()->count();
    }
}
