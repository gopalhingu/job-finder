<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    protected $table = 'questions';
    protected static $tbl = 'questions';
    protected $primaryKey = 'question_id';

    public static function getQuestion($column, $value)
    {
        $value = $column == 'question_id' || $column == 'questions.question_id' ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function storeQuestion($data, $image = '')
    {
        if ($image) {
            $question['image'] = $image;
        }
        $question_id = decode($data['question_id']);
        $question_category_id = isset($data['question_category_id']) ? decode($data['question_category_id']) : '';

        unset($data['_token']);
        $question['employer_id'] = employerId();

        //First inserting/updating question
        if ($question_id) {
            $question['question_category_id'] = $question_category_id;
            $question['title'] = $data['title'];
            $question['type'] = isset($data['type']) ? $data['type'] : '';
            $question['updated_at'] = date('Y-m-d G:i:s');
            Self::where('question_id', $question_id)->update($question);
        } else {
            $question['question_category_id'] = $question_category_id;
            $question['title'] = $data['title'];
            $question['type'] = isset($data['type']) ? $data['type'] : '';
            $question['nature'] = $data['nature'];
            $question['created_at'] = date('Y-m-d G:i:s');
            $question['updated_at'] = date('Y-m-d G:i:s');
            Self::insert($question);
            $question_id = DB::getPdo()->lastInsertId();
        }

        if ($data['nature'] == 'quiz') {
            //Second inserting/updating answers
            $customFields = array(
                'question_answer_id' => decodeArray($data['answer_ids']),
                'title' => $data['answer_titles'], 
                'is_correct' => $data['answers']
            );
            $answers = arrangeSections($customFields);
            foreach ($answers as $answer) {
                $answer['employer_id'] = employerId();
                if ($answer['question_answer_id']) {
                    DB::table('question_answers')->where('question_answer_id', $answer['question_answer_id'])->update($answer);
                } else {
                    $answer['question_id'] = $question_id;
                    unset($data['question_answer_id']);
                    DB::table('question_answers')->insert($answer);
                }
            }
        }
    }

    public static function remove($question_id)
    {
        $condition = array('question_id' => decode($question_id));
        Self::where($condition)->delete();
        DB::table('question_answers')->where($condition)->delete();
    }

    public static function removeAnswer($question_answer_id)
    {
        DB::table('question_answers')->where(array('question_answer_id' => decode($question_answer_id)))->delete();
    }

    public static function removeImage($question_id)
    {
        Self::where('question_id', decode($question_id))->update(array('image' => ''));
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'question_id' || $field == 'questions.question_id' ? decode($value) : $value;
        $query = Self::whereNotNull('questions.question_id');
        $query->where($field, $value);
        $query->where('employer_id', employerId());        
        if ($edit) {
            $query->where('question_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;
    }

    public static function getAll($data, $nature = 'quiz')
    {
        //Setting cookies for every of the request
        setSessionValues($data);

        //First getting total records
        $total = Self::getTotal($nature);
        
        //Setting filters, search and pagination via posted session variables
        $srh = getSessionValues($nature.'_questions_search');
        $question_category_id = getSessionValues($nature.'_questions_category_id');
        $type = getSessionValues($nature.'_questions_type');
        $page = getSessionValues($nature.'_questions_page', 1);
        $per_page = getSessionValues($nature.'_questions_per_page', 10);
        $per_page = $per_page < $total ? $per_page : $total;
        $limit = $per_page;
        $offset = ($page == 1 ? 0 : ($page-1)) * $per_page;
        $offset = $offset < 0 ? 0 : $offset;

        $query = Self::whereNotNull('questions.question_id');
        $query->select(
            'questions.*',
            DB::Raw('COUNT(DISTINCT('.dbprfx().'question_answers.question_answer_id)) AS answers_count')
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('questions.title', 'like', '%'.$srh.'%');
            });
        }        
        if ($question_category_id) {
            $query->where('questions.question_category_id', $question_category_id);
        }
        if ($type) {
            $query->where('questions.type', $type);
        }
        $query->where('questions.employer_id', employerId());
        $query->where('questions.nature', $nature);
        $query->leftJoin('question_categories','question_categories.question_category_id', '=', 'questions.question_id');
        $query->leftJoin('question_answers','question_answers.question_id', '=', 'questions.question_id');
        $query->groupBy('questions.question_id');
        $query->orderBy('questions.created_at', 'DESC');
        $query->skip($offset);
        $query->take($limit);
        $query = $query->get();
        $records = $query ? $query->toArray() : array();


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

    public static function getTotal($nature = 'quiz')
    {
        $srh = getSessionValues($nature.'_questions_search');
        $question_category_id = getSessionValues($nature.'_questions_category_id');
        $type = getSessionValues($nature.'_questions_type');

        $query = Self::whereNotNull('questions.question_id');
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('questions.title', 'like', '%'.$srh.'%');
            });            
        }        
        if ($question_category_id) {
            $query->where('questions.question_category_id', $question_category_id);
        }
        if ($type) {
            $query->where('questions.type', $type);
        }
        $query->where('questions.employer_id', employerId());        
        $query->where('questions.nature', $nature);
        $query->leftJoin('question_categories','question_categories.question_category_id', '=', 'questions.question_id');
        $query->groupBy('questions.question_id');
        $query = $query->get();
        return $query->count();
    }
}