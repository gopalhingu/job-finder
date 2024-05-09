<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Admin\News;
use App\Models\Admin\Job;
use App\Models\Admin\Department;
use App\Models\Admin\JobFilter;
use App\Models\Admin\Employer;
use App\Models\Admin\Company as AdminCompany;
use App\Models\Admin\Testimonial;
use App\Models\Admin\Message;
use App\Models\Admin\Page;
use App\Models\Admin\Notification;
use App\Models\Admin\Package;
use App\Models\Admin\Faq;
use App\Models\Admin\Membership;
use App\Models\Admin\Menu;
use App\Models\Employer\Employer as EmployerEmployer;
use App\Models\Admin\Candidate as AdminCandidate;
use App\Models\Front\Candidate as FrontCandidate;
use App\Models\Candidate\Candidate as CandidateCandidate;
use App\Models\Front\Job as FrontJob;
use App\Rules\MinString;
use App\Rules\MaxString;

use App\Models\Front\Company;
use App\Models\Company\Company as CompanyCompany;


class GeneralsController extends Controller
{
    /**
     * View function to display home page
     *
     * @return html/string
     */
    public function index(Request $request)
    {
        $data = array();
        if (setting('home_highlights_section') == 'enabled') {
            $data['jobs_count'] = Job::where('status', 1)->count();
            $data['employers_count'] = Employer::where('status', 1)->where('type', 'main')->count();
            $data['candidates_count'] = AdminCandidate::where('status', 1)->count();
        }
        if (setting('home_departments_section') == 'enabled') {
            $limit = setting('home_departments_section_limit');
            $orderBy = explode(' ', setting('home_departments_section_sort_order'));
            $orderByCol = issetVal($orderBy, 0, 'departments.department_id');
            $orderByDir = issetVal($orderBy, 1, 'DESC');
            $data['departments'] = Department::getAll(true, $limit, $orderByCol, $orderByDir);
        }
        if (setting('home_companies_section') == 'enabled') {
            $limit = setting('home_companies_section_limit');
            $orderBy = explode(' ', setting('home_companies_section_sort_order'));
            $orderByCol = issetVal($orderBy, 0, 'employers.employer_id');
            $orderByDir = issetVal($orderBy, 1, 'DESC');
            $data['companies'] = Employer::getForFront(true, $limit, $orderByCol, $orderByDir);
        }
        if (setting('home_jobs_section') == 'enabled') {
            $limit = setting('home_jobs_section_limit');
            $orderBy = explode(' ', setting('home_jobs_section_sort_order'));
            $orderByCol = issetVal($orderBy, 0, 'jobs.job_id');
            $orderByDir = issetVal($orderBy, 1, 'DESC');
            if ( candidateSession() ) {
                $data['jobs'] = FrontJob::getForFrontwithCandidate(true, candidateSession(), $limit, $orderByCol, $orderByDir);
            } else if ( companySession() ) {
                $data['jobs'] = FrontJob::getForFrontwithCompany(true, companySession(), $limit, $orderByCol, $orderByDir);
            } else{
                $data['jobs'] = FrontJob::getForFront(true, $limit, $orderByCol, $orderByDir);
            }
            $data['favorites'] = FrontJob::getFavorites();
        }
        if (setting('home_candidates_section') == 'enabled') {
            $limit = setting('home_candidates_section_limit');
            $orderBy = explode(' ', setting('home_candidates_section_sort_order'));
            $orderByCol = issetVal($orderBy, 0, 'candidates.candidate_id');
            $orderByDir = issetVal($orderBy, 1, 'DESC');
            $data['candidates'] = FrontCandidate::getForFront(true, $limit, $orderByCol, $orderByDir);
            $data['favorites'] = FrontCandidate::getFavorites();
        }
        if (setting('home_pricing_section') == 'enabled' || setting('home_pricing') == 'yes') {
            $data['packages'] = Package::getAll();
        }
        if (setting('home_testimonials_section') == 'enabled') {
            $limit = setting('home_testimonials_section_limit');
            $orderBy = explode(' ', setting('home_testimonials_section_sort_order'));
            $orderByCol = issetVal($orderBy, 0, 'testimonials.testimonial_id');
            $orderByDir = issetVal($orderBy, 1, 'DESC');
            $data['testimonials'] = Testimonial::getForHome(true, $limit, $orderByCol, $orderByDir);
        }
        if (setting('home_news_section') == 'enabled') {
            $limit = setting('home_news_section_limit');
            $orderBy = explode(' ', setting('home_news_section_sort_order'));
            $orderByCol = issetVal($orderBy, 0, 'news.news_id');
            $orderByDir = issetVal($orderBy, 1, 'DESC');
            $data['news'] = News::getForHome(true, $limit, $orderByCol, $orderByDir);
        }
        $data['hired_count'] = DB::table('job_applications')->where('status', 'hired')->count();
        $data['departments_banner'] = Department::getAll(true, 10000, 'departments.created_at', 'DESC');
        $data['search_logs'] = FrontJob::getSearchLogs();
        return view('front'.viewPrfx().'index', $data);
    }

