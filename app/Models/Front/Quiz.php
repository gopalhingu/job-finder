<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quiz extends Model
{
    protected $table = 'quizes';
    protected static $tbl = 'quizes';
    protected $primaryKey = 'quiz_id';    
    protected $candidate_id;

    public static function getQuiz($quiz_id)
    {
        $query = DB::table('candidate_quizes')->whereNotNull('candidate_quizes.candidate_quiz_id');
        $query->where('candidate_quizes.candidate_quiz_id', decode($quiz_id));
        $query->select(
            'candidate_quizes.*',
            'jobs.title as job_title',
            'employers.slug as employer_slug',
        );
        $query->leftJoin('employers', 'employers.employer_id', '=', 'candidate_quizes.employer_id');
        $query->leftJoin('jobs', 'jobs.job_id', '=', 'candidate_quizes.job_id');
        $result = $query->first();
        return $result ? objToArr($result) : array();
    }

    public static function getCandidateQuizes()
    {
        $limit = 5;
        $query = DB::table('candidate_quizes')->whereNotNull('candidate_quizes.candidate_quiz_id');
        $query->where('candidate_quizes.candidate_id', candidateSession());
        $query->select(
            'candidate_quizes.*',
            'jobs.title as job_title',
        );
        $query->leftJoin('jobs', 'jobs.job_id', '=', 'candidate_quizes.job_id');
        $query->orderBy('candidate_quizes.created_at', 'DESC');
        $query->groupBy('candidate_quizes.candidate_quiz_id');

        $query = $query->paginate($limit);
        $results = $query->toArray();
        $results = $results ? objToArr($results['data']) : array();
        return array(
            'results' => $results,
            'pagination' => $query->links('candidate'.viewPrfx().'partials.pagination-account')
        );
    }

    public static function updateCandidateQuiz($data)
    {
        //Post Data
        $candidate_quiz_id = $data['quiz'];
        $question = isset($data['question']) ? decode($data['question']) : 0;
        $answer = isset($data['answer']) ? $data['answer'] : array();

        $detail = Self::getQuiz($candidate_quiz_id);
        $quiz = objToArr(json_decode($detail['quiz_data']));
        $questions = $quiz['questions'];
        $current_index = $question == 0 ? 0 : $question-1;
        $current = issetVal($questions, $current_index);

        $answers_data = objToArr(json_decode($detail['answers_data']));
        $submitted_answers = Self::checkAnswers($current, $answer);

        if ($question == 0) {
            $update['started_at'] = date('Y-m-d G:i:s');
        }

        $update['attempt'] = $detail['attempt'] + 1;
        $update['correct_answers'] = $detail['correct_answers'] + $submitted_answers['result'];

        //Updating the answers history (answers_data) with user answers
        $answers_data = $detail['answers_data'] ? json_decode($detail['answers_data']) : array();
        $new[$current_index] = $submitted_answers['user_answers'];
        if (count($submitted_answers['user_answers']) > 1) {
            $user_answers_data = array($submitted_answers['user_answers']);
        } else {
            $user_answers_data = $submitted_answers['user_answers'];
        }
        $new_answers_data = array_merge($answers_data, $user_answers_data);
        $update['answers_data'] = json_encode($new_answers_data);

        DB::table('candidate_quizes')
        ->where('candidate_quizes.candidate_id', candidateSession())
        ->where('candidate_quizes.candidate_quiz_id', decode($candidate_quiz_id))
        ->update($update);

        Self::updateQuizResultInJobApplication($detail);
        Self::updateOverallResultInJobApplication($detail);
    }

    private static function updateQuizResultInJobApplication($data)
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

    private static function updateOverallResultInJobApplication($data)
    {
        DB::table('job_applications')
        ->where('job_applications.candidate_id', $data['candidate_id'])
        ->where('job_applications.job_id', $data['job_id'])
        ->update(array(
            'overall_result' => 
        DB::Raw('ROUND(('.dbprfx().'job_applications.traites_result+'.dbprfx().'job_applications.quizes_result+'.dbprfx().'job_applications.interviews_result)/3)')
        ));
    }

    private static function checkAnswers($current, $answer)
    {
        if ($answer) {
            $correct_answers = array();
            foreach ($current['answers'] as $value) {
                if ($value['is_correct'] == 1) {
                    $correct_answers[] = $value['quiz_question_answer_id'];
                }
            }
            $user_answers = array();
            foreach ($answer as $value) {
                $user_answers[] = (int)decode($value);
            }
            return array(
                'user_answers' => $user_answers,
                'result' => $correct_answers === $user_answers
            );
        } else {
            return array(
                'user_answers' => array(),
                'result' => 0
            );
        }
    }
}