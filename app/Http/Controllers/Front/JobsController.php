<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Front\Job;
use App\Models\Front\CandidateMembership;
use App\Models\Admin\Department;
use App\Models\Admin\JobFilter;
use App\Models\Admin\Employer;
use App\Models\Admin\Menu;
use App\Models\Front\Resume As FrontResume;

use App\Rules\MinString;
use App\Rules\MaxString;

class JobsController extends Controller
{   
    /**
     * View function to display jobs page
     *
     * @return html/string
     */
    public function list(Request $request)
    {
        //Checking if jobs page is in menu
        if (!Menu::itemInMenu('all_jobs_page') && setting('allow_all_jobs_page') == 'yes') {
            die(__('message.not_allowed'));
        }

        $departments = $request->input('departments') ? implode(',', decodeArray(explode(',', $request->input('departments')))) : array();
        $companies = $request->input('companies') ? implode(',', decodeArray(explode(',', $request->input('companies')))) : array();
        $filters = $request->input('filters');
        $filtersSel = $filters ? decodeArray(json_decode($filters), false) : array();

        $jobs = Job::getAll($request);

        $data['job_filters'] = objToArr(JobFilter::getAll());
        $data['jobs'] = $jobs['results'];
        $data['pagination'] = $jobs['pagination'];
        $data['page'] = $request->get('page');
        $data['keyword'] = $request->get('keyword');
        $data['sort'] = $request->get('sort');
        $data['view'] = $request->get('view') ? $request->get('view') : 'list';
        $data['selected_company'] = $request->get('company');
        $data['selected_department'] = $request->get('department');
        $data['favorites'] = Job::getFavorites();
        $data['companies'] = Employer::getAll2(true, true);
        $data['departments'] = Department::getAll(true);
        $data['pagination_overview'] = paginationOverview($jobs['total'], $jobs['perPage'], $jobs['currentPage']);
        $data['search'] = urldecode($request->input('search'));
        $data['departmentsSel'] = !is_array($departments) ? explode(',', $departments) : $departments;
        $data['companiesSel'] = !is_array($companies) ? explode(',', $companies) : $companies;
        $data['filtersSel'] = $filtersSel;
        $data['filtersEncoded'] = $filters ? $filters : '{}';
        $data['page_title'] = __('message.jobs');

        return view('front'.viewPrfx().'jobs.'.$data['view'], $data);
    }

    /**
     * View function to display jobs page
     *
     * @return html/string
     */
    public function detail(Request $request, $slug)
    {
        //Checking if jobs page is in menu
        if (setting('allow_all_jobs_page') != 'yes') {
            die(__('message.not_allowed'));
        }

        //Getting job detail
        $col = preg_match("/[a-z]/i", $slug) ? 'jobs.slug' : 'jobs.job_id';
        $slug = $col == 'jobs.job_id' ? decode($slug) : $slug;
        $job = Job::getSingle($col, $slug);

        if (!isset($job['job_id'])) {
            die('not found');
        }

        //Check separate site detail
        if (separateSiteAvailable($job['separate_site'])) {
            //die(__('message.not_allowed'));
        }

        $data['job'] = $job;
        $data['resumes'] = FrontResume::getCandidateResumesList();
        $data['applied'] = Job::getAppliedJobs();
        $data['favorites'] = Job::getFavorites();
        $data['resume_id'] = FrontResume::getFirstDetailedResume();
        $data['similar'] = Job::getSimilar($job['title'], $job['job_id']);
        $data['employer'] = Employer::getEmployer('employers.employer_id', $job['employer_id']);
        $data['page_title'] = $job['title'];
        return view('front'.viewPrfx().'jobs.detail', $data);
    }

