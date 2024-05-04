<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Company\Quiz;

class Job  extends Model
{
    protected $table = 'jobs';
    protected static $tbl = 'jobs';
    protected $primaryKey = 'job_id';

    public static function getJob($column, $value)
    {
        $value = $column == 'job_id' || $column == 'jobs.job_id' ? decode($value) : $value;
        $query = Self::whereNotNull('jobs.job_id');
        $query->where($column, $value);
        $query->where('jobs.company_id', companyId());        
        $query->select(
            'jobs.*',
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_traites.traite_id)) as traites'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_quizes.quiz_id)) as quizes'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_filter_value_assignments.job_filter_id)) as job_filter_ids'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_filter_value_assignments.job_filter_value_id)) as job_filter_value_ids'),
        );
        $query->leftJoin('job_traites', 'job_traites.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_quizes', 'job_quizes.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_filter_value_assignments', 'job_filter_value_assignments.job_id', '=', 'jobs.job_id');
        $query->groupBy('jobs.job_id');
        $result = $query->first();
        return $result ? $result : emptyTableColumns(Self::$tbl, array('traites', 'quizes', 'job_filter_ids', 'job_filter_value_ids'));
    }

    public static function getAll($active = true, $srh = '')
    {
        $query = Self::whereNotNull('jobs.job_id');
        $query->where('jobs.company_id', companyId());
        if ($active) {
            $query->where('status', 1);
        }
        if ($srh) {
            $query->where('username', 'like', $srh);
        }
        $query = $query->get();
        return $query ? $query->toArray() : array();
    }

    public static function getTotalJobs($id = '')
    {
        $query = Self::whereNotNull('jobs.job_id');
        $query->where('status', 1);
        $query->where('company_id', companyId());
        if ($id) {
            $query->where('jobs.job_id', '!=', decode($id));
        }
        return $query->get()->count();
    }

    public static function getPopularJobs($data)
    {
        //Setting session for every parameter of the request
        setSessionValues($data);
        $limit = settingEmp('charts_count_on_dashboard');

        $applications_count = getSessionValues('applied_check');
        $favorites_count = getSessionValues('favorited_check');
        $referred_count = getSessionValues('referred_check');

        $query = Self::whereNotNull('jobs.job_id');
        $query->where('jobs.status', 1);
        $query->select(
            'jobs.title as label',
            DB::Raw('COUNT(DISTINCT(CONCAT('.dbprfx().'job_applications.job_application_id))) as applications_count'),
            DB::Raw('COUNT(DISTINCT(CONCAT('.dbprfx().'job_favorites.job_id,"-",'.dbprfx().'job_favorites.candidate_id))) as favorites_count'),
            DB::Raw('COUNT(DISTINCT(CONCAT('.dbprfx().'job_referred.job_id,"-",'.dbprfx().'job_referred.candidate_id))) as referred_count'),
        );
        $query->where('jobs.company_id', companyId());
        $query->leftJoin('job_favorites', 'job_favorites.job_id', '=' ,'jobs.job_id');
        $query->leftJoin('job_applications', 'job_applications.job_id', '=' ,'jobs.job_id');
        $query->leftJoin('job_referred', 'job_referred.job_id', '=' ,'jobs.job_id');
        $query->groupBy('jobs.job_id');
        $query->orderBy('jobs.job_id', 'ASC');
        $query->skip(0);
        $query->take($limit);
        $result = $query->get();
        $consolidated = array();
        foreach ($result as $key => $value) {
            $total = 0;
            if ($applications_count) {
                $total = $total + $value->applications_count;
            }
            if ($favorites_count) {
                $total = $total + $value->favorites_count;
            }
            if ($referred_count) {
                $total = $total + $value->referred_count;
            }
            $consolidated[] = array('label' => $value->label, 'value' => $total);
        }
        return json_encode($consolidated);
    }

    public static function storeJob($data, $edit = null)
    {
        //Replacing &nbsp with space
        $string = htmlentities($data['description'], null, 'utf-8');
        $data['description'] = str_replace("&nbsp;", " ", $string);
        $data['description'] = html_entity_decode($data['description']);

        //Separating custom values
        $custom_field_ids = isset($data['custom_field_ids']) ? $data['custom_field_ids'] : array();
        $labels = isset($data['labels']) ? $data['labels'] : array();
        $values = isset($data['values']) ? $data['values'] : array();
        $customFields = array('custom_field_id' => $custom_field_ids, 'label' => $labels, 'value' => $values);

        //Separting traites and quizes and filters
        $traites = isset($data['traites']) ? $data['traites'] : array();
        $quizes = isset($data['quizes']) ? $data['quizes'] : array();
        $filters = isset($data['filters']) ? $data['filters'] : array();

        //Removing variables
        unset($data['traites'], $data['quizes'], $data['labels'], $data['values'], $data['custom_field_ids'], $data['filters'], $data['_token'], $data['job_id']);

        $data['company_id'] = companyId();
        $data['department_id'] = decode($data['department_id']);
        $data['combined_filters'] = $filters ? makeCombined(decodeArray($filters)) : ''; //For filters search on front/candidate.
        $data['slug'] = Self::getSlug($data['title'], $data['slug'], $edit);

        if ($edit) {
            $edit = decode($edit);
            Self::where('job_id', $edit)->update($data);
            Self::insertTraites($traites, $edit);
            Self::insertQuizes($quizes, $edit);
            Self::insertCustomFields($customFields, $edit);
            Self::assignJobFilterValues($filters, $edit);
            $id = $edit;
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['status'] = 1;
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            Self::insertTraites($traites, $id);
            Self::insertQuizes($quizes, $id);
            Self::insertCustomFields($customFields, $id);
            Self::assignJobFilterValues($filters, $id);
        }
        
        return encode($id);
    }

    public static function insertTraites($traites, $job_id)
    {
        //First deleting
        DB::table('job_traites')->where('job_id', $job_id)->delete();

        //Getting traites for new
        $traites = DB::table('traites')->whereIn('traite_id', ($traites ? decodeArray($traites) : array(0)))->get();
        $traites = $traites ? objToArr($traites->toArray()) : array();

        //Inserting new traites
        foreach ($traites as $key => $value) {
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['title'] = $value['title'];
            $data['traite_id'] = $value['traite_id'];
            $data['job_id'] = $job_id;
            $data['company_id'] = companyId();
            DB::table('job_traites')->insert($data);
        }
    }

    public static function insertQuizes($quizes, $job_id)
    {
        //First deleting
        DB::table('job_quizes')->where('job_id', $job_id)->delete();

        //Second inserting quiz with quiz data
        foreach ($quizes as $quiz_id) {
            $quiz = Quiz::getCompleteQuiz($quiz_id);
            $data['quiz_data'] = json_encode($quiz);
            $data['job_id'] = $job_id;
            $data['quiz_id'] = $quiz['quiz']['quiz_id'];
            $data['quiz_title'] = $quiz['quiz']['title'];
            $data['total_questions'] = count($quiz['questions']);
            $data['allowed_time'] = $quiz['quiz']['allowed_time'];
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['company_id'] = companyId();
            DB::table('job_quizes')->insert($data);
        }
    }

    public static function insertCustomFields($customFields, $job_id)
    {
        $data = arrangeSections($customFields);
        foreach ($data as $d) {
            $d['company_id'] = companyId();
            if ($d['custom_field_id']) {
                $d['custom_field_id'] = decode($d['custom_field_id']);
                DB::table('job_custom_fields')
                ->where('job_custom_fields.custom_field_id', $d['custom_field_id'])
                ->update($d);
            } else {
                unset($d['custom_field_id']);
                $d['job_id'] = $job_id;
                DB::table('job_custom_fields')->insert($d);
            }
        }
    }

    public static function assignJobFilterValues($filters, $job_id)
    {
        $all_value_ids = array();
        foreach ($filters as $job_filter_id => $job_filter_value_ids) {
            $job_filter_detail = JobFilter::getJobFilter('job_filters.job_filter_id', $job_filter_id);
            foreach ($job_filter_value_ids as $job_filter_value_id) {
                $job_filter_value_id = decode($job_filter_value_id);
                $existing_filter = Self::checkExistingJobFilter($job_filter_value_id, $job_id);
                if ($job_filter_value_id && !$existing_filter) {
                    $insert['job_id'] = $job_id;
                    $insert['job_filter_id'] = decode($job_filter_id);
                    $insert['job_filter_value_id'] = $job_filter_value_id;
                    $insert['company_id'] = $job_filter_detail['company_id'];
                    DB::table('job_filter_value_assignments')->insert($insert);
                }
            }
            $all_value_ids = array_merge($all_value_ids, $job_filter_value_ids);
        }

        //Deleting any if not in input
        if ($all_value_ids) {
            DB::table('job_filter_value_assignments')
            ->where('job_id', $job_id)
            ->whereNotIn('job_filter_value_id', decodeArray($all_value_ids))
            ->delete();
        }
    }

    private static function checkExistingJobFilter($job_filter_value_id, $job_id)
    {
        $result = DB::table('job_filter_value_assignments')
        ->where('company_id', companyId())
        ->where('job_id', $job_id)
        ->where('job_filter_value_id', $job_filter_value_id)
        ->count();
        return $result > 0 ? true : false;
    }

    public static function changeStatus($job_id, $status)
    {
        Self::where('job_id', decode($job_id))->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function changeFollowStatus($job_follow_id, $job_follow_status)
    {
        DB::table('job_follow')->where('id', decode($job_follow_id))->update(array('status' => ($job_follow_status == 1 ? 0 : 1)));
    }

    public static function remove($job_id)
    {
        $job_id = decode($job_id);

        //First remove job
        DB::table('jobs')->where(array('job_id' => $job_id))->delete();

        //Second remove job quizes
        DB::table('job_quizes')->where(array('job_id' => $job_id))->delete();
        
        //Third remove job traites
        DB::table('job_traites')->where(array('job_id' => $job_id))->delete();

        //Forth remove custom fields
        DB::table('job_custom_fields')->where(array('job_id' => $job_id))->delete();

        //Fifth remove job applications
        DB::table('job_applications')->where(array('job_id' => $job_id))->delete();

        //Sixth remove job favorites
        DB::table('job_favorites')->where(array('job_id' => $job_id))->delete();

        //Seventh remove job referred
        DB::table('job_referred')->where(array('job_id' => $job_id))->delete();

        //Eighth remove candidate interviews
        DB::table('candidate_interviews')->where(array('job_id' => $job_id))->delete();

        //Ninth remove candidate quizes
        DB::table('candidate_quizes')->where(array('job_id' => $job_id))->delete();

        //Tenth remove job filter value assignments
        DB::table('job_filter_value_assignments')->where(array('job_id' => $job_id))->delete();
    }

    public static function valueExist($field, $value, $edit = false)
    {
        $value = $field == 'job_id' || $field == 'jobs.job_id' ? decode($value) : $value;
        $query = Self::whereNotNull('jobs.job_id');
        $query->where($field, $value);
        $query->where('company_id', companyId());
        if ($edit) {
            $query->where('job_id', '!=', decode($edit));
        }
        return $query->get()->count() > 0 ? true : false;
    }

    public static function getFields($job_id)
    {
        $result = DB::table('job_custom_fields')->where('job_id', decode($job_id))->get();
        return $result;
    }

    public static function getJobsForCSV($ids)
    {
        $ids = explode(',', $ids);
        $ids = decodeArray($ids);
        $query = Self::whereNotNull('jobs.job_id');
        $query->select(
            'jobs.*',
            'departments.title as department',
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_applications.job_id)) as applications_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_favorites.job_id)) as favorites_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_referred.job_id)) as referred_count'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_traites.title)) as traites'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_filter_values.title) SEPARATOR ",") as job_filter_values')
        );
        $query->whereIn('jobs.job_id', $ids);
        $query->leftJoin('departments', 'departments.department_id', '=' ,'jobs.department_id');
        $query->leftJoin('job_applications', 'job_applications.job_id', '=' ,'jobs.job_id');
        $query->leftJoin('job_favorites', 'job_favorites.job_id', '=' ,'jobs.job_id');
        $query->leftJoin('job_referred', 'job_referred.job_id', '=' ,'jobs.job_id');
        $query->leftJoin('job_traites', 'job_traites.job_id', '=' ,'jobs.job_id');
        $query->leftJoin('job_filter_value_assignments', 'job_filter_value_assignments.job_id', '=' ,'jobs.job_id');
        $query->leftJoin('job_filter_values', 'job_filter_values.job_filter_value_id', '=', 'job_filter_value_assignments.job_filter_value_id');
        $query->groupBy('jobs.job_id');
        return $query->get();
    }    

    public static function removeCustomField($custom_field_id)
    {
        DB::table('job_custom_fields')->where(array('custom_field_id' => decode($custom_field_id)))->delete();
    }    

    public static function jobsList($request)
    {
        $columns = array(
            "",
            "jobs.title",
            "departments.department_id",
            "",
            "applications_count",
            "favorites_count",
            "referred_count",
            "traites_count",
            "jobs.created_at",
            "jobs.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('jobs.job_id');
        $query->select(
            'jobs.job_ids',
            'jobs.department_id',
            'jobs.title',
            'jobs.status',
            'jobs.created_at',
            'departments.title as department',
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_applications.job_application_id)) as applications_count'),
            DB::Raw('COUNT(DISTINCT(CONCAT('.dbprfx().'job_favorites.candidate_id, '.dbprfx().'job_favorites.job_id))) as favorites_count'),
            DB::Raw('COUNT(DISTINCT(CONCAT('.dbprfx().'job_referred.candidate_id, '.dbprfx().'job_referred.job_id))) as referred_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_traites.traite_id)) as traites_count'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_filter_values.title) SEPARATOR ",") as job_filter_values'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'job_filter_value_assignments.job_filter_id, "-", '.dbprfx().'job_filter_value_assignments.job_filter_value_id))) AS combined')
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('jobs.title', 'like', '%'.$srh.'%')
                ->orWhere('jobs.description', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('jobs.status', $request['status']);
        }
        if (isset($request['department']) && $request['department'] != '') {
            $query->where('departments.department_id', decode($request['department']));
        }
        $combined = array();
        if (isset($request['job_filters'])) {
            $job_filter_ids = array();
            $job_filter_value_ids = array();
            foreach ($request['job_filters'] as $job_filter_id => $job_filter_value_id) {
                if ($job_filter_id && $job_filter_value_id) {
                    $combined[] = decode($job_filter_id).'-'.decode($job_filter_value_id);
                    $job_filter_ids[] = $job_filter_id;
                    $job_filter_value_ids[] = $job_filter_value_id;
                }
            }
            if ($job_filter_ids && $job_filter_value_ids) {
                $query->where(function($q) use($srh, $job_filter_ids, $job_filter_value_ids) {
                    $q->whereIn('job_filter_value_assignments.job_filter_id', decodeArray($job_filter_ids));
                    $q->whereIn('job_filter_value_assignments.job_filter_value_id', decodeArray($job_filter_value_ids));
                });
            }
        }
        $query->where('jobs.company_id', companyId());
        $query->leftJoin('departments', 'departments.department_id', '=', 'jobs.department_id');
        $query->leftJoin('job_applications', 'job_applications.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_favorites', 'job_favorites.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_referred', 'job_referred.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_traites', 'job_traites.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_filter_value_assignments', 'job_filter_value_assignments.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_filter_values', 'job_filter_values.job_filter_value_id', '=', 'job_filter_value_assignments.job_filter_value_id');
        $query->groupBy('jobs.job_id');
        
        //Enabling multi cross relationed filter search
        if (isset($request['job_filters']) && $combined) {
            $combined = combinationsOfArray($combined, count($combined));
            $c = 1;
            foreach ($combined as $comb) {
                if ($c == 1) {
                    $query->havingRaw('combined', [$comb]);
                } else {
                    $query->orHavingRaw('combined', [$comb]);
                }
                $c++;
            }
        }

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
    

    public static function getAllValues($request)
    {
        $columns = array(
            "",
            "jobs.title",
            "departments.department_id",
            "",
            "applications_count",
            "favorites_count",
            "referred_count",
            "traites_count",
            "jobs.created_at",
            "jobs.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('jobs.job_id');
        $query->select(
            'jobs.job_id',
            'jobs.department_id',
            'jobs.title',
            'jobs.status',
            'jobs.created_at',
            'departments.title as department',
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_applications.job_application_id)) as applications_count'),
            DB::Raw('COUNT(DISTINCT(CONCAT('.dbprfx().'job_favorites.candidate_id, '.dbprfx().'job_favorites.job_id))) as favorites_count'),
            DB::Raw('COUNT(DISTINCT(CONCAT('.dbprfx().'job_referred.candidate_id, '.dbprfx().'job_referred.job_id))) as referred_count'),
            DB::Raw('COUNT(DISTINCT('.dbprfx().'job_traites.traite_id)) as traites_count'),
            DB::Raw('GROUP_CONCAT(DISTINCT('.dbprfx().'job_filter_values.title) SEPARATOR ",") as job_filter_values'),
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'job_filter_value_assignments.job_filter_id, "-", '.dbprfx().'job_filter_value_assignments.job_filter_value_id))) AS combined')
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('jobs.title', 'like', '%'.$srh.'%')
                ->orWhere('jobs.description', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('jobs.status', $request['status']);
        }
        if (isset($request['department']) && $request['department'] != '') {
            $query->where('departments.department_id', decode($request['department']));
        }
        $combined = array();
        if (isset($request['job_filters'])) {
            $job_filter_ids = array();
            $job_filter_value_ids = array();
            foreach ($request['job_filters'] as $job_filter_id => $job_filter_value_id) {
                if ($job_filter_id && $job_filter_value_id) {
                    $combined[] = decode($job_filter_id).'-'.decode($job_filter_value_id);
                    $job_filter_ids[] = $job_filter_id;
                    $job_filter_value_ids[] = $job_filter_value_id;
                }
            }
            if ($job_filter_ids && $job_filter_value_ids) {
                $query->where(function($q) use($srh, $job_filter_ids, $job_filter_value_ids) {
                    $q->whereIn('job_filter_value_assignments.job_filter_id', decodeArray($job_filter_ids));
                    $q->whereIn('job_filter_value_assignments.job_filter_value_id', decodeArray($job_filter_value_ids));
                });
            }
        }
        $query->where('jobs.company_id', companyId());
        $query->leftJoin('departments', 'departments.department_id', '=', 'jobs.department_id');
        $query->leftJoin('job_applications', 'job_applications.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_favorites', 'job_favorites.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_referred', 'job_referred.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_traites', 'job_traites.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_filter_value_assignments', 'job_filter_value_assignments.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_filter_values', 'job_filter_values.job_filter_value_id', '=', 'job_filter_value_assignments.job_filter_value_id');
        // $query->leftJoin('job_follow', 'job_follow.job_id', '=', 'jobs.job_id');
        $query->groupBy('jobs.job_id');
        
        //Enabling multi cross relationed filter search
        if (isset($request['job_filters']) && $combined) {
            $combined = combinationsOfArray($combined, count($combined));
            $c = 1;
            foreach ($combined as $comb) {
                if ($c == 1) {
                    $query->havingRaw('combined', [$comb]);
                } else {
                    $query->orHavingRaw('combined', [$comb]);
                }
                $c++;
            }
        }

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

    // public static function getAllValues($id)
    // {
    //     $columns = array(
    //         "",
    //         "jobs.title",
    //         "",
    //         "jobs.created_at",
    //         "jobs.status",
    //     );
    //     $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
    //     $orderDirection = $request['order'][0]['dir'];
    //     $srh = $request['search']['value'];
    //     $limit = $request['length'];
    //     $offset = $request['start'];

    //     // $query = DB::table('jobs')->where('job_id', decode($id))->get();
    //     $decodedId = decode($id);
    //     $result = DB::table('jobs')
    //             ->where('jobs.job_id', $decodedId)
    //             ->select('jobs.*')
    //             ->leftJoin('job_follow', 'job_follow.job_id', '=', 'jobs.job_id')
    //             ->groupBy('jobs.job_id')
    //             ->get();

    //     $result = $result ? $result->toArray() : array();

    //     $result = array(
    //         'data' => Self::prepareDataForJobFollowTable($result),
    //         'recordsTotal' => Self::getTotal(),
    //         'recordsFiltered' => Self::getTotal($srh, $request),
    //     );

    //     return $result;
    // }

    
    public static function getAllValues($request)
    {
        $columns = array(
            "",
            "jobs.title",
            "",
            "jobs.created_at",
            "job_follow_1.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];
        $jobid = decode($request['jobid']);

        $query = Self::whereNotNull('jobs.job_id');
        $query->select(
            "employers.first_name",
            "job_follow_1.status as jobFollowStatus",
            "job_follow_1.id as jobFollowId",
            'jobs.job_id',
            'jobs.title',
            'jobs.status',
            'jobs.created_at',
        );
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('employers.first_name', 'like', '%'.$srh.'%');
            });
        }
        
        if (isset($request['jobFollowStatus']) && $request['jobFollowStatus'] != '') {
            $query->where('job_follow_1.status', $request['jobFollowStatus']);
        }
       
        $combined = array();
      
        $query->where('jobs.company_id', companyId());
        $query->where('jobs.job_id', $jobid);
        $query->leftJoin('job_follow as job_follow_1', 'job_follow_1.job_id', '=', 'jobs.job_id');
        $query->leftJoin('employers', 'employers.employer_id', '=', 'job_follow_1.employer_id');
        $query->groupBy('jobs.job_id');
        
        //Enabling multi cross relationed filter search
        if (isset($request['job_filters']) && $combined) {
            $combined = combinationsOfArray($combined, count($combined));
            $c = 1;
            foreach ($combined as $comb) {
                if ($c == 1) {
                    $query->havingRaw('combined', [$comb]);
                } else {
                    $query->orHavingRaw('combined', [$comb]);
                }
                $c++;
            }
        }

        $query->orderBy($orderColumn, $orderDirection);
        $query->skip($offset);
        $query->take($limit);
        $result = $query->get();
        $result = $result ? $result->toArray() : array();

        $result = array(
            'data' => Self::prepareDataJobFollowForTable($result),
            'recordsTotal' => Self::getTotal(),
            'recordsFiltered' => Self::getTotal($srh, $request),
        );

        return $result;
    }

    private static function prepareDataJobFollowForTable($jobs)
    {
        $sorted = array();
        foreach ($jobs as $j) {
            $j = objToArr($j);
            $id = encode($j['job_id']);
            $jobFollowId = encode($j['jobFollowId']);
            
            if ($j['jobFollowStatus'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($j['first_name']),
                date('d M, Y', strtotime($j['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-job-follow-status" data-status="'.$j['jobFollowStatus'].'" data-id="'.$jobFollowId.'">'.$button_text.'</button>',
            );
        }
        return $sorted;
    }

    public static function getTotal($srh = false, $request = '')
    {
        $query = Self::whereNotNull('jobs.job_id');
        $query->select(
            'jobs.job_id',
            DB::Raw('GROUP_CONCAT(DISTINCT(CONCAT('.dbprfx().'job_filter_value_assignments.job_filter_id, "-", '.dbprfx().'job_filter_value_assignments.job_filter_value_id))) AS combined')
        );        
        if ($srh) {
            $query->where('jobs.title', 'like', '%'.$srh.'%')
            ->orWhere('jobs.description', 'like', '%'.$srh.'%');            
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('jobs.status', $request['status']);
        }
        if (isset($request['department']) && $request['department'] != '') {
            $query->where('departments.department_id', decode($request['department']));
        }


        $combined = array();        
        if (isset($request['job_filters'])) {
            $job_filter_ids = array();
            $job_filter_value_ids = array();
            foreach ($request['job_filters'] as $job_filter_id => $job_filter_value_id) {
                if ($job_filter_id && $job_filter_value_id) {
                    $job_filter_ids[] = $job_filter_id;
                    $job_filter_value_ids[] = $job_filter_value_id;
                    $combined[] = decode($job_filter_id).'-'.decode($job_filter_value_id);
                }
            }
            if ($job_filter_ids && $job_filter_value_ids) {
                $query->where(function($q) use($srh, $job_filter_ids, $job_filter_value_ids) {
                    $q->whereIn('job_filter_value_assignments.job_filter_id', decodeArray($job_filter_ids));
                    $q->whereIn('job_filter_value_assignments.job_filter_value_id', decodeArray($job_filter_value_ids));
                });
            }
        }
        $query->where('jobs.company_id', companyId());        
        $query->leftJoin('departments', 'departments.department_id', '=', 'jobs.department_id');
        $query->leftJoin('job_applications', 'job_applications.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_favorites', 'job_favorites.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_referred', 'job_referred.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_traites', 'job_traites.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_filter_value_assignments', 'job_filter_value_assignments.job_id', '=', 'jobs.job_id');
        $query->leftJoin('job_filter_values', 'job_filter_values.job_filter_value_id', '=', 'job_filter_value_assignments.job_filter_value_id');
        $query->groupBy('jobs.job_id');

        //Enabling multi cross relationed filter search
        if (isset($request['job_filters']) && $combined) {
            $combined = combinationsOfArray($combined, count($combined));
            $c = 1;
            foreach ($combined as $comb) {
                if ($c == 1) {
                    $query->havingRaw('combined', [$comb]);
                } else {
                    $query->orHavingRaw('combined', [$comb]);
                }
                $c++;
            }
        }

        return $query->get()->count();
    }

    private static function prepareDataForTable($jobs)
    {
        $sorted = array();
        foreach ($jobs as $j) {
            $actions = '';
            $j = objToArr($j);
            $id = encode($j['job_id']);
            if ($j['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (empAllowedTo('edit_jobs')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-job" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (empAllowedTo('delete_jobs')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-job" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs get-job-follow-values" data-id="'.$id.'"><i class="fas fa-list"></i></button>
            ';
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($j['title']),
                $j['department'] ? esc_output($j['department']) : '---',
                $j['job_filter_values'] ? esc_output($j['job_filter_values']) : '---',
                $j['applications_count'],
                $j['favorites_count'],
                $j['referred_count'],
                $j['traites_count'],
                date('d M, Y', strtotime($j['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-job-status" data-status="'.$j['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }

    public static function getSlug($title, $slug, $edit)
    {
        $slug = $slug ? $slug : slugify($title);
        $numbers = range(1, 500);
        $edit = decode($edit);
        array_unshift($numbers , '');
        foreach ($numbers as $number) {
            $completeSlug = $slug.($number ? '-'.$number : '');
            $query = Self::where('slug', $completeSlug);
            if ($edit) {
                $query->where('job_id', '!=', $edit);
            }
            $count = $query->get()->count();
            if ($count == 0) {
                return $completeSlug;
            }
        }
    }
}