<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\Employer as AdminEmployer;
use App\Models\Admin\Company as AdminCompany;
use App\Models\Employer\Employer;
use App\Models\Employer\Role;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;
use App\Models\Company\Company as Company;

class CompanysController extends Controller
{
    /**
     * View Function to display login page
     *
     * @return html/string
     */   
    public function loginView(Request $request)
    {
        if (companySession()) {
            return redirect(route('company-dashboard'));
        } else if ($request->cookie('remember_me_token_company' . appId())) {
            $employerWithToken = Company::getEmployerWithRememberMeToken($request->cookie('remember_me_token_company' . appId()));
            if ($employerWithToken) {
                setSession('company', $employerWithToken);
                return redirect(route('company-dashboard'));
            } else {
                $this->logout(true);
            }
        }
        $data['page'] = __('message.login');
        return view('company.login', $data);
    }

    /**
     * Function to process login form request
     *
     * @return redirect
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

        $company = objToArr(Company::login($request->input('email'), $request->input('password')));
        if ($company) {
            setSession('company', $company);
            $this->setRememberMe($request->input('email'), $request->input('rememberme'));
            return redirect()->route('company-dashboard');
        } else {
			return redirect()->route('company-login')->withErrors([__('message.email_password_error')]);
        }

        return redirect(route('company-login'));
    }

    /**
     * View Function to display forgot password page view
     *
     * @return html/string
     */
    public function forgotPasswordView()
    {
        $data['page'] = __('message.login');
    	return view('company.forgot-password', $data);
    }

