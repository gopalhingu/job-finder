<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Front\Candidate;
use App\Models\Front\Resume;
use App\Models\Admin\Notification;
use App\Models\Admin\CandidatePackage;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

class CandidatesController extends Controller
{   
    /**
     * View function to display all candidates page
     *
     * @return html/string
     */
    public function candidates(Request $r)
    {
        //This feature/page was introduced in version 2.2
        if (viewPrfx('only') == 'alpha') {
            die('Not Available in Alpha View');
        }
        //As the setting indicates, if only employers are allowed to view candidates
        if (setting('enable_only_employer_to_view_candidates') == 'yes' && !employerSession()) {
            die('Only Available for employers');
        }
        $data['page_title'] = __('message.candidates');
        $candidates = Candidate::getForListPage($r->all());
        $data['pagination'] = $candidates['pagination'];
        $data['page'] = $r->get('page');
        $data['search'] = $r->get('search');
        $data['sort'] = $r->get('sort');
        $data['view'] = $r->get('view') ? $r->get('view') : 'list';
        $data['candidates_experiences_value'] = $r->get('candidates_experiences_value');
        $data['candidates_experiences_range'] = $r->get('candidates_experiences_range') ? $r->get('candidates_experiences_range') : 0;
        $data['candidates_qualifications_value'] = $r->get('candidates_qualifications_value');
        $data['candidates_qualifications_range'] = $r->get('candidates_qualifications_range') ? $r->get('candidates_qualifications_range') : 0;
        $data['candidates_achievements_value'] = $r->get('candidates_achievements_value');
        $data['candidates_achievements_range'] = $r->get('candidates_achievements_range') ? $r->get('candidates_achievements_range') : 0;
        $data['candidates_skills_value'] = $r->get('candidates_skills_value');
        $data['candidates_skills_range'] = $r->get('candidates_skills_range') ? $r->get('candidates_skills_range') : 0;
        $data['candidates_languages_value'] = $r->get('candidates_languages_value');
        $data['candidates_languages_range'] = $r->get('candidates_languages_range') ? $r->get('candidates_languages_range') : 0;
        $data['candidates'] = $candidates['results'];
        $data['pagination_overview'] = paginationOverview($candidates['total'], $candidates['perPage'], $candidates['currentPage']);
        $data['favorites'] = Candidate::getFavorites();

        return view('front'.viewPrfx().'candidates.'.$data['view'], $data);
    }

    /**
     * View function to display candidates detail page
     *
     * @return html/string
     */
    public function candidatesDetail($slug)
    {
        //As the setting indicates, if only employers are allowed to view candidates
        if (setting('enable_only_employer_to_view_candidates') == 'yes' && !employerSession()) {
            die('Only Available for employers');
        }

        $candidate = Candidate::getSingle('candidates.slug', $slug);
        if (!$candidate) {
            $candidate = Candidate::getSingle('candidates.candidate_id', decode($slug));
        }
        if (!$candidate) {
            die(__('message.no_record_found'));
        }
        if (setting('enable_candidate_packages') == 'yes' && !candidateShow($candidate, 'show_profile')) {
            die(__('message.not_allowed'));   
        }

        $data['page_title'] = __('message.candidates');
        $data['candidate'] = $candidate;
        $data['resume'] = Resume::getCompleteResume($data['candidate']['resume_id']);
        $data['similar'] = issetVal($candidate, 'skill_titles') ? Candidate::getSimilar(issetVal($candidate, 'skill_titles')) : array();
        $data['favorites'] = Candidate::getFavorites();
        return view('front'.viewPrfx().'candidates.detail', $data);
    }

    /**
     * View Function to display settings update page for candidate
     *
     * @return html/string
     */
    public function updateSettingsView(Request $request, $id = null)
    {
        $data['page_title'] = __('message.update_settings');
        $data['menu'] = 'settings';
        $data['candidate'] = Candidate::getFirst('candidates.candidate_id', candidateSession());
        return view('front'.viewPrfx().'candidates.settings', $data);
    }    

    /**
     * Function (for ajax) to process settings update form request
     *
     * @return redirect
     */
    public function updateSettings(Request $request)
    {
        $this->checkIfDemo();

        Candidate::updateProfile($request->all());
        
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.settings_updated')))
        ));
    }

    /**
     * View Function to display profile update page for candidate
     *
     * @return html/string
     */
    public function updateProfileView(Request $request, $id = null)
    {
        $data['page_title'] = __('message.update_profile');
        $data['menu'] = 'profile';
        $data['candidate'] = Candidate::getFirst('candidates.candidate_id', candidateSession());
        return view('front'.viewPrfx().'candidates.profile', $data);
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
        $data['page_title'] = __('message.update_password');
        $data['menu'] = 'password';
        return view('front'.viewPrfx().'candidates.password', $data);
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
     * View function to submit register free form
     *
     * @return html/string
     */
    public function register(Request $request)
    {
        $this->checkIfDemo();

        //Doing form validations
        $rules['first_name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['last_name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['email'] = 'required|email|unique:candidates';
        $rules['password'] = 'required|required_with:retype_password|same:retype_password';
        $rules['retype_password'] = 'required';
        $messages = [
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
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Checking if packages are enabled and if there is a free package
        if (setting('enable_candidate_packages') == 'yes') {        
            //Checking if there is any free package or not
            $freePackage = objToArr(CandidatePackage::where('is_free', 1)->first()->toArray());
            if (!$freePackage) {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => __('message.no_free_package')))
                )));
            }
        }

        //Sending verification email for candidate
        if (setting('enable_candidate_email_verification') == 'yes') {
            $candidate = Candidate::createCandidate($request->all(), true);
            $tagsWithValues = array(
                '((site_link))' => url('/'),
                '((site_logo))' => setting('site_logo'),
                '((first_name))' => $candidate['first_name'],
                '((last_name))' => $candidate['last_name'],
                '((email))' => $candidate['email'],
                '((link))' => url('/').'/activate-account/'.$candidate['token'],
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
     * Function to activate account 
     * e.g. resulting function for click on email
     * 
     * @return redirect
     */
    public function activateAccount($token = null)
    {
        $result = Candidate::activateAccount($token);
        if ($result) {
            $data['page_title'] = __('message.activate_account');
            return view('front'.viewPrfx().'activate-account', $data);
        } else {
            return redirect(route('home'));
        }
    }

    /**
     * Function to mark jobs as favorite
     *
     * @return html/string
     */
    public function markFavorite($id = null)
    {
        if (employerSession()) {
            if (Candidate::markFavorite($id)) {
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
        Candidate::unmarkFavorite($id);
        echo json_encode(array('success' => 'true', 'messages' => ''));
    }
    
}

