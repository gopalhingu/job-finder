<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;
use File;
use Response;

use App\Helpers\DbTables;
use App\Helpers\DbImport;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

use App\Models\Admin\User;
use App\Models\Admin\Employer;
use App\Models\Admin\Membership;
use App\Models\Admin\Language;
use App\Models\Employer\Job;
use App\Models\Employer\Employer As Team;
use App\Models\Employer\JobFilter;
use App\Models\Employer\Quiz;
use App\Models\Employer\Interview;
use App\Models\Employer\Traite;

class EssentialsController extends Controller
{
    /**
     * Function to install application
     *
     * @return void
     */
    public function install(Request $request, $step = 'requirements')
    {   
        $envIsEmpty = false;
        if (env('DB_HOST') == '' || env('DB_DATABASE') == '' || env('DB_USERNAME') == '') {
            $envIsEmpty = true;
        }

        if ($step == 'requirements' && $envIsEmpty) {
            return view('essentials.requirements');
        } elseif ($step == 'database' && $envIsEmpty) {
            return view('essentials.database');
        } elseif ($step == 'credentials') {
            $existing = User::where('user_type', 'admin')->first();
            if ($existing) {
                return redirect(route('admin-login'));
            }
            return view('essentials.credentials');
        } else {
            return redirect(route('admin-login'));
        }
    }