    /**
     * Function to apply to a job
     *
     * @return html/string
     */
    public function applyJob(Request $request, $id = null)
    {
        $this->checkIfDemo();
        if (candidateSession()) {

            //Checking if job applications are allowed (if candidate packages are enabled.)
            if (setting('enable_candidate_packages') == 'yes') {
                $applied_jobs = Job::getAppliedJobs();
                $allowed_job_applications = CandidateMembership::getActiveMembership('allowed_job_applications');
                if (count($applied_jobs) >= $allowed_job_applications) {
                    $message = __('message.job_allowed_limit_msg').' ('.__('message.allowed_job_applications').' : '.$allowed_job_applications.', '.__('message.job_applications').' : '.count($applied_jobs).')';
                    die(json_encode(array(
                        'success' => 'false',
                        'messages' => $this->ajaxErrorMessage(array('error' => $message))
                    )));
                }
            }

            //Apply to job
            $job = Job::getSingle('jobs.job_id', decode($request->input('job_id')));

            if (setting('enable_multiple_resume') == 'yes') {

                $validator = Validator::make($request->all(), [
                    'resume' => 'required',
                ], [
                    'resume.required' => __('validation.required'),
                ]);
                if ($validator->fails()) {
                    die(json_encode(array(
                        'success' => 'false',
                        'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
                    )));
                }                  

                $resume = FrontResume::getFirst('resumes.resume_id', decode($request->input('resume')));

                if ($job['is_static_allowed'] != 1 && $resume['type'] != 'detailed') {
                    echo json_encode(array(
                        'success' => 'error',
                        'messages' => $this->ajaxErrorMessage(array('error' => __('message.you_need_to_apply_via_detailed')))
                    ));
                } else {
                    $detail = Job::applyJob($request->all());
                    $this->sendEmailToEmployerAndCandidate($job, $detail);
                    echo json_encode(array(
                        'success' => 'true',
                        'messages' => $this->ajaxErrorMessage(array('success' => __('message.job_applied_successfully')))
                    ));
                }
 
            } else {
                $detail = Job::applyJob($request->all());
                $this->sendEmailToEmployerAndCandidate($job, $detail);
                echo json_encode(array(
                    'success' => 'true',
                    'messages' => $this->ajaxErrorMessage(array('success' => __('message.job_applied_successfully')))
                ));
            }
        } else {
            echo json_encode(array('success' => 'false', 'messages' => ''));
        }
    }

    /**
     * Function to mark jobs as favorite
     *
     * @return html/string
     */
    public function markFavorite($id = null)
    {
        if (candidateSession()) {
            if (Job::markFavorite($id)) {
                echo json_encode(array('success' => 'true', 'messages' => ''));
            }
        } else {
            echo json_encode(array('success' => 'false', 'messages' => ''));
        }
    } 

    /**
     * Function to unmark jobs as favorite
     *
     * @return html/string
     */
    public function unmarkFavorite($id = null)
    {
        Job::unmarkFavorite($id);
        echo json_encode(array('success' => 'true', 'messages' => ''));
    }

    /**
     * Function to display refer job form
     *
     * @return html/string
     */
    public function referJobView()
    {
        echo view('front'.viewPrfx().'refer-job', array())->render();
    } 

