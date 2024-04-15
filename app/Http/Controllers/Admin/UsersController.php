<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\User;
use App\Models\Admin\Role;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

use SimpleExcel\SimpleExcel;

class UsersController extends Controller
{
    /**
     * View Function to display login page
     *
     * @return html/string
     */   
    public function loginView(Request $request)
    {
        if (adminSession()) {
            return redirect(route('admin-dashboard'));
        } else if ($request->cookie('remember_me_token_admin' . appId())) {
            $userWithToken = User::getUserWithRememberMeToken($request->cookie('remember_me_token_admin' . appId()));
            if ($userWithToken) {
                setSession('admin', $userWithToken);
                return redirect(route('admin-dashboard'));
            } else {
                $this->logout(true);
            }
        }
        return view('admin.users.outer.login');
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

        $user = objToArr(User::login($request->input('email'), $request->input('password')));
        if ($user) {
            setSession('admin', $user);
            $this->setRememberMe($request->input('email'), $request->input('rememberme'));
            return redirect()->route('admin-dashboard');
        } else {
			return redirect()->route('admin-login')->withErrors([__('message.email_password_error')]);
        }

        return redirect(route('admin-login'));
    }

    /**
     * View Function to display forgot password page view
     *
     * @return html/string
     */
    public function forgotPasswordView()
    {
    	return view('admin.users.outer.forget-password');
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

        $user = User::checkUserByEmail($request->input('email'));
        if ($user) {
            $token = User::saveTokenForPasswordReset($request->input('email'));
            $message = __('message.an_email_with_a_link_to_reset');
            $tagsWithValues = array(
                '((site_link))' => url('/'),
                '((site_logo))' => setting('site_logo'),
                '((first_name))' => $user->first_name,
                '((last_name))' => $user->last_name,
                '((link))' => url('/').'/admin/reset-password/'.$token,
            );
            $messageEmail = replaceTagsInTemplate2(setting('employer_reset_password'), $tagsWithValues);
            $this->sendEmail($messageEmail, $user->email, __('message.your_password_reset_link'));
        } else {
        	return redirect()->route('admin-forgot-pass')->withErrors([__('message.email_not_found')]);
        }

        return redirect()->route('admin-forgot-pass')->with('message', $message);
    }

    /**
     * View Function to display reset password page view
     *
     * @param string $token
     * @return html/string
     */
    public function resetPasswordView($token = NULL)
    {
    	return view('admin.users.outer.reset-password', compact('token'));
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

		if (!User::checkIfTokenExist($request->input('token'))) {
            return redirect()->route('admin-reset-pass', ['token' => $request->input('token')])
            	->withErrors([__('message.invalid_request_please_regenerate')]);
        } else {
            User::updatePasswordByField('token', $request->input('token'), $request->input('new_password'));
            return redirect()->route('admin-login')->with('message', __('message.your_password_has_been_successfully').','.__('message.login_with_new_password'));
        }
    }

