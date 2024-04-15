<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionAnswer extends Model
{
    protected $table = 'question_answers';
    protected static $tbl = 'question_answers';
    protected $primaryKey = 'question_answer_id';    

    public static function getQuestionAnswer($column, $value)
    {
        $value = $column == 'question_answer_id' || $column == 'question_answers.question_answer_id' ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function getQuestionAnswers($column, $value)
    {
        $value = $column == 'question_id' || $column == 'question_answers.question_id' ? decode($value) : $value;
        $query = Self::whereNotNull('question_answers.question_answer_id');
        $query->where($column, $value);
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function remove($question_answer_id)
    {
        Self::where(array('question_answer_id' => decode($question_answer_id)))->delete();
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'question_answer_id' || $field == 'question_answers.question_answer_id' ? decode($value) : $value;
        $query = Self::whereNotNull('question_answers.question_answer_id');
        $query->where($field, $value);
        $query->where('employer_id', employerId());        
        if ($edit) {
            $query->where('question_answer_id !=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;
    }
}