    /**
     * View function to display register page
     *
     * @return html/string
     */
    public function registerView()
    {
        if (candidateSession() || employerSession()) {
            return redirect(route('home'));
        }

        if (setting('enable_employer_registeration') == 'no' && setting('enable_candidate_registeration') == 'no') {
            $message = __('message.not_allowed').'<Br />'.'<a href="'.url('/').'">'.__('message.back').'</a>';
            die($message);
        }
        if (setting('enable_employer_free_registeration') == 'yes') {
            $data['page_title'] = __('register');
            return view('front.register-free', $data);
        } else {
            $data['page_title'] = __('register');
            $data['packages'] = Package::getAll();
            return view('front.register-paid', $data);
        }
    }

    /**
     * View function to submit register free form
     *
     * @return html/string
     */
    public function registerFreeFormSubmit(Request $request)
    {
        $this->checkIfDemo();

        //Checking for reserved words to avoid any conflict with routes
        if (reservedWord(slugify($request->input('company')))) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.choose_different_company_name')))
            )));
        }

        //Checking if there is any free package or not
        $freePackage = Package::where('is_free', 1)->first();
        if (!$freePackage) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.no_free_package')))
            )));
        }

        //Doing form validations
        $rules['first_name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['last_name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['company'] = ['required', new MinString(2), new MaxString(100), 'unique:employers'];
        $rules['email'] = 'required|email|unique:employers';
        $rules['password'] = 'required|required_with:retype_password|same:retype_password';
        $rules['retype_password'] = 'required';
        $messages = [
            'first_name.required' => __('validation.required'),
            'first_name.min' => __('validation.min_string'),
            'first_name.max' => __('validation.max_string'),
            'last_name.required' => __('validation.required'),
            'last_name.min' => __('validation.min_string'),
            'last_name.max' => __('validation.max_string'),
            'company.required' => __('validation.required'),
            'company.min' => __('validation.min_string'),
            'company.max' => __('validation.max_string'),
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'password.required' => __('validation.required'),
            'retype_password.required' => __('validation.required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Creating employer
        $employer['first_name'] = $request->input('first_name');
        $employer['last_name'] = $request->input('last_name');
        $employer['email'] = $request->input('email');
        $employer['company'] = $request->input('company');
        $employer['slug'] = slugify($request->input('company'));
        $employer['employername'] = $employer['slug'];
        $employer['password'] = Hash::make($request->input('password'));
        $employer['status'] = 1;
        $employer['created_at'] = date('Y-m-d G:i:s');
        $employer['updated_at'] = date('Y-m-d G:i:s');
        $successMessage = __('message.account_created_please_login');

        //Sending email to employer if verification is enabled
        if (setting('enable_employer_email_verification') == 'yes') {
            $token = token();
            $tagsWithValues = array(
                '((site_link))' => url('/'),
                '((site_logo))' => setting('site_logo'),
                '((first_name))' => $employer['first_name'],
                '((last_name))' => $employer['last_name'],
                '((email))' => $employer['email'],
                '((link))' => route('employer-activate-account', $token),
            );
            $message = replaceTagsInTemplate2(setting('employer_verify_email'), $tagsWithValues);
            $subject = setting('site_name').' : '.__('message.verify_account');
            $this->sendEmail($message, $employer['email'], $subject);
            $employer['token'] = $token;
            $employer['status'] = 0;
            $successMessage = __('message.a_verification_email_has_been_sent');
        }

        //Inserting employer
        Employer::insert($employer);
        $employer_id = \DB::getPdo()->lastInsertId();

        //Creating employer settings if no verification is required
        //IF not created at this step, settings will be created when employer activates account
        if (setting('enable_employer_email_verification') == 'no') {
            Employer::importEmployerSettings($employer_id);

            if (setting('import_employer_dummy_data_on_signup') == 'yes') {
                Employer::importEmployerDummyData($employer_id);
            }
        }

        //Creating membership
        $membership['employer_id'] = $employer_id;
        $membership['package_id'] = $freePackage->package_id;
        $membership['title'] = $freePackage->title;
        $membership['payment_type'] = 'Free';
        $membership['package_type'] = 'Free';
        $membership['price_paid'] = '0.00';
        $membership['details'] = json_encode($freePackage->toArray());
        $membership['separate_site'] = $freePackage->separate_site;
        $membership['status'] = 1;
        $membership['created_at'] = date('Y-m-d G:i:s');
        $membership['expiry'] = packageExpiry('free');
        Membership::insert($membership);

        //Inserting notification for admin
        $newSignupMessage = __('message.new_employer_msg', array(
            'name' => $request->input('company'), 
            'package' => $freePackage->title, 
            'date' => date('D M, Y h:i a'),
        ));
        Notification::do('employer_signup', __('message.new_employer').' ('.$request->input('company').')');

        //Sending email to admin
        if (setting('enable_employer_register_notification') == 'yes') {
            $tagsWithValues = array(
                '((site_link))' => url('/'),
                '((site_logo))' => setting('site_logo'),
                '((first_name))' => $employer['first_name'],
                '((last_name))' => $employer['last_name'],
                '((email))' => $employer['email'],
                '((package))' => $membership['details'],
            );
            $message = replaceTagsInTemplate2(setting('employer_signup'), $tagsWithValues);
            $this->sendEmail($message, setting('admin_email'), setting('site_name').' : '.__('message.new_employer_signup'));
        }
        
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => $successMessage))
        )));
    }
    
    public function registerCompany(Request $request)
    {
        $this->checkIfDemo();

        //Checking for reserved words to avoid any conflict with routes
        if (reservedWord(slugify($request->input('company')))) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.choose_different_company_name')))
            )));
        }

        //Checking if there is any free package or not
        $freePackage = Package::where('is_free', 1)->first();
        if (!$freePackage) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.no_free_package')))
            )));
        }

        //Doing form validations
        $rules['first_name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['last_name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['company'] = ['required', new MinString(2), new MaxString(100), 'unique:company'];
        $rules['email'] = 'required|email|unique:company';
        $rules['password'] = 'required|required_with:retype_password|same:retype_password';
        $rules['retype_password'] = 'required';
        $messages = [
            'first_name.required' => __('validation.required'),
            'first_name.min' => __('validation.min_string'),
            'first_name.max' => __('validation.max_string'),
            'last_name.required' => __('validation.required'),
            'last_name.min' => __('validation.min_string'),
            'last_name.max' => __('validation.max_string'),
            'company.required' => __('validation.required'),
            'company.min' => __('validation.min_string'),
            'company.max' => __('validation.max_string'),
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'password.required' => __('validation.required'),
            'retype_password.required' => __('validation.required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Creating company
        $company['first_name'] = $request->input('first_name');
        $company['last_name'] = $request->input('last_name');
        $company['email'] = $request->input('email');
        $company['company'] = $request->input('company');
        $company['slug'] = slugify($request->input('company'));
        $company['companyname'] = $company['slug'];
        $company['password'] = Hash::make($request->input('password'));
        $company['status'] = 1;
        $company['created_at'] = date('Y-m-d G:i:s');
        $company['updated_at'] = date('Y-m-d G:i:s');
        $successMessage = __('message.account_created_please_login');

        // Sending email to company if verification is enabled
        if (setting('enable_company_email_verification') == 'yes') {
            $token = token();
            $tagsWithValues = array(
                '((site_link))' => url('/'),
                '((site_logo))' => setting('site_logo'),
                '((first_name))' => $company['first_name'],
                '((last_name))' => $company['last_name'],
                '((email))' => $company['email'],
                '((link))' => route('company-activate-account', $token),
            );
            $message = replaceTagsInTemplate2(setting('company_verify_email'), $tagsWithValues);
            $subject = setting('site_name').' : '.__('message.verify_account');
            $this->sendEmail($message, $company['email'], $subject);
            $company['token'] = $token;
            // $company['status'] = 0;
            $successMessage = __('message.a_verification_email_has_been_sent');
        }

        //Inserting company
        Company::insert($company);
        $company_id = \DB::getPdo()->lastInsertId();

        //Creating employer settings if no verification is required
        //IF not created at this step, settings will be created when employer activates account
        if (setting('enable_company_email_verification') == 'no') {
            AdminCompany::importCompanySettings($company_id);

            if (setting('import_company_dummy_data_on_signup') == 'yes') {
                AdminCompany::importCompanyDummyData($company_id);
            }
        }

        //Creating membership
        $membership['company_id'] = $company_id;
        $membership['package_id'] = $freePackage->package_id;
        $membership['title'] = $freePackage->title;
        $membership['payment_type'] = 'Free';
        $membership['package_type'] = 'Free';
        $membership['price_paid'] = '0.00';
        $membership['details'] = json_encode($freePackage->toArray());
        $membership['separate_site'] = $freePackage->separate_site;
        $membership['status'] = 1;
        $membership['created_at'] = date('Y-m-d G:i:s');
        $membership['expiry'] = packageExpiry('free');
        Membership::insert($membership);

        //Inserting notification for admin
        $newSignupMessage = __('message.new_company_msg', array(
            'name' => $request->input('company'), 
            'package' => $freePackage->title, 
            'date' => date('D M, Y h:i a'),
        ));
        Notification::do('company_signup', __('message.new_employer').' ('.$request->input('company').')');

        //Sending email to admin
        if (setting('enable_company_register_notification') == 'yes') {
            $tagsWithValues = array(
                '((site_link))' => url('/'),
                '((site_logo))' => setting('site_logo'),
                '((first_name))' => $company['first_name'],
                '((last_name))' => $company['last_name'],
                '((email))' => $company['email'],
                '((package))' => $membership['details'],
            );
            $message = replaceTagsInTemplate2(setting('company_signup'), $tagsWithValues);
            $this->sendEmail($message, setting('admin_email'), setting('site_name').' : '.__('message.new_employer_signup'));
        }
        
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => $successMessage))
        )));
    }  

    /**
     * View Function to display register page for user
     *
     * @return html/string
     */
    public function showForgotPassword()
    {
        if (candidateSession() || employerSession()) {
            return redirect(route('home'));
        }

        $data['page_title'] = __('message.forgot_password');
        return view('front.forgot-password', $data);
    }

    /**
     * Function to display register page for user
     *
     * @return html/string
     */
    public function sendPasswordLink(Request $request)
    {
        $this->checkIfDemo();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        if ($request->input('type') == 'candidate') {
            $candidate = FrontCandidate::getFirst('candidates.email', $request->input('email'));
            if ($candidate['candidate_id'] == '') {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => __('message.email_does_not_exist')))
                )));
            }

            FrontCandidate::createTokenForCandidate($request->input('email'));

            //Sending mail
            $candidate = FrontCandidate::getFirst('candidates.email', $request->input('email'));
            $tagsWithValues = array(
                '((site_link))' => url('/'),
                '((site_logo))' => setting('site_logo'),
                '((first_name))' => $candidate['first_name'],
                '((last_name))' => $candidate['last_name'],
                '((link))' => url('/').'/reset-password/'.$candidate['token'].'?type=candidate',
            );
            $messageEmail = replaceTagsInTemplate2(setting('candidate_reset_password'), $tagsWithValues);
            $this->sendEmail($messageEmail, $candidate['email'], __('message.create_new_password'));
        } else {
            $employer = EmployerEmployer::checkEmployerByEmail($request->input('email'));
            if ($employer) {
                $token = EmployerEmployer::saveTokenForPasswordReset($request->input('email'));
                $message = __('message.an_email_with_a_link_to_reset');
                $tagsWithValues = array(
                    '((site_link))' => url('/'),
                    '((site_logo))' => setting('site_logo'),
                    '((first_name))' => $employer['first_name'],
                    '((last_name))' => $employer['last_name'],
                    '((link))' => url('/').'/reset-password/'.$token.'?type=employer',
                );
                $messageEmail = replaceTagsInTemplate2(setting('employer_reset_password'), $tagsWithValues);
                $subject = setting('site_name').' : '.__('message.your_password_reset_link');
                $this->sendEmail($messageEmail, $employer['email'], $subject);
            } else {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => __('message.email_does_not_exist')))
                )));
            }
        }

        //Giving success message
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.an_email_with_a_link_to_reset')))
        )));

    }

    /**
     * View function to display password reset form by email
     *
     * @return redirect
     */
    public function resetPassword(Request $request, $token = null)
    {
        if (candidateSession() || employerSession()) {
            return redirect(route('home'));
        }
        
        $data['page_title'] = __('message.reset_password');
        $data['token'] = $token;
        $data['type'] = $request->input('type');
        return view('front'.viewPrfx().'reset-password', $data);
    }

    /**
     * Function (for ajax) to process password reset form request
     *
     * @return redirect
     */
    public function updatePasswordByForgot(Request $request)
    {
        $this->checkIfDemo();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'new_password' => 'required|required_with:retype_new_password|same:retype_new_password',
            'retype_new_password' => 'required',
        ], [
            'token.required' => __('validation.required'),
            'new_password.required' => __('validation.required'),
            'retype_new_password.required' => __('validation.required')
        ]);

        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        if ($request->input('type') == 'candidate') {
            $existing = FrontCandidate::getFirst('token', $request->input('token'));
            if ($existing['candidate_id'] == '') {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => __('message.token_mismatch')))
                )));
            }
            FrontCandidate::updatePasswordByField('token', $request->input('token'), Hash::make($request->input('new_password')));
        } else {
            if (!EmployerEmployer::checkIfTokenExist($request->input('token'))) {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => __('message.token_mismatch')))
                )));
            }
            EmployerEmployer::updatePasswordByField('token', $request->input('token'), $request->input('new_password'));
        }

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.password_updated')))
        )));
    }

    /**
     * View function to display single page
     *
     * @return html/string
     */
    public function page($slug)
    {
        $page = Page::getPage('pages.slug', $slug);
        if ($page['page_id'] == '') {
            return redirect('/');
        }
        $data['page'] = $page;
        $data['page_title'] = $page->title;
        $data['page_keywords'] = $page->keywords;
        $data['page_summary'] = $page->summary;
        $data['page_description'] = $page->description;
        return view('front'.viewPrfx().'page', $data);
    }

    /**
     * View function to display companies page
     *
     * @return html/string
     */
    public function companies(Request $request)
    {
        //Checking if companies page is in menu
        if (!Menu::itemInMenu('all_companies_page')) {
            die(__('message.not_allowed'));
        }

        $companies = Employer::getAll(
            true,
            setting('companies_per_page'),
            'main',
            $request->get('sort'),
            $request->get('search'),
            $request->get('industry'),
            $request->get('no_of_employees')
        );
        $data['companies'] = $companies;
        $data['sort'] = $request->get('sort');
        $data['page'] = $request->get('page');
        $data['search'] = $request->get('search');
        $data['industry'] = $request->input('industry');
        $data['no_of_employees'] = $request->get('no_of_employees');
        $data['no_of_employees_dd'] = Employer::$noOfEmployees;
        $data['industry_dd'] = Employer::$industries;
        $data['pagination'] = $companies ? $companies->links('front'.viewPrfx().'partials.companies-pagination') : '';
        $data['pagination_overview'] = paginationOverview($companies->total(), $companies->perPage(), $companies->currentPage());
        $data['page_title'] = __('message.companies');
        return view('front'.viewPrfx().'companies.list', $data);
    }

    /**
     * View function to display company detail page
     *
     * @return html/string
     */
    public function companyDetail($slug)
    {
        $data['page_title'] = __('message.companies');
        $data['employer'] = Employer::getEmployer('employers.slug', $slug);
        $data['jobs'] = FrontJob::getForEmployer($slug);
        $data['favorites'] = FrontJob::getFavorites();
        return view('front'.viewPrfx().'companies.detail', $data);
    }

    /**
     * View function to display features page
     *
     * @return html/string
     */
    public function features(Request $request)
    {
        if (viewPrfx('only') == 'alpha') {
            die('Not Available in Alpha View');
        }

        //Checking if features page is in menu
        if (!Menu::itemInMenu('features')) {
            die(__('message.not_allowed'));
        }
        $data['page_title'] = __('message.features');
        return view('front'.viewPrfx().'features', $data);
    }

    /**
     * View function to display pricing page
     *
     * @return html/string
     */
    public function pricing(Request $request)
    {
        if (viewPrfx('only') == 'alpha') {
            die('Not Available in Alpha View');
        }

        //Checking if pricing page is in menu
        if (!Menu::itemInMenu('pricing')) {
            die(__('message.not_allowed'));
        }
        $data['page_title'] = __('message.pricing');
        $data['packages'] = Package::getAll();
        $data['faqs'] = Faq::getForPricingPage();
        return view('front'.viewPrfx().'pricing', $data);
    }

    /**
     * View function to display contact page
     *
     * @return html/string
     */
    public function contact(Request $request)
    {
        if (viewPrfx('only') == 'alpha') {
            die('Not Available in Alpha View');
        }
        
        //Checking if contact page is in menu
        if (!Menu::itemInMenu('contact')) {
            die(__('message.not_allowed'));
        }
        $data['page_title'] = __('message.contact');
        return view('front'.viewPrfx().'contact', $data);
    }

    /**
     * View function to submit contact form
     *
     * @return html/string
     */
    public function contactFormSubmit(Request $request)
    {
        $this->checkIfDemo();

        $rules['name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['email'] = 'required|email';
        $rules['subject'] = ['required', new MinString(10), new MaxString(150)];
        $rules['message'] = ['required', new MinString(10), new MaxString(5000)];

        $validator = Validator::make($request->all(), $rules, [
            'name.required' => __('validation.required'),
            'name.min' => __('validation.min_string'),
            'name.max' => __('validation.max_string'),
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'subject.required' => __('validation.required'),
            'subject.min' => __('validation.min_string'),
            'subject.max' => __('validation.max_string'),
            'message.required' => __('validation.required'),
            'message.min' => __('validation.min_string'),
            'message.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $subject = __('message.new_site_message') .' : '. $request->input('subject');
        if (setting('enable_site_message_email') == 'yes') {
            $this->sendEmail($request->input('message'), setting('admin_email'), $subject);
        }

        Message::storeMessage($request->all());
        Notification::do('message', $request->input('subject'));

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array(
                'success' => __('message.message').' '.__('message.created')
        )))));
    }

    /**
     * View function to display login register modal
     *
     * @return html/string
     */
    public function loginRegisterModal(Request $request, $type = NULL)
    {
        $data['jobs_count'] = Job::where('status', 1)->count();
        $data['employers_count'] = Employer::where('status', 1)->where('type', 'main')->count();
        $data['candidates_count'] = AdminCandidate::where('status', 1)->count();
        $data['type'] = $type;

        if (setting('enable_google_login') == 'yes') {
            $client = $this->getGoogleClient('employer');
            $data['google_login_link_employer'] = $client->createAuthUrl();
            $client2 = $this->getGoogleClient('candidate');
            $data['google_login_link_candidate'] = $client2->createAuthUrl();
        } else {
            $data['google_login_link_candidate'] = '';
            $data['google_login_link_employer'] = '';
        }

        if (setting('enable_linkedin_login') == 'yes') {
            $linkedinHelper = new \App\Helpers\LinkedinHelper();
            $data['linkedin_login_link_employer'] = $linkedinHelper->getLink('employer');
            $data['linkedin_login_link_candidate'] = $linkedinHelper->getLink('candidate');
        } else {
            $data['linkedin_login_link_candidate'] = '';
            $data['linkedin_login_link_employer'] = '';
        }

        echo view('front'.viewPrfx().'login-register-modal', $data)->render();
    }

    /**
     * View function to display login form in modal
     *
     * @return html/string
     */
    public function loginView(Request $request)
    {
        if (setting('enable_google_login') == 'yes') {
            $client = $this->getGoogleClient('employer');
            $data['google_login_link_employer'] = $client->createAuthUrl();
            $client2 = $this->getGoogleClient('candidate');
            $data['google_login_link_candidate'] = $client2->createAuthUrl();
        } else {
            $data['google_login_link_candidate'] = '';
            $data['google_login_link_employer'] = '';
        }

        if (setting('enable_linkedin_login') == 'yes') {
            $linkedinHelper = new \App\Helpers\LinkedinHelper();
            $data['linkedin_login_link_employer'] = $linkedinHelper->getLink('employer');
            $data['linkedin_login_link_candidate'] = $linkedinHelper->getLink('candidate');
        } else {
            $data['linkedin_login_link_candidate'] = '';
            $data['linkedin_login_link_employer'] = '';
        }

        echo view('front'.viewPrfx().'login', $data)->render();
    }

    /**
     * Function to process login form request
     *
     * @return redirect
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'password.required' => __('validation.required')
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $type = $request->input('type') ? $request->input('type') : 'candidate';

        if ($type == 'candidate') {
            $candidate = FrontCandidate::login($request->input('email'), $request->input('password'));
            if ($candidate) {
                setSession('candidate', objToArr($candidate));
                $this->setRememberMe($request->input('email'), $request->input('remember'), 'candidate');
            } else {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => __('message.email_password_error')))
                )));
            }
        }  else if($type == 'company'){
            
            $company = objToArr(CompanyCompany::login($request->input('email'), $request->input('password')));
            if ($company) {
                setSession('company', objToArr($company));
                $this->setRememberMe($request->input('email'), $request->input('remember'), 'company');
            } else {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => __('message.email_password_error')))
                )));
            }  
        }else {
            $employer = objToArr(EmployerEmployer::login($request->input('email'), $request->input('password')));
            if ($employer) {
                $employer['user_type'] = 'employer';
                setSession('employer', $employer);
                $this->setRememberMe($request->input('email'), $request->input('rememberme'), 'employer');
            } else {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => __('message.email_password_error')))
                )));
            }
        }

        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.success')))
        ));
    }    

    /**
     * View function to display register form in modal
     *
     * @return html/string
     */
    public function registerViewForm(Request $request)
    {
        $data = array();
        echo view('front'.viewPrfx().'register', $data)->render();
    }

    /**
     * View function to display register form in modal
     *
     * @return html/string
     */
    public function forgotPasswordViewForm(Request $request)
    {
        $data = array();
        echo view('front'.viewPrfx().'forgot-password', $data)->render();
    }

    /**
     * Function to process request for logout
     *
     * @return redirect
     */
    public function logout()
    {
        removeSession('candidate');
        \Cookie::queue(\Cookie::forget('remember_me_token_candidate' . appId()));
        return redirect(route('home'));
    }

    /**
     * Page Function to process google redirect
     *
     * @return html
     */
    public function googleRedirect(Request $request)
    {
        $client = $this->getGoogleClient();

        // authenticate code from Google OAuth Flow
        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token['access_token']);

            // get profile info
            $google_oauth = new \Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            $id =  $google_account_info->id;
            $email =  $google_account_info->email;
            $name =  $google_account_info->name;
            $image = $google_account_info->picture;
            $state = $request->get('state') ? base64UrlDecode($request->get('state')) : array();
            $state = $state ? json_decode($state) : '';
            $type = isset($state->type) ? $state->type : '';
            $employer = isset($state->employer) ? $state->employer : '';

            if ($type == 'candidate') {
                $result = CandidateCandidate::createGoogleCandidateIfNotExist($id, $email, $name, $image);
                if ($result) {
                    setSession('candidate', objToArr($result));
                    if ($employer) {
                        return redirect(empUrlBySlug($employer).'login');
                    } else {
                        return redirect(route('home'));
                    }
                }
            } else {
                $result = EmployerEmployer::createGoogleEmployerIfNotExist($id, $email, $name, $image);
                if ($result) {
                    setSession('employer', objToArr($result));
                    return redirect(route('home'));
                }                
            }
            die('Internal account already exist with this email, Please login via regular login');
        }
    }

    /**
     * Page Function to process linkedin redirect
     *
     * @return html
     */
    public function linkedinRedirect(Request $request)
    {
        if ($request->get('code')) {
            $linkedinHelper = new \App\Helpers\LinkedinHelper();
            $accessToken = $linkedinHelper->getAccessToken($_GET['code']);
            $linkedinResult = $linkedinHelper->getLinkedinRefinedData($request, $accessToken);
            $state = issetVal($linkedinResult, 'state') ? base64UrlDecode($linkedinResult['state']) : array();
            $state = $state ? json_decode($state) : '';
            $type = isset($state->type) ? $state->type : '';
            $employer = isset($state->employer) ? $state->employer : '';

            if ($type == 'candidate') {
                $result = CandidateCandidate::createLinkedinCandidateIfNotExist($linkedinResult);
                if ($result) {
                    setSession('candidate', objToArr($result));
                    if ($employer) {
                        return redirect(empUrlBySlug($employer).'account');
                    } else {
                        return redirect(route('home'));
                    }
                } else {
                    die('Internal account already exist with this email, Please login via regular login');
                }
            } else {
               $result = EmployerEmployer::createLinkedinEmployerIfNotExist($linkedinResult);
                if ($result) {
                    setSession('employer', objToArr($result));
                    return redirect(route('home'));
                } else {
                    die('Internal account already exist with this email, Please login via regular login');
                }
            }
        } else {
            return redirect(route('front-acc-resume-listing'));
        }
    }    

    /**
     * Private function to set remember me token for logged in user
     *
     * @return void
     */
    private function setRememberMe($email, $check, $type)
    {
        if ($check) {
            $name = 'remember_me_token_'.$type . appId();
            $tokenValue = $email.'-'.strtotime(date('Y-m-d G:i:s'));
            $time = '1209600'; //Two weeks
            \Cookie::queue(\Cookie::make($name, $tokenValue, $time));
            if ($type == 'candidate') {
                FrontCandidate::storeRememberMeToken($email, $tokenValue);
            } else {
                EmployerEmployer::storeRememberMeToken($email, $tokenValue);
            }
        }
    }

}
