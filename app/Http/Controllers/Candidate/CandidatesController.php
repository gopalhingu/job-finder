<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Candidate\Candidate;
use App\Models\Admin\Notification;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

class CandidatesController extends Controller
{
    /**
     * View function to display login page for candidate
     *
     * @return html/string
     */
    public function loginView(Request $request, $slug = null)
    {
        if (candidateSession()) {
            return redirect(routeWithSlug('can-acc-main'));
        } else if ($request->cookie('remember_me_token_candidate' . appId())) {
            $candidateWithToken = Candidate::getCandidateWithRememberMeToken(
                $request->cookie('remember_me_token_candidate' . appId())
            );
            if ($candidateWithToken) {
                setSession('candidate', $candidateWithToken);
                return redirect(routeWithSlug('can-acc-main'));
            } else {
                $this->logout();
            }
        }

        $data['page'] = __('message.login').' | ' . settingEmpSlug('site_name');
        $data['breadcrumb_title'] = __('message.login');
        $data['settings'] = setting();
        $data['slug'] = $slug;

        if (setting('enable_google_login') == 'yes') {
            $client = $this->getGoogleClient('candidate');
            $data['googleLogin'] = $client->createAuthUrl();
        } else {
            $data['googleLogin'] = '';
        }

        if (setting('enable_linkedin_login') == 'yes') {
            $linkedinHelper = new \App\Helpers\LinkedinHelper();
            $data['linkedinLogin'] = $linkedinHelper->getLink('candidate');
        } else {
            $data['linkedinLogin'] = '';
        }

        return view('candidate'.viewPrfx().'login', $data);
    }

    /**
     * Post Function to process login request by candidate
     *
     * @return html/string
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'password.required' => __('validation.required')
        ]);        

        $email = $request->input('email');
        $candidate = Candidate::login($email, $request->input('password'));
        if ($candidate) {
            setSession('candidate', objToArr($candidate));
            $this->setRememberMe($email, $request->input('remember'));
            return redirect(routeWithSlug('can-acc-main'));
        } else {
            return redirect(routeWithSlug('candidate-login-view'))->withErrors([__('message.email_password_error')]);
        }

        return redirect(routeWithSlug('candidate-login-view'));
    }

    /**
     * View Function to display register page for candidate
     *
     * @return html/string
     */
    public function registerView(Request $request)
    {
        if (candidateSession()) {
            return redirect(routeWithSlug('can-acc-main'));
        } else if ($request->cookie('remember_me_token_candidate' . appId())) {
            $candidateWithToken = Candidate::getCandidateWithRememberMeToken(
                $request->cookie('remember_me_token_candidate' . appId())
            );
            if ($candidateWithToken) {
                setSession('candidate', objToArr($candidateWithToken)); 
                return redirect(routeWithSlug('can-acc-main'));
            } else {
                $this->logout();
            }
        }

        $data['page'] = __('message.register').' | ' . settingEmpSlug('site_name');
        $data['breadcrumb_title'] = __('message.register');
        if (setting('enable_candidate_registeration') == 'yes') {
            return view('candidate'.viewPrfx().'register', $data);
        } else {
            return view('candidate'.viewPrfx().'404', $data);
        }
    }