    /**
     * Function to refer job to a person
     *
     * @return html/string
     */
    public function referJob(Request $request, $id = null)
    {
        $this->checkIfDemo();
        if (candidateSession()) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'name' => ['required', new MinString(2), new MaxString(50)],
                'phone' => 'required|digits_between:0,50',
            ], [
                'email.required' => __('validation.required'),
                'email.email' => __('validation.email'),
                'name.required' => __('validation.required'),
                'name.min' => __('validation.min_string'),
                'name.max' => __('validation.max_string'),
                'phone.required' => __('validation.required'),
                'phone.digits_between' => __('validation.digits_between'),
            ]);
            if ($validator->fails()) {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
                )));
            }

            if (Job::ifAlreadyReferred($request->all())) {
                die(json_encode(array(
                    'success' => 'error',
                    'messages' => $this->ajaxErrorMessage(array('error' => __('message.job_is_already_referred')))
                )));
            } else {
                $detail = Job::referJob($request->all());
                $tagsWithValues = array(
                    '((site_link))' => $detail['url'],
                    '((site_logo))' => $detail['site_logo'],
                    '((name))' => $request->input('name'),
                    '((first_name))' => candidateSession('first_name'),
                    '((last_name))' => candidateSession('last_name'),
                    '((link))' => $detail['url'].'job/'.$request->input('job_id'),
                );
                $messageEmail = replaceTagsInTemplate2(setting('employer_refer_job'), $tagsWithValues);
                $this->sendEmail($messageEmail, $request->input('email'), $detail['site_name'].' : '.__('message.job_referred'));
                die(json_encode(array(
                    'success' => 'true',
                    'messages' => $this->ajaxErrorMessage(array('success' => __('message.job_referred_successfully')))
                )));
            }
        } else {
            die(json_encode(array(
                'success' => 'error',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.login_as_candidate_to_refer')))
            )));
        }
    }

    /**
     * View Function to display candidate job applications page
     *
     * @param integer $page
     * @return html/string
     */
    public function jobApplicationsView()
    {
        $limit = viewPrfx('only') == 'beta' ? '10000' : '';
        $jobs = Job::getAppliedJobsList($limit);
        $data['page_title'] = __('message.job_applications');
        $data['menu'] = 'applications';
        $data['jobs'] = $jobs['results'];
        $data['pagination'] = $jobs['pagination'];
        return view('front'.viewPrfx().'candidates.job-applications', $data);
    }

    /**
     * View Function to display candidate job favorites page
     *
     * @param integer $page
     * @return html/string
     */
    public function jobFavoritesView()
    {
        $limit = viewPrfx('only') == 'beta' ? '10000' : '';
        $jobs = Job::getFavoriteJobsList($limit);
        $data['page_title'] = __('message.job_favorites');
        $data['menu'] = 'favorites';
        $data['jobs'] = $jobs['results'];
        $data['pagination'] = $jobs['pagination'];
        return view('front'.viewPrfx().'candidates.job-favorites', $data);
    }

    /**
     * View Function to display candidate job referred page
     *
     * @param integer $page
     * @return html/string
     */
    public function jobReferredView($page = null)
    {
        $limit = viewPrfx('only') == 'beta' ? '10000' : '';
        $jobs = Job::getReferredJobsList($limit);
        $data['page_title'] = __('message.job_referred');
        $data['jobs'] = $jobs['results'];
        $data['pagination'] = $jobs['pagination'];
        $data['jobFavorites'] = Job::getFavorites();
        $data['menu'] = 'referred';
        return view('front'.viewPrfx().'candidates.job-referred', $data);
    }

    /**
     * Private function to send job application notifications
     *
     * @param integer $job
     * @param array $candidate
     * @return void
     */
    private function sendEmailToEmployerAndCandidate($job, $detail)
    {
        //Sending email notification to candidate
        if (setting('enable_candidate_job_apply_notification') == 'yes') {
            $tagsWithValues = array(
                '((site_link))' => $detail['url'],
                '((site_logo))' => $detail['site_logo'],
                '((site_name))' => $detail['site_name'],
                '((first_name))'=> candidateSession('first_name'),
                '((last_name))' => candidateSession('last_name'),
                '((job_title))' => $job['title'],
            );
            $messageEmail = replaceTagsInTemplate2($detail['candidate_job_app'], $tagsWithValues);
            $subject = $detail['site_name'].' : '.__('message.job_applied').' ('.$job['title'].')';
            $this->sendEmail($messageEmail, candidateSession('email'), $subject);
        }

        //Sending email notification to employer
        if (setting('enable_employer_job_apply_notification') == 'yes') {
            $tagsWithValues = array(
                '((site_link))' => $detail['url'],
                '((site_logo))' => $detail['site_logo'],
                '((site_name))' => $detail['site_name'],
                '((job_title))' => $job['title'],
            );
            $messageEmail = replaceTagsInTemplate2($detail['employer_job_app'], $tagsWithValues);
            $subject = $detail['site_name'].' : '.__('message.job_application_received').' ('.$job['title'].')';
            $this->sendEmail($messageEmail, $job['employer_email'], $subject);
        }
    }
}
