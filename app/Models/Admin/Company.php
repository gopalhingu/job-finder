<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Company\Job AS CompanyJob;
use App\Models\Company\Quiz AS CompanyQuiz;
use App\Models\Company\Traite AS CompanyTraite;
use App\Models\Admin\JobFilter AS AdminJobFilter;

class Company extends Model
{
    protected $table = 'company';
    protected static $tbl = 'company';
    protected $primaryKey = 'company_id';

    protected $fillable = [
        'company_id',
        'slug',
        'first_name',
        'last_name',
        'companyname',
        'email',
        'password',
        'image',
        'phone1',
        'phone2',
        'city',
        'state',
        'country',
        'address',
        'gender',
        'dob',
        'status',
        'token',
        'created_at',
        'updated_at',
    ];

    public static $noOfEmployees = [
        '0-5',
        '5-10',
        '10-25',
        '25-50',
        '50-100',
        '100-250',
        '250-500',
        '500-1000',
        'More Than 1000'
    ];

    public static $industries = [
        'Advertising',
        'Marketing',
        'Aerospace',
        'Agriculture',
        'Computers and Technology',
        'Construction',
        'Education',
        'Energy',
        'Entertainment',
        'Fashion',
        'Finance',
        'Food and Beverages',
        'Health Care',
        'Hospitality',
        'Manufacturing',
        'Media and News',
        'Mining',
        'Pharmaceutical',
        'Telecommunication',
        'Transportation',
        'Accountancy',
        'Banking',
        'Energy and Utilities',
        'Environment',
        'Law and Legal',
        'Property and Real Estate',
        'Logistics',
    ];