    /**
     * Function to process forgot password form request
     *
     * @return redirect
     */
    public function forgotPassword(Request $request)
    {
        $this->checkIfDemo('reload');

        //Validations
		$request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
        ]);

        $company = Company::checkCompanyByEmail($request->input('email'));
        if ($company) {
            $token = Company::saveTokenForPasswordReset($request->input('email'));
            $message = __('message.an_email_with_a_link_to_reset');
            $tagsWithValues = array(
                '((site_link))' => url('/'),
                '((site_logo))' => setting('site_logo'),
                '((first_name))' => $company['first_name'],
                '((last_name))' => $company['last_name'],
                '((link))' => url('/').'/company/reset-password/'.$token,
            );
            $messageEmail = replaceTagsInTemplate2(setting('company_reset_password'), $tagsWithValues);
            $subject = setting('site_name').' : '.__('message.your_password_reset_link');
            $this->sendEmail($messageEmail, $company['email'], $subject);
        } else {
        	return redirect()->route('company-forgot-password')->withErrors([__('message.email_not_found')]);
        }

        return redirect()->route('company-forgot-password')->with('message', $message);
    }

    /**
     * View Function to display reset password page view
     *
     * @param string $token
     * @return html/string
     */
    public function resetPasswordView($token = NULL)
    {
        $data['page'] = __('message.login');
        $data['token'] = $token;
    	return view('company.reset-password', $data);
    }

    /**
     * Function to process reset password form request
     *
     * @return redirect
     */
    public function resetPassword(Request $request)
    {
        $this->checkIfDemo('reload');

		$request->validate([
            'token' => 'required',
            'new_password' => 'required|required_with:retype_new_password|same:retype_new_password',
            'retype_new_password' => 'required',
        ], [
        	'token.required' => __('validation.required'),
            'new_password.required' => __('validation.required'),
            'retype_new_password.required' => __('validation.required')
        ]);

		if (!Company::checkIfTokenExist($request->input('token'))) {
            return redirect()->route('company-reset-password', ['token' => $request->input('token')])
            	->withErrors([__('message.invalid_request_please_regenerate')]);
        } else {
            Company::updatePasswordByField('token', $request->input('token'), $request->input('new_password'));
            return redirect()->route('company-login')->with('message', __('message.your_password_has_been_successfully').'. '.__('message.login_with_new_password'));
        }
    }

    /**
     * Function to process request for logout
     *
     * @return redirect
     */
    public function logout($noRedirect = false)
    {
    	removeSession('company');
        \Cookie::queue(\Cookie::forget('remember_me_token_company' . appId()));
        if (!$noRedirect) {
            return redirect(route('company-login'));
        }
    }

    /**
     * View Function to display profile page view
     *
     * @return html/string
     */
    public function profileView()
    {
        $data['page'] = __('message.profile');
        $data['menu'] = 'profile';
        $data['profile'] = objToArr(Company::getCompany('company.company_id', encode(companySession())));
        return view('company.profile', $data);
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
            'email' => 'required|email|unique:company,email,'.companySession().',company_id',
            'phone1' => 'digits_between:0,50',
            'phone2' => 'digits_between:0,50',
            'city' => [new MinString(2), new MaxString(50)],
            'state' => [new MinString(2), new MaxString(50)],
            'country' => [new MinString(2), new MaxString(50)],
            'address' => [new MinString(2), new MaxString(50)],
            'no_of_employees' => [new MinString(2), new MaxString(50)],
            'industry' => [new MinString(2), new MaxString(50)],
            'founded_in' => [new MinString(2), new MaxString(50)],
            'url' => ['url', new MinString(10), new MaxString(250)],
            'twitter' => ['url', new MinString(10), new MaxString(250)],
            'facebook' => ['url', new MinString(10), new MaxString(250)],
            'instagram' => ['url', new MinString(10), new MaxString(250)],
            'google' => ['url', new MinString(10), new MaxString(250)],
            'linkedin' => ['url', new MinString(10), new MaxString(250)],
            'youtube' => ['url', new MinString(10), new MaxString(250)],
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'dimensions:max_height=300,max_width=300', new MaxFile(generalFileUploadLimit())],     
            'logo' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'dimensions:max_height=300,max_width=300', new MaxFile(generalFileUploadLimit())],     
		], [
        	'first_name.required' => __('validation.required'),
        	'first_name.min' => __('validation.min_string'),
        	'first_name.max' => __('validation.max_string'),
        	'last_name.required' => __('validation.required'),
        	'last_name.min' => __('validation.min_string'),
        	'last_name.max' => __('validation.max_string'),
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
        	'phone1.digits_between' => __('validation.digits_between'),
            'phone2.digits_between' => __('validation.digits_between'),
            'city.min' => __('validation.min_string'),
            'city.max' => __('validation.max_string'),
            'state.min' => __('validation.min_string'),
            'state.max' => __('validation.max_string'),
            'country.min' => __('validation.min_string'),
            'country.max' => __('validation.max_string'),
            'address.min' => __('validation.min_string'),
            'address.max' => __('validation.max_string'),
            'no_of_employees.min' => __('validation.min_string'),
            'no_of_employees.max' => __('validation.max_string'),
            'industry.min' => __('validation.min_string'),
            'industry.max' => __('validation.max_string'),
            'founded_in.min' => __('validation.min_string'),
            'founded_in.max' => __('validation.max_string'),
            'url.url' => __('validation.url'),
            'url.min' => __('validation.min_string'),
            'url.max' => __('validation.max_string'),
            'twitter.url' => __('validation.url'),
            'twitter.min' => __('validation.min_string'),
            'twitter.max' => __('validation.max_string'),
            'facebook.url' => __('validation.url'),
            'facebook.min' => __('validation.min_string'),
            'facebook.max' => __('validation.max_string'),
            'instagram.url' => __('validation.url'),
            'instagram.min' => __('validation.min_string'),
            'instagram.max' => __('validation.max_string'),
            'google.url' => __('validation.url'),
            'google.min' => __('validation.min_string'),
            'google.max' => __('validation.max_string'),
            'linkedin.url' => __('validation.url'),
            'linkedin.min' => __('validation.min_string'),
            'linkedin.max' => __('validation.max_string'),
            'youtube.url' => __('validation.url'),
            'youtube.min' => __('validation.min_string'),
            'youtube.max' => __('validation.max_string'),
            'image.image' => __('validation.image'),
            'image.dimensions' => __('validation.dimensions').' ('.__('message.max_allowed').' 300x300px)',
            'logo.image' => __('validation.image'),
            'logo.dimensions' => __('validation.dimensions').' ('.__('message.max_allowed').' 300x300px)',
		]);
		if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
		}

        //Uploading image if any
        $path = companySession('type') == 'main' ? config('constants.upload_dirs.companys') : companyPath().'/team/';
        $imageUpload = $this->uploadPublicFile(
        	$request, 
            'image', 
            $path,
        	array('image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'dimensions:max_height=300,max_width=300', new MaxFile(generalFileUploadLimit())]),
        	array(
                'image.image' => __('validation.image'),
                'image.dimensions' => __('validation.dimensions').' ('.__('message.max_allowed').' 300x300px)'
            )
        );

        //Deleting existing image if new is uploaded
        if (issetVal($imageUpload, 'success') == 'true') {
        	$company = objToArr(Company::getCompany('company_id', encode(companySession())));
            $this->deleteOldFile($company['image']);
        }


        //Uploading logo if any
        $logoUpload = $this->uploadPublicFile(
            $request, 
            'logo', 
            config('constants.upload_dirs.companys'),
            array('logo' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'dimensions:max_height=300,max_width=300', new MaxFile(generalFileUploadLimit())]),
            array(
                'logo.image' => __('validation.image'),
                'logo.dimensions' => __('validation.dimensions').' ('.__('message.max_allowed').' 300x300px)'
            )
        );

        //Deleting existing logo if new is uploaded
        if (issetVal($logoUpload, 'success') == 'true') {
            $company = objToArr(Company::getCompany('company_id', encode(companySession())));
            $this->deleteOldFile($company['logo']);
        }

    	//updating db recrod
        Company::updateProfile($request->all(), issetVal($imageUpload, 'message'), issetVal($logoUpload, 'message'));

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.profile_updated')))
        )));
    }

    /**
     * View Function to display profile page view
     *
     * @return html/string
     */
    public function passwordView()
    {
        $data['page'] = __('message.password');
        $data['menu'] = 'password';
        $data['profile'] = objToArr(Employer::getEmployer('employer_id', employerSession()));
		return view('employer.password', $data);
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

		if (!Employer::checkExistingPassword($request->input('old_password'))) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.old_password_do_not_match')))
            )));
        } else {
            Employer::updatePasswordByField('employer_id', employerSession(), $request->input('new_password'));
            die(json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('message' => __('message.password_updated')))
            )));
        }
    }

    /**
     * View Function to display employers list view page
     *
     * @return html/string
     */
    public function teamListView()
    {
        $data['page'] = __('message.team');
        $data['menu'] = 'team';
        $data['roles'] = objToArr(Role::getAll());
        return view('employer.team.list', $data);
    }

    /**
     * Function to get data for employers jquery datatable
     *
     * @return json
     */
    public function teamList(Request $request)
    {
        echo json_encode(Employer::employersList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $employer_id
     * @return html/string
     */
    public function createOrEdit($employer_id = NULL)
    {
        $data['employer'] = objToArr(Employer::getEmployer('employer_id', $employer_id));
        $data['roles'] = objToArr(Role::getAll());
        $data['employerRoles'] = explode(',', Role::getEmployerRoles($employer_id));
        echo view('employer.team.create-or-edit', $data)->render();
    }

    /**
     * Function (for ajax) to process employer create or edit form request
     *
     * @return redirect
     */
    public function saveEmployer(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('employer_id') ? $request->input('employer_id') : false;

        $this->checkActiveTeam($edit, $request->input('status'));

        $rules['first_name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['last_name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['email'] = 'required|email';
        $rules['phone1'] = 'digits_between:0,50';
        $rules['phone2'] = 'digits_between:0,50';
        $rules['image'] = ['image', 'mimes:jpeg,png,jpg,gif,svg', 'dimensions:max_height=300,max_width=300', new MaxFile(generalFileUploadLimit())];
        if (!$edit) {
            $rules['password'] = ['required', new MinString(2), new MaxString(50)];
        }

        $validator = Validator::make($request->all(), $rules, [
            'first_name.required' => __('validation.required'),
            'first_name.min' => __('validation.min_string'),
            'first_name.max' => __('validation.max_string'),
            'last_name.required' => __('validation.required'),
            'last_name.min' => __('validation.min_string'),
            'last_name.max' => __('validation.max_string'),
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'password.required' => __('validation.required'),
            'phone1.digits_between' => __('validation.digits_between'),
            'phone2.digits_between' => __('validation.digits_between'),
            'image.image' => __('validation.image'),
            'image.dimensions' => __('validation.dimensions').' ('.__('message.max_allowed').' 300x300px)'
        ]);

        //If validation fails
        if ($validator->fails()) {
            $errors =  $validator->messages()->toArray();
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Checking for duplicates
        if (Employer::valueExist('email', $request->input('email'), $edit)) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.use_another_email')))
            )));
        }

        //Deleting old file if any
        if ($edit && $request->input('image')) {
            $oldFile = Employer::getEmployer('employers.employer_id', $edit);
            $this->deleteOldFile($oldFile['image']);
        }

        //Uploading new file
        $fileUpload = $this->uploadPublicFile(
            $request, 'image', employerPath().'/team/', 
            array('image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(generalFileUploadLimit())]),
            array('image.image' => __('validation.image'))
        );

        //updating db recrod
        Employer::storeEmployer($request->all(), $edit, issetVal($fileUpload, 'message'));

        //Sending notification to team member if selected
        if ($request->input('notify_team_member')) {
            $tagsWithValues = array(
                '((site_link))' => url('/'),
                '((site_logo))' => setting('site_logo'),
                '((first_name))' => $request->input('first_name'),
                '((last_name))' => $request->input('last_name'),
                '((email))' => $request->input('email'),
                '((password))' => $request->input('password'),
                '((link))' => url('/').'/employer',
            );
            $messageEmail = replaceTagsInTemplate2(settingEmp('team_creation'), $tagsWithValues);
            $subject = settingEmp('site_name').' : '.__('message.team_account_created');
            $this->sendEmail($messageEmail, $request->input('email'), $subject);
        }

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array(
                'success' => __('message.employer').' ' . ($edit ? __('message.updated') : __('message.created'))
        )))));
    }

    /**
     * Function (for ajax) to process employer create or edit form request
     *
     * @return redirect
     */
    public function saveEmployerRoles(Request $request)
    {
        $this->checkIfDemo();

        $validator = Validator::make($request->all(), [
            'roles' => 'required',
        ], [
            'roles.required' => __('validation.required'),
        ]);

        if ($validator->fails()) {
            $errors =  $validator->messages()->toArray();
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        Employer::storeEmployerRolesBulk($request->all());
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => 'created'))
        ));
    }

    /**
     * Function (for ajax) to process employer change status request
     *
     * @param integer $employer_id
     * @param string $status
     * @return void
     */
    public function changeStatus($employer_id = null, $status = null)
    {
        $this->checkIfDemo();

        if ($status == '0') {
            $this->checkActiveTeam();
        }

        Employer::changeStatus($employer_id, $status);
    }

    /**
     * Function (for ajax) to process employer delete request
     *
     * @param integer $employer_id
     * @return void
     */
    public function delete($employer_id)
    {
        $this->checkIfDemo();

        $oldFile = Employer::getEmployer('employers.employer_id', $employer_id);
        $this->deleteOldFile($oldFile['image']);

        Employer::remove($employer_id);
    }

    /**
     * Private function to set remember me token for logged in employer
     *
     * @return void
     */
    private function setRememberMe($email, $check)
    {
        if ($check) {
            $name = 'remember_me_token_employer' . appId();
            $tokenValue = $email.'-'.strtotime(date('Y-m-d G:i:s'));
            $time = '1209600'; //Two weeks
            \Cookie::queue(\Cookie::make($name, $tokenValue, $time));
            Employer::storeRememberMeToken($email, $tokenValue);
        }
    }

    /**
     * Function to activate account 
     * e.g. resulting function for click on email
     * 
     * @return redirect
     */
    public function activateAccount($token = null)
    {
        $company = Company::activateAccount($token);
        if ($company) {
            //Importing company settings if not imported
            AdminCompany::importCompanySettings($company);

            if (setting('import_company_dummy_data_on_signup') == 'yes') {
                AdminCompany::importCompanyDummyData($company);
            }

            //Removing any previously logged in company account
            removeSession('company');

            $data['page'] = __('message.activate_account');
            return view('company.activate-account', $data);
        } else {
            $content = '';
            $content .= '<strong><h3>'.__('message.some_error_occured').'</h3></strong>';
            $content .= '<br />';
            $content .= '<a href="'.url('/').'/employer">'.__('message.try_again').'</a>';
            die($content);
        }
    }

    /**
     * Function to check active membership content
     *
     * @return html
     */
    private function checkActiveTeam($id = '', $status = '')
    {
        if ($id != '' && $status == '0') {
            return false;
        }

        //Checking if allowed in membership
        $totalActiveTeams = Employer::getTotalTeam($id);
        $totalAllowedActiveTeams = empMembership(employerId(), 'active_users');
        if ($totalAllowedActiveTeams == '-1') {
            return false;
        }        
        if ($totalActiveTeams >= $totalAllowedActiveTeams) {
            $detail = '<br />'.__('message.current_active').' : '.$totalActiveTeams;
            $detail .= '<br />'.__('message.allowed_in_membership').' : '.$totalAllowedActiveTeams.'<br />';
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.active_teams_limit_message').$detail))
            )));
        }        
    }    
}