    /**
     * Post Function to register a candidate
     *
     * @return html/string
     */
    public function register(Request $request)
    {
        $this->checkIfDemo();

        //Validating user input
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', new MinString(2), new MaxString(50)],
            'last_name' => ['required', new MinString(2), new MaxString(50)],
            'email' => 'required|email|unique:candidates',
            'password' => 'required|required_with:retype_password|same:retype_password',
            'retype_password' => 'required',
        ], [
            'first_name.required' => __('validation.required'),
            'first_name.min' => __('validation.min_string'),
            'first_name.max' => __('validation.max_string'),
            'last_name.required' => __('validation.required'),
            'last_name.min' => __('validation.min_string'),
            'last_name.max' => __('validation.max_string'),
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'password.required' => __('validation.required'),            
            'retype_password.required' => __('validation.required'),            
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Sending verification email for candidate
        if (setting('enable_candidate_email_verification') == 'yes') {
            $candidate = Candidate::createCandidate($request->all(), true);
            $tagsWithValues = array(
                '((site_link))' => empUrl(),
                '((site_logo))' => settingEmpSlug('site_logo'),
                '((first_name))' => $candidate['first_name'],
                '((last_name))' => $candidate['last_name'],
                '((email))' => $candidate['email'],
                '((link))' => empUrl().'activate-account/'.$candidate['token'],
            );
            $messageEmail = replaceTagsInTemplate2(setting('candidate_verify_email'), $tagsWithValues);
            $this->sendEmail($messageEmail, $candidate['email'], __('message.activate_your_account'));
            $message = __('message.a_verification_email_has_been_sent');
        } else {
            $candidate = Candidate::createCandidate($request->all());
            $message = __('message.account_created_please_login');
        }
        
        //Sending new candidate signup notification to admin
        if (setting('enable_candidate_register_notification') == 'yes') {
            $tagsWithValues = array(
                '((site_link))' => url('/'),
                '((site_logo))' => setting('site_logo'),
                '((first_name))' => $candidate['first_name'],
                '((last_name))' => $candidate['last_name'],
                '((email))' => $candidate['email'],
            );
            $messageEmail = replaceTagsInTemplate2(setting('candidate_signup'), $tagsWithValues);
            $this->sendEmail($messageEmail, setting('admin_email'), __('message.new_candidate_signup'));
        }

        //Creating system notification
        $notif = __('message.new_candidate_signup').' ('.$candidate['first_name'].' '.$candidate['last_name'].')';
        Notification::do('candidate_signup', $notif);

        //Giving success message
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => $message))
        )));
    }

    /**
     * Private function to set remember me token for logged in user
     *
     * @return void
     */
    private function setRememberMe($email, $check)
    {
        if ($check) {
            $name = 'remember_me_token_candidate' . appId();
            $tokenValue = $email.'-'.strtotime(date('Y-m-d G:i:s'));
            $time = '1209600'; //Two weeks
            \Cookie::queue(\Cookie::make($name, $tokenValue, $time));
            Candidate::storeRememberMeToken($email, $tokenValue);
        }
    }

    /**
     * Function to process request for logout
     *
     * @return redirect
     */
    public function logout($slug = null, $noRedirect = false)
    {
        removeSession('candidate');
        \Cookie::queue(\Cookie::forget('remember_me_token_candidate' . appId()));
        if (!$noRedirect) {
            return redirect(routeWithSlug('candidate-login-view'));
        }
    }

    /**
     * View Function to display register page for user
     *
     * @return html/string
     */
    public function showForgotPassword()
    {
        if (setting('enable_candidate_forgot_password') == 'yes') {
            $data['page'] = __('message.forgot_password') . ' | ' . settingEmpSlug('site_name');
            $data['breadcrumb_title'] = __('message.forgot_password');
            return view('candidate'.viewPrfx().'forgot-password', $data);
        } else {
            return redirect(routeWithSlug('candidate-home'));
        }
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

        $existing = Candidate::getFirst('candidates.email', $request->input('email'));
        if ($existing['candidate_id'] == '') {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.email_does_not_exist')))
            )));
        }

        //Creating token for candidate
        Candidate::createTokenForCandidate($request->input('email'));

        //Sending mail
        $candidate = Candidate::getFirst('candidates.email', $request->input('email'));
        $tagsWithValues = array(
            '((site_link))' => url(empSlug()),
            '((site_logo))' => settingEmpSlug('site_logo'),
            '((first_name))' => $candidate['first_name'],
            '((last_name))' => $candidate['last_name'],
            '((link))' => empUrl().'reset-password/'.$candidate['token'],
        );
        $messageEmail = replaceTagsInTemplate2(setting('candidate_reset_password'), $tagsWithValues);
        $this->sendEmail($messageEmail, $candidate['email'], __('message.create_new_password'));

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
    public function resetPassword($token = null)
    {
        $data['token'] = $token;
        $data['page'] = __('message.reset_password') . ' | ' . setting('site_name');
        $data['breadcrumb_title'] = __('message.forgot_password');
        return view('candidate'.viewPrfx().'reset-password', $data);
    }

    /**
     * Function (for ajax) to process password reset form request
     *
     * @return redirect
     */
    public function updatePasswordByForgot(Request $request, $slug = null, $token = null)
    {
        $this->checkIfDemo();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|required_with:retype_password|same:retype_password',
            'retype_password' => 'required',
        ], [
            'token.required' => __('validation.required'),
            'password.required' => __('validation.required'),
            'retype_new_password.required' => __('validation.required')
        ]);

        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $existing = Candidate::getFirst('token', $token);
        if ($existing['candidate_id'] == '') {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.token_mismatch')))
            )));
        }

        Candidate::updatePasswordByField('token', $request->input('token'), Hash::make($request->input('password')));
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.password_updated')))
        )));
    }    

    /**
     * View Function to display profile update page for candidate
     *
     * @return html/string
     */
    public function updateProfileView($id = null)
    {
        $data['page'] = __('message.update_profile'). ' | ' . settingEmpSlug('site_name');
        $data['breadcrumb_title'] = __('message.update_profile');
        $data['menu'] = 'profile';
        $data['candidate'] = Candidate::getFirst('candidates.candidate_id', candidateSession());
        return view('candidate'.viewPrfx().'account-profile', $data);
    }    

    /**
     * Function (for ajax) to process profile update form request
     *
     * @return redirect
     */
    public function updateProfile(Request $request)
    {
        $this->checkIfDemo();

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', new MinString(2), new MaxString(50)],
            'last_name' => ['required', new MinString(2), new MaxString(50)],
            'email' => 'required|email|unique:candidates,email,'.candidateSession().',candidate_id',
            'phone1' => 'required|digits_between:0,50',
            'phone2' => 'digits_between:0,50',
            'city' => ['required', new MinString(2), new MaxString(50)],
            'country' => ['required', new MinString(2), new MaxString(50)],
            'dob' => 'required',
            'state' => ['required', new MinString(2), new MaxString(50)],
            'address' => ['required', new MinString(2), new MaxString(50)],
            'bio' => ['required', new MinString(2), new MaxString(2000)],
        ], [
            'first_name.required' => __('validation.required'),
            'first_name.min' => __('validation.min_string'),
            'first_name.max' => __('validation.max_string'),
            'last_name.required' => __('validation.required'),
            'last_name.min' => __('validation.min_string'),
            'last_name.max' => __('validation.max_string'),
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'email.unique' => __('validation.unique'),
            'phone1.required' => __('validation.required'),
            'phone1.digits_between' => __('validation.digits_between'),
            'phone2.digits_between' => __('validation.digits_between'),
            'city.required' => __('validation.required'),
            'city.min' => __('validation.min_string'),
            'city.max' => __('validation.max_string'),
            'country.required' => __('validation.required'),
            'country.min' => __('validation.min_string'),
            'country.max' => __('validation.max_string'),
            'dob.required' => __('validation.required'),
            'state.required' => __('validation.required'),
            'state.min' => __('validation.min_string'),
            'state.max' => __('validation.max_string'),
            'address.required' => __('validation.required'),
            'address.min' => __('validation.min_string'),
            'address.max' => __('validation.max_string'),
            'bio.required' => __('validation.required'),
            'bio.min' => __('validation.min_string'),
            'bio.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }


        //Uploading new
        $fileUpload = $this->uploadPublicFile(
            $request, 
            'image', 
            config('constants.upload_dirs.candidates'),
            array('image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(generalFileUploadLimit())]),
            array('image.image' => __('validation.image'))
        );


        //Deleting existing/old file
        if (issetVal($fileUpload, 'success') == 'true') {
            $candidate = objToArr(Candidate::getFirst('candidates.candidate_id', candidateSession()));
            $this->deleteOldFile($candidate['image']);
        }

        Candidate::updateProfile($request->all(), issetVal($fileUpload, 'message'));
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.profile_updated')))
        ));
    }

    /**
     * View Function to display password update page for candidate
     *
     * @return html/string
     */
    public function updatePasswordView($id = null)
    {
        $data['page'] = __('message.update_password').' | ' . setting('site_name');
        $data['breadcrumb_title'] = __('message.update_password');
        $data['menu'] = 'password';
        return view('candidate'.viewPrfx().'account-password', $data);
    }

    /**
     * Function (for ajax) to process password reset form request
     *
     * @return redirect
     */
    public function updatePassword(Request $request)
    {
        $this->checkIfDemo();

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|required_with:retype_password|same:retype_password',
            'retype_password' => 'required',
        ], [
            'old_password.required' => __('validation.required'),
            'new_password.required' => __('validation.required'),
            'retype_password.required' => __('validation.required'),
        ]);

        if ($validator->fails()) {
            $errors =  $validator->messages()->toArray();
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        if (!Candidate::checkExistingPassword($request->input('old_password'))) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.old_password_do_not_match')))
            )));
        }

        Candidate::updatePasswordByField(
            'candidates.candidate_id',
            candidateSession(),
            Hash::make($request->input('new_password'))
        );
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.password_updated')))
        ));
    }

    /**
     * Function to activate account 
     * e.g. resulting function for click on email
     * 
     * @return redirect
     */
    public function activateAccount($slug = null, $token = null)
    {
        $result = Candidate::activateAccount($token);
        if ($result) {
            $data['page'] = __('message.account_activation');
            $data['breadcrumb_title'] = __('message.account_activation');
            return view('candidate'.viewPrfx().'activate-account', $data);
        } else {
            return redirect(empUrl());
        }
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

            $result = Candidate::createGoogleCandidateIfNotExist($id, $email, $name, $image);
            if ($result) {
                setSession('candidate', objToArr($result));
                $employer = json_decode(base64UrlDecode($request->get('state')));
                return redirect(empUrlBySlug($employer->employer).'login');
            } else {
                $pagedata['page'] = __('message.user_exist_with_this_email');
                return view('candidate'.viewPrfx().'user-existing-account', $pagedata);
            }
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
            $result = Candidate::createLinkedinCandidateIfNotExist($linkedinResult);
            if ($result) {
                setSession('candidate', objToArr($result));
                $employer = json_decode(base64UrlDecode($linkedinResult['state']));
                return redirect(empUrlBySlug($employer->employer).'account');
            } else {
                $pagedata['page'] = __('message.user_exist_with_this_email');
                return view('candidate'.viewPrfx().'user-existing-account', $pagedata);
            }
        } else {
            return redirect(routeWithSlug('can-acc-main'));
        }
    }   
}

