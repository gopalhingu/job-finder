<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';
    protected static $tbl = 'quiz_questions';
    protected $primaryKey = 'quiz_question_id';

    public static function getQuizQuestionAnswers($column, $value)
    {
        $value = $column == 'quiz_question_id' || $column == 'quiz_question_answers.quiz_question_id' ? decode($value) : $value;
        $query = DB::table('quiz_question_answers')->where($column, $value)->get();
        return $query ? $query->toArray() : array();
    }

    public static function get($column, $value)
    {
        $value = $column == 'quiz_question_id' || $column == 'quiz_question_answers.quiz_question_id' ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function getAll($quiz_id = '')
    {
        $query = Self::whereNotNull('quiz_questions.quiz_question_id');
        $query->select(
            'quiz_questions.*',
            DB::Raw('COUNT(DISTINCT('.dbprfx().'quiz_question_answers.quiz_question_answer_id)) AS answers_count')
        );
        $query->where('quiz_questions.employer_id', employerId());        
        $query->where('quiz_questions.quiz_id', decode($quiz_id));
        $query->leftJoin('quiz_question_answers', 'quiz_question_answers.quiz_question_id', '=', 'quiz_questions.quiz_question_id');
        $query->groupBy('quiz_questions.quiz_question_id');
        $query->orderBy('quiz_questions.order', 'ASC');
        $query = $query->get();
        $records = $query ? $query->toArray() : array();
        return $records;
    }

    public static function updateQuizQuestion($data, $image = '')
    {
        if ($image) {
            $question['image'] = $image;
        }

        //First : inserting/updating question
        $question['title'] = $data['title'];
        $question['type'] = $data['type'];
        $question['updated_at'] = date('Y-m-d G:i:s');
        Self::where('quiz_question_id', decode($data['quiz_question_id']))->update($question);

        //Second : Arranging answers
        $answers = arrangeSections(array(
            'quiz_question_answer_id' => decodeArray($data['answer_ids']),
            'title' => $data['answer_titles'], 
            'is_correct' => $data['answers']
        ));

        //Third : inserting or updating answers
        foreach ($answers as $answer) {
            if ($answer['quiz_question_answer_id']) {
                DB::table('quiz_question_answers')->where('quiz_question_answer_id', $answer['quiz_question_answer_id'])->update($answer);
            } else {
                $answer['quiz_question_id'] = decode($data['quiz_question_id']);
                unset($data['quiz_question_answer_id']);
                DB::table('quiz_question_answers')->insert($answer);
            }
        }
    }

    public static function add($quiz_id, $question, $answers)
    {
        //First : inserting question
        unset($question['question_id'], $question['question_category_id'], $question['nature']);
        $question['quiz_id'] = decode($quiz_id);
        $question['order'] = 10000;
        Self::insert($question);
        $id = DB::getPdo()->lastInsertId();
        
        //Second : inserting answers
        foreach ($answers as $answer) {
            unset($answer['question_answer_id'], $answer['question_id']);
            $answer['quiz_question_id'] = $id;
            DB::table('quiz_question_answers')->insert($answer);
        }
        
        return $id;
    }

    public static function orderQuestions($data)
    {
        $data = json_decode($data['data']);
        $data = objToArr($data->items);

        foreach ($data as $d) {
            Self::where('quiz_questions.quiz_question_id', decode($d['id']))->update(array('order' => $d['order']));
        }
    }    

    public static function remove($quiz_question_id)
    {
        $quiz_question_id = decode($quiz_question_id);
        Self::where(array('quiz_question_id' => $quiz_question_id))->delete();
        DB::table('quiz_question_answers')->where(array('quiz_question_id' => $quiz_question_id))->delete();
    }    


    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'quiz_question_id' || $field == 'quiz_question_answers.quiz_question_id' ? decode($value) : $value;
        $query = Self::whereNotNull('quiz_questions.quiz_question_id');
        $query->where($field, $value);
        $query->where('quiz_questions.employer_id', employerId());        
        if ($edit) {
            $query->where('quiz_question_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;
    }

    public static function removeAnswer($quiz_question_answer_id)
    {
        DB::table('quiz_question_answers')->where(array('quiz_question_answer_id' => decode($quiz_question_answer_id)))->delete();
    }

    public static function removeImage($quiz_question_id)
    {
        Self::where('quiz_question_id', decode($quiz_question_id))->update(array('image' => ''));
    }  
}