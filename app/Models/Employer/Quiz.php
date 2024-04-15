<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Employer\CandidateQuiz;

class Quiz extends Model
{
    protected $table = 'quizes';
    protected static $tbl = 'quizes';
    protected $primaryKey = 'quiz_id';

    public static function get($column, $value)
    {
        $value = $column == 'quiz_id' || $column == 'quizes.quiz_id' ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function store($data, $edit = null)
    {
        unset($data['_token'], $data['quiz_id']);
        $data['employer_id'] = employerId();
        $data['quiz_category_id'] = issetVal($data, 'quiz_category_id') ? decode(issetVal($data, 'quiz_category_id')) : '';

        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('quiz_id', decode($edit))->update($data);
            return array('id' => $edit, 'title' => $data['title']);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            return array('id' => encode($id), 'title' => $data['title']);
        }
    }

    public static function cloneQuiz($data)
    {
        $quiz_id = decode($data['quiz_id']);
        unset($data['quiz_id'], $data['_token']);

        //First create new quiz
        $data['created_at'] = date('Y-m-d G:i:s');
        $data['employer_id'] = employerId();
        Self::insert($data);
        $new_quiz_id = DB::getPdo()->lastInsertId();
        
        //Second -> getting question of cloned quiz and inserting
        foreach (Self::quizQuestions($quiz_id) as $question) {
            $quiz_question_id_original = $question['quiz_question_id'];
            unset($question['quiz_question_id']);
            $question['quiz_id'] = $new_quiz_id;
            DB::table('quiz_questions')->insert($question);
            $quiz_question_id = DB::getPdo()->lastInsertId();

            //Third -> inserting question answers
            foreach (Self::quizQuestionAnswers($quiz_question_id_original) as $answer) {
                unset($answer['quiz_question_answer_id']);
                $answer['quiz_question_id'] = $quiz_question_id;
                DB::table('quiz_question_answers')->insert($answer);
                $answer_id = DB::getPdo()->lastInsertId();
           }
        }
    }

    public static function remove($quiz_id)
    {
        $quiz_id = decode($quiz_id);

        //First : getting quiz_question_ids
        $query = DB::table('quiz_questions')->whereNotNull('quiz_questions.quiz_id');
        $query->where('quiz_questions.quiz_id', $quiz_id);
        $query->select(DB::Raw('GROUP_CONCAT('.dbprfx().'quiz_questions.quiz_question_id) AS ids'));
        $result = $query->first();
        $result = $result ? $result->ids : '';

        //Second : deleting quiz_question_answers
        DB::table('quiz_question_answers')->whereIn('quiz_question_answers.quiz_question_id', explode(',', $result))->delete();

        //Third : deleting quiz_questions
        DB::table('quiz_questions')->where(array('quiz_id' => $quiz_id))->delete();

        //Forht : Finally deleting quiz
        Self::where(array('quiz_id' => $quiz_id))->delete();
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'quiz_id' || $field == 'quizes.quiz_id' ? decode($value) : $value;
        $query = Self::whereNotNull('quizes.quiz_id');
        $query->where($field, $value);
        $query->where('employer_id', employerId());
        if ($edit) {
            $query->where('quiz_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;

    }

    public static function getAll($active = false)
    {
        $query = Self::whereNotNull('quizes.quiz_id');
        $query->where('employer_id', employerId());
        if ($active) {
            $query->where('status', 1);
        }
        $query->orderBy('created_at', 'DESC');
        $query = $query->get();
        return $query ? $query->toArray() : array();
    }

    public static function getDropDown($quiz_category_id = '')
    {
        $query = Self::whereNotNull('quizes.quiz_id');
        if ($quiz_category_id) {
            $query->where('quiz_category_id', decode($quiz_category_id));
        }
        $query->where('employer_id', employerId());
        $query->select('quiz_id', 'title', 'status');
        $query->orderBy('created_at', 'DESC');
        $query = $query->get();
        $result = $query ? $query->toArray() : array();
        $encodedArray = array();
        foreach ($result as $r) {
            $r['quiz_id'] = encode($r['quiz_id']);
            $encodedArray[] = $r;
        }
        return $encodedArray;
    }

    public static function getCompleteQuiz($quiz_id)
    {
        $result = array();
        $result['quiz'] = Self::get('quiz_id', $quiz_id);
        $result['questions'] = Self::quizQuestions(decode($quiz_id));
        foreach ($result['questions'] as $key => $question) {
            $answers = Self::quizQuestionAnswers($question['quiz_question_id']);
            $result['questions'][$key]['answers'] = $answers;
        }
        return $result;
    }

    public static function quizQuestions($quiz_id = '')
    {
        $query = DB::table('quiz_questions')->whereNotNull('quiz_questions.quiz_question_id');
        $query->where('quiz_id', $quiz_id);
        $query->orderBy('order', 'ASC');
        $questions = $query->get();
        $questions = $questions ? objToArr($questions->toArray()) : array();
        return $questions;
    }

    public static function quizQuestionAnswers($quiz_question_id = '')
    {
        $query = DB::table('quiz_question_answers')->whereNotNull('quiz_question_answers.quiz_question_answer_id');
        $query->where('quiz_question_id', $quiz_question_id);
        $answers = $query->get();
        $answers = $answers ? objToArr($answers->toArray()) : array();
        return $answers;
    }

    public static function getCandidateQuiz($column, $value)
    {
        $query = DB::table('candidate_quizes')->whereNotNull('candidate_quizes.candidate_quiz_id');
        $query->where('candidate_quizes.employer_id', employerId());        
        $query->where($column, $value);
        $result = $query->first();
        return $result ? objToArr($result) : emptyTableColumns('candidate_quizes');
    }

    public static function getTotalQuizes($id = '')
    {
        $query = Self::whereNotNull('quizes.quiz_id');
        $query->where('status', 1);
        $query->where('employer_id', employerId());
        if ($id) {
            $query->where('quizes.quiz_id', '!=', decode($id));
        }
        return $query->get()->count();
    }    

    public static function deleteCandidateQuiz($candidate_quiz_id)
    {
        $candidate_quiz_id = decode($candidate_quiz_id);
        $quiz = Self::getCandidateQuiz('candidate_quiz_id', $candidate_quiz_id);
        DB::table('candidate_quizes')->where(array('candidate_quiz_id' => $candidate_quiz_id))->delete();
        return array('job_id' => $quiz['job_id'], 'candidate_id' => $quiz['candidate_id']);
    }
}