    /**
     * Function to process request for logout
     *
     * @return redirect
     */
    public function logout($noRedirect = false)
    {
    	removeSession('admin');
        \Cookie::queue(\Cookie::forget('remember_me_token_admin' . appId()));
        if (!$noRedirect) {
            return redirect(route('admin-login'));
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
        $data['profile'] = objToArr(User::getUser('users.user_id', adminSession()));
        return view('admin.users.inner.profile', $data);
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
            'email' => 'required|email|unique:users,email,'.adminSession().',user_id',
            'username' => ['required', new MinString(2), new MaxString(50), 'unique:users,username,'.adminSession().',user_id'],
            'phone' => 'digits_between:0,50',
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'dimensions:max_height=250,max_width=250', new MaxFile(adminFileUploadLimit())]
		], [
        	'first_name.required' => __('validation.required'),
        	'first_name.min' => __('validation.min_string'),
        	'first_name.max' => __('validation.max_string'),
        	'last_name.required' => __('validation.required'),
        	'last_name.min' => __('validation.min_string'),
        	'last_name.max' => __('validation.max_string'),
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
        	'username.required' => __('validation.required'),
        	'username.min' => __('validation.min_string'),
        	'username.max' => __('validation.max_string'),
        	'phone.digits_between' => __('validation.digits_between'),
            'image.image' => __('validation.image'),
            'image.dimensions' => __('validation.dimensions').' ('.__('message.max_allowed').' 250x250px)'            
		]);

		if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
		}

        $fileUpload = $this->uploadPublicFile(
        	$request, 'image', config('constants.upload_dirs.users'), 
        	array('image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(adminFileUploadLimit())]),
        	array('image.image' => __('validation.image'))
        );

		if (issetVal($fileUpload, 'success') == 'false') {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => issetVal($fileUpload, 'message')))
            )));
        }

        //Deleting existing file
        if (issetVal($fileUpload, 'success') == 'true') {
        	$user = objToArr(User::getUser('user_id', adminSession()));
            $this->deleteOldFile($user['image']);
        }

    	//updating db recrod
        User::updateProfile($request->all(), issetVal($fileUpload, 'message'));

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
        $data['profile'] = objToArr(User::getUser('user_id', adminSession()));
		return view('admin.users.inner.password', $data);
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
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
		}        

		if (!User::checkExistingPassword($request->input('old_password'))) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.old_password_do_not_match')))
            )));
        } else {
            User::updatePasswordByField('user_id', adminSession(), $request->input('new_password'));
            die(json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('message' => __('message.password_updated')))
            )));
        }
    }

    /**
     * View Function to display users list view page
     *
     * @return html/string
     */
    public function usersListView()
    {
        $data['page'] = __('message.users');
        $data['menu'] = 'users';
        $data['roles'] = objToArr(Role::getAll());
        return view('admin.users.list', $data);
    }

    /**
     * Function to get data for users jquery datatable
     *
     * @return json
     */
    public function usersList(Request $request)
    {
        echo json_encode(User::usersList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $user_id
     * @return html/string
     */
    public function createOrEdit($user_id = NULL)
    {
        $data['user'] = objToArr(User::getUser('user_id', $user_id));
        $data['roles'] = objToArr(Role::getAll());
        $data['userRoles'] = explode(',', Role::getUserRoles($user_id));
        echo view('admin.users.create-or-edit', $data)->render();
    }

    /**
     * Function (for ajax) to process user create or edit form request
     *
     * @return redirect
     */
    public function saveUser(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('user_id') ? $request->input('user_id') : false;
        $rules['first_name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['last_name'] = ['required', new MinString(2), new MaxString(50)];
        if ($edit) {
            $rules['email'] = 'required|email|unique:users,email,'.$edit.',user_id';
            $rules['username'] = ['required', new MinString(2), new MaxString(50), 'unique:users,username,'.$edit.',user_id'];
        } else {
            $rules['email'] = 'required|email|unique:users';
            $rules['username'] = ['required', new MinString(2), new MaxString(50), 'unique:users'];
            $rules['password'] = 'required';            
        }
        $rules['phone'] = 'digits_between:0,50';

        $validator = Validator::make($request->all(), $rules, [
            'first_name.required' => __('validation.required'),
            'first_name.min' => __('validation.min_string'),
            'first_name.max' => __('validation.max_string'),
            'last_name.required' => __('validation.required'),
            'last_name.min' => __('validation.min_string'),
            'last_name.max' => __('validation.max_string'),
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'username.required' => __('validation.required'),
            'username.min' => __('validation.min_string'),
            'username.max' => __('validation.max_string'),
            'password.required' => __('validation.required'),
            'phone.digits_between' => __('validation.digits_between'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Deleting old file if any
        if ($edit && $request->input('image')) {
            $user = objToArr(User::getUser('user_id', $edit));
            $this->deleteOldFile($user['image']);
        }

        $fileUpload = $this->uploadPublicFile(
            $request, 'image', config('constants.upload_dirs.users'), 
            array('image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'dimensions:max_height=250,max_width=250', new MaxFile(adminFileUploadLimit())]),
            array('image.image' => __('validation.image'), 'image.dimensions' => __('validation.dimensions').' ('.__('message.max_allowed').' 250x250px)')
        );

        if (issetVal($fileUpload, 'success') == 'false') {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => issetVal($fileUpload, 'message')))
            )));
        }

        //updating db recrod
        User::storeUser($request->all(), $edit, issetVal($fileUpload, 'message'));
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array(
                'success' => __('message.user').' ' . ($edit ? __('message.updated') : __('message.created'))
        )))));
    }

    /**
     * Function (for ajax) to process user create or edit form request
     *
     * @return redirect
     */
    public function saveUserRoles(Request $request)
    {
        $this->checkIfDemo();

        $validator = Validator::make($request->all(), [
            'roles' => 'required',
        ], [
            'roles.required' => __('validation.required'),

        ]);

        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        User::storeUserRolesBulk($request->all());
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => 'created'))
        ));
    }

    /**
     * Function (for ajax) to process user change status request
     *
     * @param integer $user_id
     * @param string $status
     * @return void
     */
    public function changeStatus($user_id = null, $status = null)
    {
        $this->checkIfDemo();
        User::changeStatus($user_id, $status);
    }

    /**
     * Function (for ajax) to process user bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        User::bulkAction($request->input('data'));
    }

    /**
     * Function (for ajax) to process user delete request
     *
     * @param integer $user_id
     * @return void
     */
    public function delete($user_id)
    {
        $this->checkIfDemo();

        //Deleting existing file
        $user = objToArr(User::getUser('user_id', $user_id));
        $this->deleteOldFile($user['image']);

        User::remove($user_id);
    }

    /**
     * Private function to set remember me token for logged in user
     *
     * @return void
     */
    private function setRememberMe($email, $check)
    {
        if ($check) {
            $name = 'remember_me_token_admin' . appId();
            $tokenValue = $email.'-'.strtotime(date('Y-m-d G:i:s'));
            $time = '1209600'; //Two weeks
            \Cookie::queue(\Cookie::make($name, $tokenValue, $time));
            User::storeRememberMeToken($email, $tokenValue);
        }
    }

    /**
     * Function (for ajax) to display form to send email to user
     *
     * @return void
     */
    public function messageView()
    {
        echo view('admin.users.message')->render();
    }

    /**
     * Function (for ajax) to send email to user
     *
     * @return void
     */
    public function message(Request $request)
    {
        $this->checkIfDemo();
        ini_set('max_execution_time', 5000);
        $data = $request->input();
        $users = explode(',', $data['ids']);

        $rules['msg'] = ['required', new MinString(5), new MaxString(10000)];
        $rules['subject'] = ['required', new MinString(1), new MaxString(100)];
        $validator = Validator::make($request->all(), $rules, [
            'msg.required' => __('validation.required'),
            'msg.min' => __('validation.min_string'),
            'msg.max' => __('validation.max_string'),
            'subject.required' => __('validation.required'),
            'subject.min' => __('validation.min_string'),
            'subject.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        foreach ($users as $user_id) {
            $user = objToArr(User::getUser('users.user_id', $user_id));
            $this->sendEmail(removeUselessLineBreaks($data['msg']), $user['email'], $data['subject']);
        }

        die(json_encode(array('success' => 'true', 'messages' => '')));
    }

    /**
     * Post Function to download users data in excel
     *
     * @return void
     */
    public function usersExcel(Request $request)
    {
        $data = User::getUsersForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');                    
        $excel->writer->setData($data);
        $excel->writer->saveFile('users'); 
        exit;
    }  
}