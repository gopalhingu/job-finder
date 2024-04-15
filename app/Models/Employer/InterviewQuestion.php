<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InterviewQuestion extends Model
{
    protected $table = 'interview_questions';
    protected static $tbl = 'interview_questions';
    protected $primaryKey = 'interview_question_id';

    public static function get($column, $value)
    {
        $value = $column == 'interview_question_id' || $column == 'interview_questions.interview_question_id' ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function getAll($interview_id = '')
    {
        $query = Self::whereNotNull('interview_questions.interview_question_id');
        $query->select('interview_questions.*');
        $query->where('interview_questions.employer_id', employerId());        
        $query->where('interview_questions.interview_id', decode($interview_id));
        $query->from('interview_questions');
        $query->groupBy('interview_questions.interview_question_id');
        $query->orderBy('interview_questions.order', 'ASC');
        $query = $query->get();
        return $query ? $query->toArray() : array();
    }

    public static function updateInterviewQuestion($data)
    {
        //First inserting/updating question
        $question['title'] = $data['title'];
        $question['updated_at'] = date('Y-m-d G:i:s');
        Self::where('interview_question_id', decode($data['interview_question_id']))->update($question);
    }

    public static function add($interview_id, $question, $answers)
    {
        unset($question['question_id'], $question['question_category_id'], $question['nature'], $question['type'], $question['_token']);
        $question['interview_id'] = decode($interview_id);
        $question['order'] = 10000;
        $question['employer_id'] = employerId();
        Self::insert($question);
        $id = DB::getPdo()->lastInsertId();
        return encode($id);
    }

    public static function orderQuestions($data)
    {
        $data = json_decode($data['data']);
        $data = objToArr($data->items);

        foreach ($data as $d) {
            Self::where('interview_questions.interview_question_id', decode($d['id']))->update(array('order' => $d['order']));
        }
    }

    public static function remove($interview_question_id)
    {
        Self::where(array('interview_question_id' => decode($interview_question_id)))->delete();
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'interview_question_id' || $field == 'interview_questions.interview_question_id' ? decode($value) : $value;        
        $query = Self::whereNotNull('interviews.interview_id');
        $query->where($field, $value);
        $query->where('employer_id', employerId());
        if ($edit) {
            $query->where('interview_question_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;
    }
}