    /**
     * Function to create env
     *
     * @return void
     */
    public function setupEnv(Request $request)
    {
        if (env('DB_HOST') != '' || env('DB_DATABASE') != '' || env('DB_USERNAME') != '') {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => 'Already setup'))
            )));
        }

        //Doing data validation
        $validator = Validator::make($request->all(), [
            'db_host' => ['required', new MinString(2), new MaxString(150)],
            'db_name' => ['required', new MinString(2), new MaxString(150)],
            'db_user' => ['required', new MinString(2), new MaxString(150)],
            'db_type' => ['required', new MinString(2), new MaxString(150)],
        ], [
            'db_host.required' => __('validation.required'),
            'db_host.min' => __('validation.min_string'),
            'db_host.max' => __('validation.max_string'),
            'db_name.required' => __('validation.required'),
            'db_name.min' => __('validation.min_string'),
            'db_name.max' => __('validation.max_string'),
            'db_user.required' => __('validation.required'),
            'db_user.min' => __('validation.min_string'),
            'db_user.max' => __('validation.max_string'),
            'db_type.required' => __('validation.required'),
            'db_type.min' => __('validation.min_string'),
            'db_type.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Collecting form data
        $db_host = $request->input('db_host');
        $db_name = $request->input('db_name');
        $db_user = $request->input('db_user');
        $db_password = $request->input('db_password');
        $db_type = $request->input('db_type');
        $db_prefix = $request->input('db_prefix');

        //checking database connection with the provided credentials
        $message = '';
        try {
            $conn = new \PDO($db_type.":host=".$db_host.";dbname=".$db_name, $db_user, $db_password);
        } catch(\Exception $e) {
            $message = 'An error occured. <br /><br />(<i>'.$e->getMessage().'</i>).<br /><br /> Check database credentials and/or try selecting different db driver.';
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => $message))
            )));
        }

        //Creating env file
        $result = $this->createEnvFile($db_host, $db_name, $db_user, $db_password, $db_prefix, $db_type, base_path().'/.env');

        if ($result == 'success') {
            $content = ":root {\n";
            $content .= "--body-bg:#FBFBFB;\n";
            $content .= "--main-menu-bg:#FBFBFB;\n";
            $content .= "--main-menu-btn-bg:#fe9961;\n";
            $content .= "--main-menu-font-color:#484848;\n";
            $content .= "--main-menu-font-highlight-color:#286EFB;\n";
            $content .= "--main-menu-sticky-bg:#72c2f8;\n";
            $content .= "--main-menu-sticky-font-color:#FFF;\n";
            $content .= "--main-banner-bg:#FBFBFB;\n";
            $content .= "--mobile-menu-bg:#85d4f3;\n";
            $content .= "--mobile-menu-sidebar-bg:#85d4f3;\n";
            $content .= "--mobile-menu-font-color:#484848;\n";
            $content .= "--main-banner-height:500px;\n";
            $content .= "--icons-color:#fe9961;\n";
            $content .= "--breadcrumb-background:#edf8ff;\n";
            $content .= "--breadcrumb-font-color:#626262;\n";
            $content .= "--site-btn-bg:#fe9961;\n";
            $content .= "--site-btn-font-color:#FFF;\n";
            $content .= "--main-banner:url(".route('uploads-view')."/identities/site-banner.jpg);\n";
            $content .= "--breadcrumb-image:url(".route('uploads-view')."/identities/site-breadcrumb-image.png);\n";
            $content .= "--testimonials-banner:url(".route('uploads-view')."/identities/site-breadcrumb-image.jpg);\n";
            $content .= "}";
            writeToFile(public_path('/f-assets'.viewPrfx(true).'/css/variables.css'), $content);

            $content = ":root {\n";
            $content .= "--body-bg:#FBFBFB;\n";
            $content .= "--main-menu-bg:#FBFBFB;\n";
            $content .= "--main-menu-font-color:#484848;\n";
            $content .= "--main-menu-font-highlight-color:#286EFB;\n";
            $content .= "--main-banner-bg:#FBFBFB;\n";
            $content .= "--main-banner-height:500px;\n";
            $content .= "--breadcrumb-image:url(".route('uploads-view')."/identities/site-breadcrumb-image.png);\n";
            $content .= "--main-banner:url(".route('uploads-view')."/identities/site-banner.png);\n";
            $content .= "}";
            writeToFile(public_path('/c-assets'.viewPrfx(true).'/css/variables.css'), $content);

            die(json_encode(array('success' => 'true', 'messages' => '')));
        } else {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => $result))
            )));
        }
    }

    /**
     * Function to create database tables
     *
     * @return void
     */
    public function setupDatabase()
    {
        if (env('DB_HOST') == '' || env('DB_DATABASE') == '' || env('DB_USERNAME') == '' || env('APP_URL') == '') {
            return redirect(route('home'));
        }

        DbTables::run();
        $this->generateStorageLink();
        return redirect(route('install-app', ['step' => 'credentials']));
    }

    /**
     * Function to create env and database
     *
     * @return void
     */
    public function setupAdminUser(Request $request)
    {
        //Doing data validation
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', new MinString(2), new MaxString(150)],
            'last_name' => ['required', new MinString(2), new MaxString(150)],
            'email' => ['required', new MinString(2), new MaxString(150)],
            'password' => ['required', new MinString(2), new MaxString(150)],
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
            'email.min' => __('validation.min_string'),
            'email.max' => __('validation.max_string'),
            'password.required' => __('validation.required'),
            'password.min' => __('validation.min_string'),
            'password.max' => __('validation.max_string'),
            'retype_password.required' => __('validation.required'),                        
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $admin['first_name'] = $request->input('first_name');
        $admin['last_name'] = $request->input('last_name');
        $admin['username'] = $request->input('username');
        $admin['email'] = $request->input('email');
        $admin['password'] = \Hash::make($request->input('password'));
        $admin['user_type'] = 'admin';
        $admin['created_at'] = date('Y-m-d G:i:s');
        $admin['status'] = 1;
        User::insert($admin);

        die(json_encode(array('success' => 'true', 'messages' => '')));
    }

    /**
     * Function to create database tables
     *
     * @return void
     */
    public function schema()
    {
        DbTables::run();
    }

    /**
     * Function to import data
     *
     * @return void
     */
    public function data($employer_id = null)
    {
        if ($employer_id) {
            Employer::importEmployerDummyData($employer_id);
            exit;
        }

        DbImport::run();
    }

    /**
     * Function to set color theme in session to avoid glitch on page reload
     *
     * @return void
     */
    public function setColorTheme($color = null, $color_from_candidate_area = null)
    {
        $color = $color_from_candidate_area ? $color_from_candidate_area : $color;
        setSession('default_color_set_by_user', $color);
    }

    /**
     * Function to set front language
     *
     * @return void
     */
    public function setFrontLanguage(Request $request, $id = null)
    {
        $lang = Language::getLanguage('languages.language_id', decode($id));
        setSession('front_selected_language_flag', $lang->flag);
        setSession('front_selected_language_title', $lang->title);
        setSession('front_selected_language_slug', $lang->slug);
        setSession('front_selected_language_direction', $lang->direction);
    }

    /**
     * Function to set candidate language
     *
     * @return void
     */
    public function setCandidateLanguage(Request $request, $slug = null, $id = null)
    {
        $lang = Language::getLanguage('languages.language_id', decode($id));
        setSession('front_selected_language_flag', $lang->flag);
        setSession('front_selected_language_title', $lang->title);
        setSession('front_selected_language_slug', $lang->slug);
        setSession('front_selected_language_direction', $lang->direction);
    }

    /**
     * Function to set employer language
     *
     * @return void
     */
    public function setEmployerLanguage(Request $request, $id = null)
    {
        $lang = Language::getLanguage('languages.language_id', decode($id));
        setSession('employer_selected_language_flag', $lang->flag);
        setSession('employer_selected_language_title', $lang->title);
        setSession('employer_selected_language_slug', $lang->slug);
        setSession('employer_selected_language_direction', $lang->direction);
    }

    /**
     * Function to refresh memberships
     *
     * @return void
     */
    public function refreshMemberships()
    {
        if (!allowedTo('refresh_memberships')) {
            die(__('message.not_allowed'));
        }
        
        $changes = '';
        $memberships = Membership::getAll();

        foreach ($memberships as $membership) {
            $employer_id = $membership['employer_id'];
            $details = objToArr(json_decode($membership['details']));
            $condition = array('employer_id' => $employer_id, 'status' => 1);
            $update = array('status' => 0);
            $changes .= '---------------<br />Employer Id : '.$employer_id.'<br />';
            $total = 0;

            //Refreshing active jobs
            $jobs_count = Job::where($condition)->count();
            if ($jobs_count > issetVal($details, 'active_jobs')) {
                $diff = (int)$jobs_count - (int)issetVal($details, 'active_jobs');
                $changes .= $diff.' Job(s) Deactivated<br />';
                $total = $total + $diff;
                Job::where($condition)->orderBy('created_at', 'ASC')->take($diff)->update($update);
            }

            //Refreshing Team
            $team_condition = array('type' => 'team', 'parent_id' => $employer_id, 'status' => 1);
            $team_count = Team::where($team_condition)->count();
            if ($team_count > issetVal($details, 'active_users')) {
                $diff = (int)$team_count - (int)issetVal($details, 'active_users');
                $changes .= $diff.' Team(s) Deactivated<br />';
                $total = $total + $diff;
                Team::where($team_condition)->orderBy('created_at', 'ASC')->take($diff)->update($update);
            }

            //Refreshing filters
            $filters_count = JobFilter::where($condition)->count();
            if ($filters_count > issetVal($details, 'active_custom_filters')) {
                $diff = (int)$filters_count - (int)issetVal($details, 'active_custom_filters');
                $changes .= $diff.' Custom Filter(s) Deactivated<br />';
                $total = $total + $diff;
                JobFilter::where($condition)->orderBy('created_at', 'ASC')->take($diff)->update($update);
            }

            //Refreshing quizes
            $quizes_count = Quiz::where($condition)->count();
            if ($quizes_count > issetVal($details, 'active_quizes')) {
                $diff = (int)$filters_count - (int)issetVal($details, 'active_quizes');
                $changes .= $diff.' Quize(s) Deactivated<br />';
                $total = $total + $diff;
                Quiz::where($condition)->orderBy('created_at', 'ASC')->take($diff)->update($update);
            }

            //Refreshing interviews
            $interviews_count = Interview::where($condition)->count();
            if ($interviews_count > issetVal($details, 'active_interviews')) {
                $diff = (int)$interviews_count - (int)issetVal($details, 'active_interviews');
                $changes .= $diff.' Interview(s) Deactivated<br />';
                $total = $total + $diff;
                Interview::where($condition)->orderBy('created_at', 'ASC')->take($diff)->update($update);
            }

            $traites_count = Traite::where($condition)->count();
            if ($traites_count > issetVal($details, 'active_traites')) {
                $diff = (int)$traites_count - (int)issetVal($details, 'active_traites');
                $changes .= $diff.' Traite(s) Deactivated<br />';
                $total = $total + $diff;
                Traite::where($condition)->orderBy('created_at', 'ASC')->take($diff)->update($update);
            }

            if ($total == 0) {
                $changes .= 'Nothing deactivated<br />';
            }
        }

        die($changes);
    }

    /**
     * Upload Function (for ajax) to upload images from the ckeditor window
     *
     * @return html
     */
    public function uploadCkEditorImage(Request $request)
    {
        if (isset($_FILES['upload']['name'])) {
            $fileUpload = $this->uploadPublicFile(
                $request, 'upload', config('constants.upload_dirs.ckeditor'), 
                array('upload' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(generalFileUploadLimit())]),
                array('upload.image' => __('validation.image'))
            );
            $funcNum = $request->input('CKEditorFuncNum');
            $url = route('uploads-view', $fileUpload['message']);
            die(json_encode(array(
                'uploaded' => 'true',
                'url' => $url,
            )));
        }
    }

    /**
     * Fallback Function to display images if symlinks are not working
     *
     * @return redirect
     */
    public function uploadsView(Request $request, $seg1 = '', $seg2 = '', $seg3 = '', $seg4 = '')
    {
        $storagePath  = Storage::disk(config('constants.upload_dirs.main'))->getDriver()->getAdapter()->getPathPrefix();
        $seg2 = $seg2 ? '/'.$seg2 : '';
        $seg3 = $seg3 ? '/'.$seg3 : '';
        $seg4 = $seg4 ? '/'.$seg4 : '';
        $path = $storagePath . $seg1.$seg2.$seg3.$seg4;
        if (!File::exists($path)) {
            abort(404);
        }
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }    

    /**
     * Private function to create sym links for uploads directory
     *
     * @return void
     */
    public function generateStorageLink()
    {
        if (function_exists('symlink')) {
            $uploads = public_path().'/uploads';
            if (is_dir($uploads)) {
                deleteDirWithContents($uploads);
            }
            
            \Artisan::call('storage:link');
        }
    }

    /**
     * Private function to create env file
     *
     * @return string
     */
    private function createEnvFile($db_host, $db_name, $db_user, $db_password, $db_prefix, $db_type, $file)
    {
        try {
            $env = fopen($file, "w");
            if (!$env) {
                return 'Unable to find .env file';
            } 
            $content = '';
            $content .= 'APP_NAME=Laravel'.PHP_EOL;
            $content .= 'APP_ENV=local'.PHP_EOL;
            $content .= 'APP_DEBUG=true'.PHP_EOL;
            $content .= 'APP_KEY=base64:X3oWaVk0WqL9slE/q/1ih3+4p6TFdXeimGVBLPVHObs='.PHP_EOL;
            $content .= 'APP_URL='.base_url(true).'/'.PHP_EOL.PHP_EOL;
            $content .= 'DB_CONNECTION='.$db_type.PHP_EOL;
            $content .= 'DB_HOST='.$db_host.PHP_EOL;
            $content .= 'DB_PORT=3306'.PHP_EOL;
            $content .= 'DB_DATABASE='.$db_name.PHP_EOL;
            $content .= 'DB_USERNAME='.$db_user.PHP_EOL;
            $content .= 'DB_PASSWORD='.$db_password.PHP_EOL;
            $content .= 'DB_PREFIX='.$db_prefix.PHP_EOL.PHP_EOL;
            $content .= 'CFSAAS_FRONT_PRFX=beta'.PHP_EOL;
            $content .= 'CFSAAS_DEMO=false'.PHP_EOL;
            $content .= 'CFSAAS_DEMO_STRICT=false'.PHP_EOL;
            $content .= 'CFSAAS_ROUTE_SLUG=slug'.PHP_EOL;
            $content .= 'CFSAAS_SCRIPT_TYPE=jobportal'.PHP_EOL;
            $content .= 'PAYPAL_SANDBOX_URL=https://www.sandbox.paypal.com/cgi-bin/webscr'.PHP_EOL;
            $content .= 'PAYPAL_URL=https://www.paypal.com/cgi-bin/webscr'.PHP_EOL;
            $content .= 'ADD_SLUG_IN_TRANSLATIONS=true'.PHP_EOL;
            fwrite($env, $content);
            fclose($env);
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }    
}
    