    public static function getCompany($column, $value)
    {
    	$employer = Self::where($column, $value)->first();
    	return $employer ? $employer->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function checkExistingRole($role_id, $employer_id)
    {
        $result = DB::table('employer_roles')->where(array(
            'role_id' => $role_id,
            'employer_id' => $employer_id
        ))->count();
        return ($result > 0) ? true : false;
    }

    public static function storeEmployerRolesBulk($data)
    {
        $roles = $data['roles'];
        $employer_ids = json_decode($data['employer_ids']);
        foreach ($roles as $role_id) {
            foreach ($employer_ids as $employer_id) {
                $existing = Self::checkExistingRole($role_id, $employer_id);
                if (!$existing) {
                    $d['employer_id'] = $employer_id;
                    $d['role_id'] = $role_id;
                    DB::table('employer_roles')->insert($d);
                }
            }
        }
    }

    public static function storeEmployer($data, $edit = null, $image = '', $logo = '')
    {
        $roles = isset($data['roles']) ? $data['roles'] : array();
        unset($data['roles'], $data['employer_id'], $data['_token'], $data['image']);
        if ($image) {
            $data['image'] = $image;
        }
        if ($logo) {
            $data['logo'] = $logo;
        }
        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            $data['slug'] = slugify($data['company']);
            if ($data['password']) {
                $data['password'] = \Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            Self::where('employer_id', $edit)->update($data);
            Self::insertRoles($roles, $edit);
            $id = $edit;
        } else {
            $data['slug'] = slugify($data['company']);
            $data['password'] = \Hash::make($data['password']);
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['updated_at'] = date('Y-m-d G:i:s');
            $data['type'] = 'main';
            $data['employername'] = slugify($data['company']).'-'.curRand();
            $data['status'] = 1;
            Self::insert($data);
            $id = DB::getPdo()->lastInsertId();
            Self::insertRoles($roles, $id);
        }
        return $id;
    }

    private static function insertRoles($data, $id)
    {
        DB::table('employer_roles')->where(array('employer_id' => $id))->delete();
        foreach ($data as $d) {
            DB::table('employer_roles')->insert(array('employer_id' => $id, 'role_id' => $d));
        }
    }

    public static function changeStatus($employer_id, $status)
    {
        Self::where('employer_id', $employer_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($employer_id)
    {
        Self::where(array('employer_id' => $employer_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('employer_id', $ids)->update(array('status' => '1'));
            break;
            case "deactivate":
                Self::whereIn('employer_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function getAll2($active = true, $type = false)
    {
        $query = Self::whereNotNull('employers.employer_id');
        if ($active) {
            $query->where('employers.status', 1);
        }
        if ($type) {
            $query->where('employers.type', 'main');
        }
        $query->select(
            'employers.*',
            DB::Raw('COUNT('.dbprfx().'jobs.job_id) AS count'),
        );
        $query->from(Self::$tbl);
        if (setting('display_jobs_front') == 'employers_without_separate_site') {
            $query->where('memberships.separate_site', 0);
        }        
        $query->leftJoin('jobs', 'jobs.employer_id', '=', 'employers.employer_id');        
        $query->leftJoin('memberships', function($join) {
            $join->on('memberships.employer_id', '=', 'employers.employer_id');
            $join->where('memberships.status', '=', '1');
            $join->where('memberships.expiry', '>', \DB::raw('NOW()'));
        });
        $query->groupBy('employers.employer_id');
        return $query->get();
    }

    public static function getAll($active = true, $limit = '', $type = 'main', $sort = '', $search = '', $industry = '', $no_of_employees = '')
    {
        $query = Self::whereNotNull('employers.employer_id');
        $query->select(
            'employers.*',
            'memberships.separate_site',
            DB::Raw('COUNT('.dbprfx().'jobs.job_id) AS jobs_count'),
        );
        $query->where('employers.type', $type);
        if ($active) {
            $query->where('employers.status', 1);
        }
        if ($sort == 'sort_newer') {
            $query->orderBy('employers.created_at', 'DESC');
        } elseif ($sort == 'sort_older') {
            $query->orderBy('employers.created_at', 'ASC');
        } elseif ($sort == 'sort_recent') {
            $query->orderBy('jobs.created_at', 'DESC');
        } elseif ($sort == 'sort_most') {
            $query->orderBy('jobs_count', 'DESC');
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('employers.company', 'like', '%'.$search.'%');
                $q->orWhere('employers.first_name', 'like', '%'.$search.'%');
                $q->orWhere('employers.last_name', 'like', '%'.$search.'%');
                $q->orWhere('employers.short_description', 'like', '%'.$search.'%');
            });
        }
        if ($industry) {
            $query->where(function($q) use ($industry) {
                $q->where('employers.industry', 'like', '%'.$industry.'%');
            });
        }
        if ($no_of_employees) {
            $query->where(function($q) use ($no_of_employees) {
                $q->where('employers.no_of_employees', 'like', '%'.$no_of_employees.'%');
            });
        }
        $query->groupBy('employers.employer_id');
        $query->leftJoin('jobs', function($join) {
            $join->on('jobs.employer_id', '=', 'employers.employer_id')->where('jobs.last_date', '>=', \DB::raw('NOW()'));
            $join->orOn('jobs.employer_id', '=', 'employers.employer_id')->where('jobs.hide_job_after_last_date', '=', '0');
        });
        $query->leftJoin('memberships', function($join) {
            $join->on('memberships.employer_id', '=', 'employers.employer_id');
            $join->where('memberships.status', '=', '1');
            $join->where('memberships.expiry', '>', \DB::raw('NOW()'));
        });
        if ($limit) {
            return $query->paginate($limit);
        } else {
            $result = $query->get();
            return $result ? objToArr($result->toArray()) : array();
        }
    }

    public static function getForFront($active = true, $limit = '', $orderByCol = '', $orderByDir = '')
    {
        $query = Self::whereNotNull('company.company_id');
        $query->select(
            'company.*',
            'memberships.separate_site',
            DB::Raw('COUNT('.dbprfx().'jobs.job_id) AS jobs_count'),
        );
        $query->leftJoin('jobs','jobs.company_id', '=', 'company.company_id');
        $query->where('company.type', 'main');
        if ($active) {
            $query->where('company.status', 1);
        }
        if ($orderByCol) {
            $query->orderBy($orderByCol, $orderByDir);
        }        
        $query->groupBy('company.company_id');
        $query->leftJoin('memberships', function($join) {
            $join->on('memberships.company_id', '=', 'company.company_id');
            $join->where('memberships.status', '=', '1');
            $join->where('memberships.expiry', '>', \DB::raw('NOW()'));
        });        
        $result = $query->get();
        return $result ? objToArr($result->toArray()) : array();
    }

    public static function employersList($request)
    {
        $columns = array(
            "",
            "",
            "employers.first_name",
            "employers.last_name",
            "employers.email",
            "employers.company",
            "",
            "employers.created_at",
            "employers.status",
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('employers.employer_id');
        $query->where('employers.type', 'main');
        $query->select(
            'employers.*',
            DB::Raw('GROUP_CONCAT('.dbprfx().'roles.title SEPARATOR ", ") as employer_roles'),
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('company', 'like', '%'.$srh.'%');
                $q->orWhere('first_name', 'like', '%'.$srh.'%');
                $q->orWhere('last_name', 'like', '%'.$srh.'%');
                $q->orWhere('email', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('employers.status', $request['status']);
        }
        if (isset($request['role']) && $request['role'] != '') {
            $query->where('employer_roles.role_id', $request['role']);
        }
        $query->leftJoin('employer_roles','employer_roles.employer_id', '=', 'employers.employer_id');
        $query->leftJoin('roles', function($join) {
            $join->on('roles.role_id', '=', 'employer_roles.role_id')->where('roles.type', '=', 'employer');
        });
        $query->groupBy('employers.employer_id');
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
        $query = Self::whereNotNull('employers.employer_id');
        $query->from('employers');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('company', 'like', '%'.$srh.'%');
                $q->orWhere('first_name', 'like', '%'.$srh.'%');
                $q->orWhere('last_name', 'like', '%'.$srh.'%');
                $q->orWhere('email', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('employers.status', $request['status']);
        }
        if (isset($request['role']) && $request['role'] != '') {
            $query->where('employer_roles.role_id', $request['role']);
        }
        $query->leftJoin('employer_roles','employer_roles.employer_id', '=', 'employers.employer_id');
        $query->leftJoin('roles', function($join) {
            $join->on('roles.role_id', '=', 'employer_roles.role_id')->where('roles.type', '=', 'employer');
        });
        $query->where('employers.type', 'main');
        $query->groupBy('employers.employer_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($employers)
    {
        $sorted = array();
        foreach ($employers as $u) {
            $actions = '';
            $id = $u['employer_id'];
            $u = objToArr($u);
            if ($u['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (allowedTo('edit_employer')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-employer" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (allowedTo('delete_employer')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-employer" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            if (allowedTo('login_as_employer')) {
            $actions .= '
                <a target="_blank" href="'.url(route('admin-employers-loginas', array('employer_id' => encode($id), 'user_id' =>  encode(adminSession())))).'" title="'.__('message.login_as_employer').'" class="btn btn-warning btn-xs"><i class="fas fa-external-link-alt"></i></button>
            ';
            }            
            $thumb = employerThumb($u['image']);
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                "<img class='employer-thumb-table' src='".$thumb['image']."' onerror='this.src=\"".$thumb['error']."\"'/>",
                esc_output($u['first_name'], 'html'),
                esc_output($u['last_name'], 'html'),
                esc_output($u['email'], 'html'),
                esc_output($u['company'].' ('.$u['slug'].')', 'html'),
                esc_output($u['employer_roles'], 'html'),
                date('d M, Y', strtotime($u['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-employer-status" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }

    public static function signupsDetail($interval = 'this_month', $table = 'employer')
    {
        if ($table == 'employer') {
            $type = 'employers.employer_id';
            $query = Self::whereNotNull($type);
        } else {
            $type = 'candidates.candidate_id';
            $query = DB::table('candidates')->whereNotNull($type);
        }

        if ($interval == 'this_month') {
            $query->select(
                DB::Raw('CONCAT(DATE_FORMAT(created_at, "%Y"), "-", DATE_FORMAT(created_at, "%m"), "-", DATE_FORMAT(created_at, "%d")) AS labels'),
                DB::Raw('COUNT('.dbprfx().$type.') AS valuess'),
            );
            $query->whereRaw('MONTH(created_at) = MONTH(CURDATE())');
            $query->whereRaw('YEAR(created_at) = YEAR(CURDATE())');
            $query->groupByRaw('DAY(created_at)');
        } elseif ($interval == 'last_month') {
            $query->select(
                DB::Raw('CONCAT(DATE_FORMAT(created_at, "%Y"), "-", DATE_FORMAT(created_at, "%m"), "-", DATE_FORMAT(created_at, "%d")) AS labels'),
                DB::Raw('COUNT('.dbprfx().$type.') AS valuess'),
            );
            $query->whereRaw('MONTH(created_at) = MONTH(CURDATE()) - 1');
            $query->whereRaw('YEAR(created_at) = YEAR(CURDATE())');
            $query->groupByRaw('DAY(created_at)');
        } elseif ($interval == 'this_year') {
            $query->select(
                DB::Raw('CONCAT(DATE_FORMAT(created_at, "%Y"), "-", DATE_FORMAT(created_at, "%m")) AS labels'),
                DB::Raw('COUNT('.dbprfx().$type.') AS valuess'),
            );
            $query->whereRaw('YEAR(created_at) = YEAR(CURDATE())');
            $query->groupByRaw('MONTH(created_at)');
        
        } elseif ($interval == 'last_year') {
            $query->select(
                DB::Raw('CONCAT(DATE_FORMAT(created_at, "%Y"), "-", DATE_FORMAT(created_at, "%m")) AS labels'),
                DB::Raw('COUNT('.dbprfx().$type.') AS valuess'),
            );
            $query->whereRaw('YEAR(created_at) = YEAR(CURDATE()) - 1');
            $query->groupByRaw('MONTH(created_at)');
        }

        $result = $query->get();
        $result = $result ? Self::sortSignupsData($result->toArray(), $interval) : array();
        return $result;
    }

    public static function sortSignupsData($data, $interval)
    {
        $labels = array();
        $values = array();
        $data = objToArr($data);

        if ($interval == 'this_month') {
            $dates = datesOfMonth();
        } elseif ($interval == 'last_month') {
            $dates = datesOfMonth('', date('m')-1);
        } elseif ($interval == 'this_year') {
            $dates = monthsOfYear();
        } elseif ($interval == 'last_year') {
            $dates = monthsOfYear(date('Y')-1);
        }

        //Sorting data from db
        $sorted = array();
        foreach ($data as $d) {
            $sorted[$d['labels']] = $d['valuess'];
        }

        //Populating with dates
        foreach ($dates as $date) {
            $labels[] = $date;
            if (isset($sorted[$date])) {
                $values[] = $sorted[$date];
            } else {
                $values[] = 0.00;
            }
        }

        return array('labels' => $labels, 'values' => $values);
    }     

    public static function getCompanyForCSV($ids)
    {
        $query = Self::whereNotNull('company.employer_id');
        $query->from('company');
        $query->select(
            'company.*'
        );
        $query->whereIn('company.employer_id', explode(',', $ids));
        $query->groupBy('company.employer_id');
        $query->orderBy('company.created_at', 'DESC');
        return $query->get();
    }    

    public static function importCompanySettings($company_id)
    {
        $company = Self::where('company_id', $company_id)->first();
        $app_url = env('APP_URL');
        $com_url = empUrlBySlug($company->slug);
        $bannerText = '<h2>Looking for an exciting career path ?<br>Come, Join Us!</h2>';
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'candidatefinder.com';
        $host = str_replace('www.', '', $host);
        $fromEmail = 'hr@'.$host;
        $col1 = '
            <div class="footer-info">
            <h3>'.$company->company.'</h3>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.<br /></p>
            </div>
        ';
        $col2 = '
            <div class="footer-links">
                <h4>Useful Links</h4>
                <ul>
                    <li><a href="'.$com_url.'">How To Apply</a></li>
                    <li><a href="'.$com_url.'">Latest Jobs</a></li>
                    <li><a href="'.$com_url.'">My Account</a></li>
                    <li><a href="'.$com_url.'">News & Announcements</a></li>
                    <li><a href="'.$com_url.'">Privacy policy</a></li>
                </ul>
            </div>
        ';
        $col3 = '
            <div class="footer-links">
                <h4>Latest Jobs</h4>
                <ul>
                    <li><a href="'.$com_url.'">Marketing Executive</a></li>
                    <li><a href="'.$com_url.'">Accounts Manager</a></li>
                    <li><a href="'.$com_url.'">Computer System Analyst</a></li>
                    <li><a href="'.$com_url.'">Network Administrator</a></li>
                    <li><a href="'.$com_url.'">Project Manager</a></li>
                </ul>
            </div>
        ';
        $col4 = '
            <div class="footer-contact">
            <h4>Contact Us</h4>
            <p>ABC Street<br />
            New York, NY 123456<br />
            United States<br />
            <strong>Phone:</strong> +1 2345 67899 00<br />
            <strong>Email:</strong> info@example.com</p>
            <div class="social-links"><a class="twitter" href="https://www.twitter.com"><i class="fab fa-twitter">&nbsp;</i></a> <a class="facebook" href="https://www.facebook.com"><i class="fab fa-facebook">&nbsp;</i></a> <a class="instagram" href="https://www.instagram.com"><i class="fab fa-instagram">&nbsp;</i></a> <a class="google-plus" href="https://www.google.com"><i class="fab fa-google-plus">&nbsp;</i></a> <a class="linkedin" href="https://www.linkedin.com"><i class="fab fa-linkedin">&nbsp;</i></a></div>
            </div>
        ';

        $cid = $company_id;
        $data = array(
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'site_logo', 'value' => 'identities/site-logo.png'),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'site_banner', 'value' => 'identities/site-breadcrumb-image.jpg',),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'site_favicon', 'value' => 'identities/site-favicon.png',),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'site_name', 'value' => '',),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'site_keywords', 'value' => '',),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'site_description', 'value' => '',),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'banner_text', 'value' => $bannerText,),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'before_blogs_text', 'value' => '',),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'after_blogs_text', 'value' => '',),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'before_how_text', 'value' => '',),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'after_how_text', 'value' => '',),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'footer_col_1', 'value' => $col1,),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'footer_col_2', 'value' => $col2,),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'footer_col_3', 'value' => $col3,),
            array('company_id' => $cid, 'category' => 'Branding', 'key' => 'footer_col_4', 'value' => $col4,),
          
            array('company_id' => $cid, 'category' => 'Emails', 'key' => 'candidate_job_app', 'value' => getTextFromFile('candidate-job-application.txt')),
            array('company_id' => $cid, 'category' => 'Emails', 'key' => 'employer_job_app', 'value' => getTextFromFile('employer-job-application.txt')),
            array('company_id' => $cid, 'category' => 'Emails', 'key' => 'employer_interview_assign', 'value' => getTextFromFile('employer-interview-assign.txt')),
            array('company_id' => $cid, 'category' => 'Emails', 'key' => 'candidate_interview_assign', 'value' => getTextFromFile('candidate-interview-assign.txt')),
            array('company_id' => $cid, 'category' => 'Emails', 'key' => 'candidate_quiz_assign', 'value' => getTextFromFile('candidate-quiz-assign.txt')),
            array('company_id' => $cid, 'category' => 'Emails', 'key' => 'team_creation', 'value' => getTextFromFile('team-creation.txt')),

            array('company_id' => $cid, 'category' => 'General', 'key' => 'admin_email', 'value' => 'admin@'.$host,),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'from_email', 'value' => $fromEmail,),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'jobs_per_page', 'value' => '10',),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'blogs_per_page', 'value' => '10',),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'charts_count_on_dashboard', 'value' => '10',),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'default_landing_page', 'value' => 'home',),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'enable_home_banner', 'value' => 'yes',),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'home_how_it_works', 'value' => 'yes',),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'home_department_section', 'value' => 'yes',),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'home_blogs_section', 'value' => 'yes',),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'css', 'value' => '',),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'display_jobs_to_only_logged_in_users', 'value' => 'no',),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'display_jobs_last_date', 'value' => 'yes',),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'display_admin_created_departments', 'value' => 'yes',),
            array('company_id' => $cid, 'category' => 'General', 'key' => 'display_admin_created_job_filters', 'value' => 'yes',),
        );

        foreach ($data as $d) {
            $d['value'] = str_replace('"', "'", $d['value']);
            $result = DB::table('settings')->where(array('key' => $d['key'], 'company_id' => $d['company_id']))->first();
            if (!$result) {
                DB::table('settings')->insert($d);
            }
        }
    }

    public static function importCompanyDummyData($company_id)
    {   
        //Getting essentials
        $company = Self::where('company_id', $company_id)->first();
        if (!$company || $company->type == 'team') {
            return false;
        }
        $dataDir = storage_path('/app/'.config('constants.upload_dirs.main').'/dummy-data/');

        //Creating company directory if not exists
        $companyDir = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.companys').$company->slug.'/');

        $emti = '';
        $date = date('Y-m-d G:i:s');
        $additional = array('created_at' => $date, 'updated_at' => $date, 'company_id' => $company_id);
        $jd = getTextFromFile('job.txt');
        $dids = array();
        $jids = array();
        $bcid = '';

        //1 : Importing departments
        $data = array(
            array('title' => 'Accounting'.$emti, 'image' => 'accounting.png', 'status' => 1,),
            array('title' => 'Administration'.$emti, 'image' => 'administration.png', 'status' => 1,),
            array('title' => 'Information Technology'.$emti, 'image' => 'information-tech.png', 'status' => 1,),
            array('title' => 'Human Resource'.$emti, 'image' => 'human-resource.png', 'status' => 1,),
            array('title' => 'Finance'.$emti, 'image' => 'finance.png', 'status' => 1,),
            array('title' => 'Marketing'.$emti, 'image' => 'marketing.png', 'status' => 1,),
            array('title' => 'Advertising'.$emti, 'image' => 'advertising.png', 'status' => 1,),
            array('title' => 'Sales'.$emti, 'image' => 'sales.png', 'status' => 1,),
            array('title' => 'Business Development'.$emti, 'image' => 'business-dev.png', 'status' => 1,),
            array('title' => 'Customer Service'.$emti, 'image' => 'customer-service.png', 'status' => 1,),
            array('title' => 'Procurement'.$emti, 'image' => 'procurement.png', 'status' => 1,),
            array('title' => 'Maintenance'.$emti, 'image' => 'maintenance.png', 'status' => 1,),
        );
        foreach ($data as $d) {
            $condition = array('company_id' => $company_id, 'title' => $d['title']);
            $result = DB::table('departments')->where($condition)->first();
            if (!$result) {
                $original_image = $d['image'];
                $d['image'] = config('constants.upload_dirs.companys').$company->slug.'/'.config('constants.upload_dirs.departments').$d['image'];
                DB::table('departments')->insert(array_merge($d, $additional));
                $dids[] = DB::getPdo()->lastInsertId();

                //Copying image from uploads/dummy-data to newly created company folder.
                createDirectoryIfNotExists($companyDir.config('constants.upload_dirs.departments').$original_image);
                $cr = copy($dataDir.$original_image, $companyDir.config('constants.upload_dirs.departments').'/'.$original_image);
            }
        }

        //2 : Importing Jobs
        $data = array(
            array('title' => 'Marketing Executive'.$emti, 'created_at' => '2023-01-15 23:00:00'),
            array('title' => 'Accounts Manager'.$emti, 'created_at' => '2023-01-16 23:00:00'),
            array('title' => 'Computer System Analyst'.$emti, 'created_at' => '2023-01-17 23:00:00'),
            array('title' => 'Network Administrator'.$emti, 'created_at' => '2023-01-18 23:00:00'),
            array('title' => 'Project Manager'.$emti, 'created_at' => '2023-01-19 23:00:00'),
            array('title' => 'HR Business Partner'.$emti, 'created_at' => '2023-01-20 23:00:00'),
            array('title' => 'Quality Supervisor'.$emti, 'created_at' => '2023-01-21 23:00:00'),
            array('title' => 'Sr. Software Engineer'.$emti, 'created_at' => '2023-01-22 23:00:00'),
            array('title' => 'Support Staff'.$emti, 'created_at' => '2023-01-23 23:00:00'),
            array('title' => 'Warehouse Supervisor'.$emti, 'created_at' => '2023-01-24 23:00:00'),
            array('title' => 'Legal Advisor'.$emti, 'created_at' => '2023-01-25 23:00:00'),
            array('title' => 'CTO'.$emti, 'created_at' => '2023-01-26 23:00:00'),
        );
        $dids = $dids ? $dids : array(0,1,2,3,4,5,6,7,8,9,10,11);
        foreach ($data as $d) {
            $condition = array('company_id' => $company_id, 'title' => $d['title']);
            $result = DB::table('jobs')->where($condition)->first();
            if (!$result) {
                $job_title = $data[rand(0,11)]['title'];
                $d['slug'] = CompanyJob::getSlug($job_title, '', '');
                $d['department_id'] = array_rand($dids);
                $d['description'] = $jd;
                $d['company_id'] = $company_id;
                $d['title'] = $job_title;
                DB::table('jobs')->insert($d);
                $jids[] = DB::getPdo()->lastInsertId();
            }
        }

        //3 : Importing blog categories
        $data = array(
            array('title' => 'Category 1'.$emti, 'status' => 1,),
            array('title' => 'Category 2'.$emti, 'status' => 1,),
            array('title' => 'Category 3'.$emti, 'status' => 1,),
        );
        foreach ($data as $d) {
            $condition = array('company_id' => $company_id, 'title' => $d['title']);
            $result = DB::table('blog_categories')->where($condition)->first();
            if (!$result) {
                DB::table('blog_categories')->insert(array_merge($d, $additional));
                $bcid = DB::getPdo()->lastInsertId();
            }
        }

        //4 : Importing blogs
        $data = array(
            array('title' => 'Frequently Asked Questions'.$emti, 'description' => $jd, 'blog_category_id' => $bcid, 'status' => 1, 'image' => 'faqs.jpg'),
            array('title' => 'How to Apply'.$emti, 'description' => $jd, 'blog_category_id' => $bcid, 'status' => 1, 'image' => 'how-to-apply.jpg'),
            array('title' => 'Quiz Timings'.$emti, 'description' => $jd, 'blog_category_id' => $bcid, 'status' => 1, 'image' => 'quiz-timings.jpg'),
            array('title' => 'Privacy Policy'.$emti, 'description' => $jd, 'blog_category_id' => $bcid, 'status' => 1, 'image' => 'privacy-policy.jpg'),
        );
        foreach ($data as $d) {
            $condition = array('company_id' => $company_id, 'title' => $d['title']);
            $result = DB::table('blogs')->where($condition)->first();
            $original_image = $d['image'];
            if (!$result) {
                $d['image'] = config('constants.upload_dirs.companys').$company->slug.'/'.config('constants.upload_dirs.blogs').$d['image'];
                DB::table('blogs')->insert(array_merge($d, $additional));

                //Copying image from uploads/dummy-data to newly created company folder.
                createDirectoryIfNotExists($companyDir.config('constants.upload_dirs.blogs').$original_image);
                $cr = copy($dataDir.$original_image, $companyDir.config('constants.upload_dirs.blogs').'/'.$original_image);
            }
        }

        //5 : Importing company roles
        $data = array(
            array('title' => 'Admin',),
            array('title' => 'Interviewer',),
            array('title' => 'Blog Manager',),
        );
        foreach ($data as $d) {
            $condition = array('type' => 'company', 'company_id' => $company_id, 'title' => $d['title']);
            $result = DB::table('roles')->where($condition)->first();
            if (!$result) {
                DB::table('roles')->insert(array_merge($condition, $additional));
            }
        }


        //6a : Importing Question Categories
        $question_category_id = '';
        $data = array(
            array('title' => 'General',),
        );
        foreach ($data as $d) {
            $condition = array('company_id' => $company_id, 'title' => $d['title']);
            $result = DB::table('question_categories')->where($condition)->first();
            if (!$result) {
                DB::table('question_categories')->insert(array_merge($condition, $additional));
                $question_category_id = DB::getPdo()->lastInsertId();
            }
        }

        //6b : Importing Questions
        $data = array(
            array(
                'quiz_id' => 1,
                'title' => 'A computer basically constitutes of _______ integral components',
                'type' => 'radio',
                'answers' => array(
                    array('title' => 'Two', 'is_correct' => '0'),
                    array('title' => 'Four', 'is_correct' => '0'),
                    array('title' => 'Three', 'is_correct' => '1'),
                    array('title' => 'Eight', 'is_correct' => '0'),
                ),
            ),
            array(
                'quiz_id' => 1,
                'title' => 'Computers have secondary storage devices known as',
                'type' => 'radio',
                'answers' => array(
                    array('title' => 'ALU', 'is_correct' => '0'),
                    array('title' => 'Auxiliary Storage', 'is_correct' => '1'),
                    array('title' => 'CPU', 'is_correct' => '0'),
                    array('title' => 'None of the above', 'is_correct' => '0'),
                ),
            ),
            array(
                'quiz_id' => 1,
                'title' => 'Computers have secondary storage devices known as',
                'type' => 'radio',
                'answers' => array(
                    array('title' => 'ALU', 'is_correct' => '0'),
                    array('title' => 'Auxiliary Storage', 'is_correct' => '1'),
                    array('title' => 'CPU', 'is_correct' => '0'),
                    array('title' => 'None of the above', 'is_correct' => '0'),
                ),
            ),
        );
        foreach ($data as $d) {
            $condition = array('company_id' => $company_id, 'title' => $d['title']);
            $result = DB::table('questions')->where($condition)->first();
            if (!$result) {
                $condition['question_category_id'] = $question_category_id;
                $answers = $d['answers'];
                unset($d['answers']);
                DB::table('questions')->insert(array_merge($condition, $additional));
                $id = DB::getPdo()->lastInsertId();
                foreach ($answers as $answer) {
                    $answer['question_id'] = $id;
                    DB::table('question_answers')->insert($answer);
                }
            }
        }

        //6c : Importing Quiz Categories
        $qcid = '';
        $data = array(
            array('title' => 'General',),
        );
        foreach ($data as $d) {
            $condition = array('company_id' => $company_id, 'title' => $d['title']);
            $result = DB::table('quiz_categories')->where($condition)->first();
            if (!$result) {
                DB::table('quiz_categories')->insert(array_merge($condition, $additional));
                $qcid = DB::getPdo()->lastInsertId();
            }
        }

        //6d : Importing Quizes
        $quiz_id = '';
        $quiz_title = '';
        $description = "Lorem Ipsum is simply dummy text of the printing and typesetting industry.";
        $data = array(
            array('quiz_category_id' => $qcid, 'title' => 'Marketing Position Quiz', 'description' => $description, 'allowed_time' => '30',),
            array('quiz_category_id' => $qcid, 'title' => 'General Knowledge Quiz', 'description' => $description, 'allowed_time' => '30',),
        );
        foreach ($data as $d) {
            $condition = array('company_id' => $company_id, 'title' => $d['title']);
            $result = DB::table('quizes')->where($condition)->first();
            if (!$result) {
                $condition['quiz_category_id'] = $qcid;
                DB::table('quizes')->insert(array_merge($d, $additional));
                $quiz_id = DB::getPdo()->lastInsertId();
                $quiz_title = $d['title'];
            }
        }

        //6e : Importing Quiz Questions
        $data = array(
            array(
                'quiz_id' => $quiz_id,
                'title' => 'A computer basically constitutes of _______ integral components',
                'type' => 'radio',
                'answers' => array(
                    array('title' => 'Two', 'is_correct' => '0'),
                    array('title' => 'Four', 'is_correct' => '0'),
                    array('title' => 'Three', 'is_correct' => '1'),
                    array('title' => 'Eight', 'is_correct' => '0'),
                ),
            ),
            array(
                'quiz_id' => $quiz_id,
                'title' => 'Computers have secondary storage devices known as',
                'type' => 'radio',
                'answers' => array(
                    array('title' => 'ALU', 'is_correct' => '0'),
                    array('title' => 'Auxiliary Storage', 'is_correct' => '1'),
                    array('title' => 'CPU', 'is_correct' => '0'),
                    array('title' => 'None of the above', 'is_correct' => '0'),
                ),
            ),
            array(
                'quiz_id' => $quiz_id,
                'title' => 'Computers have secondary storage devices known as',
                'type' => 'radio',
                'answers' => array(
                    array('title' => 'ALU', 'is_correct' => '0'),
                    array('title' => 'Auxiliary Storage', 'is_correct' => '1'),
                    array('title' => 'CPU', 'is_correct' => '0'),
                    array('title' => 'None of the above', 'is_correct' => '0'),
                ),
            ),
        );
        foreach ($data as $d) {
            $condition = array('company_id' => $company_id, 'title' => $d['title']);
            $result = DB::table('quiz_questions')->where($condition)->first();
            if (!$result) {
                $answers = $d['answers'];
                unset($d['answers']);
                DB::table('quiz_questions')->insert(array_merge($d, $additional));
                $id = DB::getPdo()->lastInsertId();
                foreach ($answers as $answer) {
                    $answer['quiz_question_id'] = $id;
                    DB::table('quiz_question_answers')->insert($answer);
                }
            }
        }

        //6f : Attaching Quizes and Traites to Jobs
        $traites = CompanyTraite::where('company_id', 0)->get();
        $traites = $traites ? objToArr($traites->toArray()) : array();
        $job_filters = AdminJobFilter::getAll();
        $quiz_data = CompanyQuiz::getCompleteQuiz(encode($quiz_id));
        foreach ($jids as $jid) {
            $jquiz = array(
                'job_id' => $jid,
                'quiz_id' => $quiz_id,
                'quiz_title' => $quiz_title,
                'total_questions' => count($quiz_data['questions']),
                'allowed_time' => $quiz_data['quiz']['allowed_time'],
                'quiz_data' => json_encode($quiz_data),
                'company_id' => $company_id, 
            );
            DB::table('job_quizes')->insert($jquiz);
            
            //6fa : Attaching Traites to Jobs
            foreach ($traites as $traite) {
                $jtraite = array(
                    'job_id' => $jid,
                    'traite_id' => $traite['traite_id'],
                    'company_id' => $company_id, 
                    'title' => $traite['title'],
                );
                DB::table('job_traites')->insert($jtraite);
            }
            
            //6fb : Attaching Job Filters
            foreach ($job_filters as $jf) {
                $jfva = array(
                    'job_id' => $jid,
                    'company_id' => $company_id, 
                    'job_filter_id' => $jf['job_filter_id'],
                    'job_filter_value_id' => $jf['values'][rand(0,2)]['id'],
                );
                DB::table('job_filter_value_assignments')->insert($jfva);
            }
        }
    }    
}