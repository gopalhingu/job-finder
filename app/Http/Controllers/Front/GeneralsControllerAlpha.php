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
use App\Models\Admin\Testimonial;
use App\Models\Admin\Message;
use App\Models\Admin\Page;
use App\Models\Admin\Notification;
use App\Models\Admin\Package;
use App\Models\Admin\Membership;
use App\Models\Admin\Menu;
use App\Models\Employer\Employer as EmployerEmployer;
use App\Models\Admin\Candidate as AdminCandidate;
use App\Models\Front\Candidate as FrontCandidate;
use App\Rules\MinString;
use App\Rules\MaxString;

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
        if (setting('home_pricing') == 'yes') {
            $data['packages'] = Package::getAll();
        }
        if (setting('home_news') == 'yes') {
            $data['news'] = News::getAll(true, setting('home_news_limit'), $request);
        }
        if (setting('home_highlights_section') == 'yes') {
            $data['jobs_count'] = Job::where('status', 1)->count();
            $data['employers_count'] = Employer::where('status', 1)->where('type', 'main')->count();
            $data['candidates_count'] = AdminCandidate::where('status', 1)->count();
            $data['hired_count'] = DB::table('job_applications')->where('status', 'hired')->count();
            $data['employers'] = Employer::where('status', 1)->skip(0)->take(setting('home_portfolio_limit'))->orderBy('employer_id', 'desc')->get();
        }
        if (setting('home_testimonial') == 'yes') {
            $data['testimonials'] = Testimonial::getForHome();
        }
        return view('front.beta.index', $data);
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

        if (setting('enable_site_message_email') == 'yes') {
            $subject = __('message.new_site_message') .' : '. $request->input('subject');
            $this->sendEmail($request->input('message'), setting('admin_email'), $subject);
        }

        Message::storeMessage($request->all());
        Notification::do('message', $subject);

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array(
                'success' => __('message.message').' '.__('message.created')
        )))));
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
        return view('front.reset-password', $data);
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
        if (!$page) {
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
        if (!Menu::itemInMenu('all_companies_page') && setting('allow_all_companies_page') == 'yes') {
            abort(400);
        }

        $companies = Employer::getAll(true, setting('companies_per_page'), 'main', $request->get('sort'));
        $data['companies'] = $companies;
        $data['page'] = $request->get('page');
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
        return view('front'.viewPrfx().'companies.detail', $data);
    }

    /**
     * View function to display features page
     *
     * @return html/string
     */
    public function features(Request $request)
    {
        //Checking if features page is in menu
        if (!Menu::itemInMenu('features')) {
            abort(400);
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
        //Checking if pricing page is in menu
        if (!Menu::itemInMenu('pricing')) {
            abort(400);
        }
        $data['page_title'] = __('message.pricing');
        return view('front'.viewPrfx().'pricing', $data);
    }

    /**
     * View function to display contact page
     *
     * @return html/string
     */
    public function contact(Request $request)
    {
        //Checking if contact page is in menu
        if (!Menu::itemInMenu('contact')) {
            abort(400);
        }
        $data['page_title'] = __('message.contact');
        return view('front'.viewPrfx().'contact', $data);
    }
    
    /**
     * View function to display login form
     *
     * @return html/string
     */
    public function loginView(Request $request)
    {
        $data = array();
        echo view('front.login', $data)->render();
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
        } else {
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
