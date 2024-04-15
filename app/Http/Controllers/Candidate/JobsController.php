<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Candidate\Candidate;
use App\Models\Candidate\Job;
use App\Models\Candidate\JobFilter;
use App\Models\Candidate\Department;
use App\Models\Candidate\Resume;
use App\Rules\MinString;
use App\Rules\MaxString;

class JobsController extends Controller
{
    /**
     * View Function to display account job listing page
     *
     * @return html/string
     */
    public function listing(Request $request)
    {
        $search = urldecode($request->input('search'));
        $departmentsOriginal = $request->input('departments');
        $departments = $departmentsOriginal ? implode(',', decodeArray(explode(',', $departmentsOriginal))) : array();
        $filters = $request->input('filters');
        $filtersSel = $filters ? decodeArray(json_decode($filters), false) : array();

        $jobs = Job::getAll($request->input('search'), $departmentsOriginal, $filtersSel);
        $data['job_filters'] = JobFilter::getAll();
        $data['jobFavorites'] = Job::getFavorites();
        $data['departments'] = Department::getAll();
        $data['page'] = __('message.job_listing').' | '.settingEmpSlug('site_name');
        $data['breadcrumb_title'] = __('message.job_listing');
        $data['jobs'] = $jobs['results'];
        $data['pagination'] = $jobs['pagination'];
        $data['search'] = $search;
        $data['departmentsSel'] = !is_array($departments) ? explode(',', $departments) : $departments;
        $data['filtersSel'] = $filtersSel;
        $data['filtersEncoded'] = $filters ? $filters : '{}';
        $data['favorites'] = Job::getFavorites();
        return view('candidate'.viewPrfx().'jobs-listing', $data);
    }    

    /**
     * View Function to display jobs listing page
     *
     * @return html/string
     */
    public function detail(Request $request, $slug = null, $id = null)
    {
        $search = urldecode($request->input('search'));
        $departments = $request->input('departments');
        $filters = $request->input('filters');
        $filtersSel = $filters ? decodeArray(json_decode($filters)) : array();
        $slug_or_id = strpos($id, '-') ? true : false;
        $slug_or_id = preg_match("/[a-z]/i", $id) ? true : false;
        $data['job'] = Job::getJob($id, $slug_or_id);
        if (!$data['job']) {
            return redirect('404_override');
        }
        $data['departments'] = Department::getAll();
        $data['job_filters'] = JobFilter::getAll();
        $data['resumes'] = Resume::getCandidateResumesList();
        $data['applied'] = Job::getAppliedJobs();
        $data['favorites'] = Job::getFavorites();
        $data['resume_id'] = Resume::getFirstDetailedResume();
        $data['search'] = $search;
        $data['departmentsSel'] = !is_array($departments) ? explode(',', $departments) : $departments;
        $data['filtersSel'] = $filtersSel;
        $data['filtersEncoded'] = $filters ? $filters : '{}';

        $data['page'] = $data['job']['title'] .' | ' . settingEmpSlug('site_name');

        return view('candidate'.viewPrfx().'job-detail', $data);
    } 

    /**
     * Function to mark jobs as favorite
     *
     * @return html/string
     */
    public function markFavorite($slug = null, $id = null)
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
    public function unmarkFavorite($slug = null, $id = null)
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
        echo view('candidate'.viewPrfx().'partials.refer-job', array())->render();
    } 

    /**
     * Function to refer job to a person
     *
     * @return html/string
     */
    public function referJob(Request $request, $slug = null, $id = null)
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
            die(json_encode(array('success' => 'false', 'messages' => 'Please login to refer')));
        }
    }

    /**
     * Function to apply to a job
     *
     * @return html/string
     */
    public function applyJob(Request $request, $slug = null, $id = null)
    {
        $this->checkIfDemo();
        if (candidateSession()) {

            $job = Job::getJob($request->input('job_id'));

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

                $resume = Resume::getFirst('resumes.resume_id', decode($request->input('resume')));

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
     * View Function to display candidate job applications page
     *
     * @param integer $page
     * @return html/string
     */
    public function jobApplicationsView()
    {
        $limit = viewPrfx('only') == 'beta' ? '10000' : '';
        $jobs = Job::getAppliedJobsList($limit);
        $data['page'] = __('message.job_applications').' | ' . settingEmpSlug('site_name');
        $data['breadcrumb_title'] = __('message.job_applications');
        $data['menu'] = 'applications';
        $data['jobs'] = $jobs['results'];
        $data['pagination'] = $jobs['pagination'];
        return view('candidate'.viewPrfx().'account-job-applications', $data);
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
        $data['page'] = __('message.job_favorites').' | ' . settingEmpSlug('site_name');
        $data['breadcrumb_title'] = __('message.job_favorites');
        $data['menu'] = 'favorites';
        $data['jobs'] = $jobs['results'];
        $data['pagination'] = $jobs['pagination'];
        return view('candidate'.viewPrfx().'account-job-favorites', $data);
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
        $data['page'] = __('message.job_referred').' | ' . settingEmpSlug('site_name');
        $data['breadcrumb_title'] = __('message.job_referred');
        $data['jobs'] = $jobs['results'];
        $data['pagination'] = $jobs['pagination'];
        $data['jobFavorites'] = Job::getFavorites();
        $data['menu'] = 'referred';
        return view('candidate'.viewPrfx().'account-job-referred', $data);
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
                '((site_link))' => url(empSlug()),
                '((site_logo))' => $detail['site_logo'],
                '((site_name))' => $detail['site_name'],
                '((first_name))' => candidateSession('first_name'),
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
                '((site_link))' => url(empSlug()),
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

