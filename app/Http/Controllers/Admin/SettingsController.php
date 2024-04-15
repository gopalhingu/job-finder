<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\Setting as AdminSetting;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

use SimpleExcel\SimpleExcel;

class SettingsController extends Controller
{
    /**
     * View Function to display general setings form view
     *
     * @return html/string
     */
    public function general()
    {
        if (!allowedTo('general_settings')) {
            die(__('message.not_allowed'));
        }
        $data['page'] = __('message.general_settings');
        $data['menu'] = 'setting-general';
        return view('admin.settings.general-'.viewPrfx('only'), $data);
    }

    /**
     * Function (for ajax) to process settings update form request
     *
     * @return redirect
     */
    public function updateGeneral(Request $request)
    {
        $this->checkIfDemo('', true);

        $rules['site_name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['admin_email'] = 'required|email';
        $rules['purchase_code'] = [new MinString(30), new MaxString(250)];
        $rules['site_keywords'] = [new MinString(10), new MaxString(500)];
        $rules['site_description'] = [new MinString(10), new MaxString(500)];

        $validator = Validator::make($request->all(), $rules, [
            'site_name.required' => __('validation.required'),
            'site_name.min' => __('validation.min_string'),
            'site_name.max' => __('validation.max_string'),
            'admin_email.required' => __('validation.required'),
            'admin_email.email' => __('validation.email'),
            'purchase_code.min' => __('validation.min_string'),
            'purchase_code.max' => __('validation.max_string'),
            'site_keywords.min' => __('validation.min_string'),
            'site_keywords.max' => __('validation.max_string'),
            'site_description.min' => __('validation.min_string'),
            'site_description.max' => __('validation.max_string'),
        ]);

        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $data = $request->input();
        $data['front_header_scripts'] = templateInput('front_header_scripts');
        $data['front_footer_scripts'] = templateInput('front_footer_scripts');
        $data['employer_header_scripts'] = templateInput('employer_header_scripts');
        $data['employer_footer_scripts'] = templateInput('employer_footer_scripts');
        $data['candidate_header_scripts'] = templateInput('candidate_header_scripts');
        $data['candidate_footer_scripts'] = templateInput('candidate_footer_scripts');
        $data['contact_text'] = templateInput('contact_text');

        unset($data['_token']);

        AdminSetting::updateData($data);

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.settings').' '.__('message.updated')))
        )));
    }

    /**
     * View Function to display 'display settings' form view
     *
     * @return html/string
     */
    public function display()
    {
        if (!allowedTo('display_settings')) {
            die(__('message.not_allowed'));
        }
        $data['page'] = __('message.display_settings');
        $data['menu'] = 'setting-display';
        return view('admin.settings.display-'.viewPrfx('only'), $data);
    }

    /**
     * Function (for ajax) to process settings update form request
     *
     * @return redirect
     */
    public function updateDisplay(Request $request)
    {
        $this->checkIfDemo('', true);

        $rules['jobs_per_page'] = ['required'];
        $rules['news_per_page'] = ['required'];
        $rules['companies_per_page'] = ['required'];
        $rules['candidates_per_page'] = ['required'];

        $validator = Validator::make($request->all(), $rules, [
            'jobs_per_page.required' => __('validation.required'),
            'news_per_page.required' => __('validation.required'),
            'companies_per_page.required' => __('validation.required'),
            'candidates_per_page.required' => __('validation.required'),
        ]);

        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $data = $request->input();
        $data['footer_column_1'] = sanitizeHtmlTemplates(templateInput('footer_column_1'));
        $data['footer_column_2'] = sanitizeHtmlTemplates(templateInput('footer_column_2'));
        $data['footer_column_3'] = sanitizeHtmlTemplates(templateInput('footer_column_3'));
        $data['footer_column_4'] = sanitizeHtmlTemplates(templateInput('footer_column_4'));

        unset($data['_token'], $data['site_logo'], $data['site_banner']);

        $this->uploadLogo($request, $data);
        $this->uploadBanner($request, $data);
        $this->uploadTestimonialsImage($request, $data);
        $this->uploadFavicon($request, $data);

        AdminSetting::updateData($data);

        //Writing to css files
        writeToFile(public_path('/f-assets'.viewPrfx(true).'/css/custom-css.css'), $data['front_site_css']);
        writeToFile(public_path('/c-assets'.viewPrfx(true).'/css/custom-css.css'), $data['candidate_site_css']);

        if (isset($data['site_banner'])) {
            $variables['main-banner'] = "url(".route('uploads-view', $data['site_banner']).")";
        }
        if (isset($data['testimonials_banner'])) {
            $variables['breadcrumb-image'] = "url(".route('uploads-view', $data['testimonials_banner']).")";
        }

        $variables['body-bg'] = $data['body_bg'];
        $variables['main-menu-bg'] = $data['display_main_menu_bg_as_transparent'] == 'yes' ? 'transparent' : $data['main_menu_bg'];
        $variables['main-menu-font-color'] = $data['main_menu_font_color'];
        $variables['main-menu-font-highlight-color'] = $data['main_menu_font_highlight_color'];
        $variables['main-banner-bg'] = $data['main_banner_bg'];
        $variables['main-banner-height'] = $data['main_banner_height'];

        if (viewPrfx('only') == 'alpha') {
            $variables['main-menu-btn-bg'] = $data['main_menu_btn_bg'];
            $variables['main-menu-sticky-bg'] = $data['main_menu_sticky_bg'];
            $variables['main-menu-sticky-font-color'] = $data['main_menu_sticky_font_color'];
            $variables['mobile-menu-bg'] = $data['mobile_menu_bg'];
            $variables['mobile-menu-sidebar-bg'] = $data['mobile_menu_sidebar_bg'];
            $variables['mobile-menu-font-color'] = $data['mobile_menu_font_color'];
            $variables['icons-color'] = $data['icons_color'];
            $variables['breadcrumb-background'] = $data['breadcrumb_background'];
            $variables['breadcrumb-font-color'] = $data['breadcrumb_font_color'];
            $variables['site-btn-bg'] = $data['site_btn_bg'];
            $variables['site-btn-font-color'] = $data['site_btn_font_color'];            
        }

        AdminSetting::updateCssVariables($variables);

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.settings').' '.__('message.updated')))
        )));
    }

    /**
     * View Function to general images form view
     *
     * @return html/string
     */
    public function generalImages()
    {
        if (!allowedTo('general_images_settings')) {
            die(__('message.not_allowed'));
        }
        $data['page'] = __('message.general_images');
        $data['menu'] = 'setting-general-images';
        return view('admin.settings.general-images', $data);
    }

    /**
     * Function (for ajax) to process settings update form request
     *
     * @return redirect
     */
    public function updateGeneralImages(Request $request)
    {
        $this->checkIfDemo('', true);

        $data = $request->input();
        unset($data['_token']);

        $this->uploadGeneralImage($request, $data, 'filters', 'general_feature_filters', 'feature');
        $this->uploadGeneralImage($request, $data, 'interviews', 'general_feature_interviews', 'feature');
        $this->uploadGeneralImage($request, $data, 'jobboard', 'general_feature_jobboard', 'feature');
        $this->uploadGeneralImage($request, $data, 'personalized', 'general_feature_personalized', 'feature');
        $this->uploadGeneralImage($request, $data, 'quizes', 'general_feature_quizes', 'feature');
        $this->uploadGeneralImage($request, $data, 'referjob', 'general_feature_referjob', 'feature');
        $this->uploadGeneralImage($request, $data, 'reports', 'general_feature_reports', 'feature');
        $this->uploadGeneralImage($request, $data, 'resume', 'general_feature_resume', 'feature');
        $this->uploadGeneralImage($request, $data, 'rolepermissions', 'general_feature_rolepermissions', 'feature');
        $this->uploadGeneralImage($request, $data, 'selfassesment', 'general_feature_selfassesment', 'feature');
        $this->uploadGeneralImage($request, $data, 'modal', 'general_modal', 'general');
        $this->uploadGeneralImage($request, $data, 'candidates', 'general_candidates', 'general');
        $this->uploadGeneralImage($request, $data, 'employers', 'general_employers', 'general');
        $this->uploadGeneralImage($request, $data, 'benefits', 'general_benefits', 'general');

        AdminSetting::updateData($data);

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.settings').' '.__('message.updated')))
        )));
    }

    /**
     * View Function to display form view
     *
     * @return html/string
     */
    public function email()
    {
        if (!allowedTo('email_settings')) {
            die(__('message.not_allowed'));
        }
        $data['page'] = __('message.email_settings');
        $data['menu'] = 'setting-email';
        return view('admin.settings.email', $data);
    }

    /**
     * Function (for ajax) to process settings update form request
     *
     * @return redirect
     */
    public function updateEmail(Request $request)
    {
        $this->checkIfDemo('', true);

        $rules['from_email'] = 'required|email';
        $rules['from_name'] = 'required';
        $rules['smtp_host'] = 'required';
        $rules['smtp_protocol'] = 'required';
        $rules['smtp_port'] = 'required';
        $rules['smtp_username'] = 'required';
        $rules['smtp_password'] = 'required';

        $validator = Validator::make($request->all(), $rules, [
            'from_email.required' => __('validation.required'),
            'from_email.email' => __('validation.email'),
            'smtp_host.required' => __('validation.required'),
            'smtp_protocol.required' => __('validation.required'),
            'smtp_port.required' => __('validation.required'),
            'smtp_username.required' => __('validation.required'),
            'smtp_password.required' => __('validation.required'),
        ]);

        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $data = $request->input(); 
        unset($data['_token']);

        AdminSetting::updateData($data);

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.settings').' '.__('message.updated')))
        )));
    }

    /**
     * View Function to display form view
     *
     * @return html/string
     */
    public function apis()
    {
        if (!allowedTo('apis_settings')) {
            die(__('message.not_allowed'));
        }
        $data['page'] = __('message.api_settings');
        $data['menu'] = 'setting-apis';
        return view('admin.settings.apis', $data);
    }

    /**
     * Function (for ajax) to process settings update form request
     *
     * @return redirect
     */
    public function updateApis(Request $request)
    {
        $this->checkIfDemo('', true);

        $data = $request->input(); 
        unset($data['_token']);

        AdminSetting::updateData($data);

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.settings').' '.__('message.updated')))
        )));
    }

    /**
     * View Function to display form view
     *
     * @return html/string
     */
    public function home()
    {
        if (!allowedTo('home_page_settings')) {
            die(__('message.not_allowed'));
        }
        $data['page'] = __('message.home_settings');
        $data['menu'] = 'setting-home';
        return view('admin.settings.home-'.viewPrfx('only'), $data);
    }

    /**
     * Function (for ajax) to process settings update form request
     *
     * @return redirect
     */
    public function updateHomeSettings(Request $request)
    {
        $this->checkIfDemo('', true);

        $data = $request->all();
        unset($data['_token']);
        $data['home_banner_text'] = sanitizeHtmlTemplates(templateInput('home_banner_text'));

        if (issetVal($data, 'home_display_order')) {
            $data['home_display_order'] = json_encode($data['home_display_order']);
        }

        AdminSetting::updateData($data);

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.settings').' '.__('message.updated')))
        )));
    }

    /**
     * View Function to display email templates settings page view
     *
     * @return html/string
     */
    public function emailTemplates()
    {
        if (!allowedTo('email_template_settings')) {
            die(__('message.not_allowed'));
        }

        $data['page'] = __('message.email_settings');
        $data['menu'] = 'setting-email-templates';
        return view('admin.settings.email-templates', $data);        
    }

    /**
     * Function (for ajax) to process settings update form request
     *
     * @return redirect
     */
    public function updateEmailTemplates(Request $request)
    {
        $this->checkIfDemo('', true);

        $rules['candidate_signup'] = "required|max:5000";
        $rules['candidate_verify_email'] = "required|max:5000";
        $rules['candidate_reset_password'] = "required|max:5000";
        $rules['employer_signup'] = "required|max:5000";
        $rules['employer_verify_email'] = "required|max:5000";
        $rules['employer_reset_password'] = "required|max:5000";
        $rules['employer_refer_job'] = "required|max:5000";

        $validator = Validator::make($request->all(), $rules, [
            'candidate_signup.required' => __('validation.required'),
            'candidate_signup.max' => __('validation.max_string'),
            'candidate_verify_email.required' => __('validation.required'),
            'candidate_verify_email.max' => __('validation.max_string'),
            'candidate_reset_password.required' => __('validation.required'),
            'candidate_reset_password.max' => __('validation.max_string'),
            'employer_signup.required' => __('validation.required'),
            'employer_signup.max' => __('validation.max_string'),
            'employer_verify_email.required' => __('validation.required'),
            'employer_verify_email.max' => __('validation.max_string'),
            'employer_reset_password.required' => __('validation.required'),
            'employer_reset_password.max' => __('validation.max_string'),
            'employer_refer_job.required' => __('validation.required'),
            'employer_refer_job.max' => __('validation.max_string'),
        ]);

        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Preparing data for email templates
        $data = array(
            'candidate_signup' => templateInput('candidate_signup'),
            'candidate_verify_email' => templateInput('candidate_verify_email'),
            'candidate_reset_password' => templateInput('candidate_reset_password'),
            'employer_signup' => templateInput('employer_signup'),
            'employer_verify_email' => templateInput('employer_verify_email'),
            'employer_reset_password' => templateInput('employer_reset_password'),
            'employer_refer_job' => templateInput('employer_refer_job'),
        );

        //Checking to validate reserved words
        $this->reservedWordsValidationForAdminTemplates($data);

        //Sanitizing all the templates (Adjust broken html and remove malicious things i.e. xss)
        $data = sanitizeHtmlTemplates($data);

        //Updating data
        AdminSetting::updateData($data);

        //Creating files for display in admin via iframe
        foreach ($data as $key => $value) {
            $filename = str_replace('_', '-', $key).'.html';
            $filePath = storage_path('/app/'.config('constants.upload_dirs.main').'/templates/'.$filename);
            $myfile = fopen($filePath, "w") or die("Unable to open file!");

            //Replacing tags with actual values
            $tags = array('((site_name))', '((site_link))', '((site_logo))');
            $values = array(setting('site_name'), url('/'), setting('site_logo'));
            $template = replaceTagsInTemplate(setting($key), $tags, $values);

            fwrite($myfile, $template);
            fclose($myfile);
        }

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.settings').' '.__('message.updated')))
        )));
    }

    /**
     * View Function to display email templates settings page view
     *
     * @return html/string
     */
    public function employerSettings()
    {
        if (!allowedTo('employer_override_settings')) {
            die(__('message.not_allowed'));
        }
        
        $data['page'] = __('message.employer_settings');
        $data['menu'] = 'setting-employers';
        return view('admin.settings.employer', $data);        
    }

    /**
     * Function (for ajax) to process settings update form request
     *
     * @return redirect
     */
    public function updateEmployerSettings(Request $request)
    {
        $this->checkIfDemo('', true);

        $rules['banner_text'] = "max:5000";
        $rules['before_blogs_text'] = "max:5000";
        $rules['after_blogs_text'] = "max:5000";
        $rules['before_how_text'] = "max:5000";
        $rules['after_how_text'] = "max:5000";
        $rules['footer_col_1'] = "max:5000";
        $rules['footer_col_2'] = "max:5000";
        $rules['footer_col_3'] = "max:5000";
        $rules['footer_col_4'] = "max:5000";

        $rules['candidate_job_app'] = "required|max:5000";
        $rules['employer_job_app'] = "required|max:5000";
        $rules['employer_interview_assign'] = "required|max:5000";
        $rules['candidate_interview_assign'] = "required|max:5000";
        $rules['candidate_quiz_assign'] = "required|max:5000";
        $rules['team_creation'] = "required|max:5000";

        $validator = Validator::make($request->all(), $rules, [
            'candidate_job_app.required' => __('validation.required'),
            'candidate_job_app.max' => __('validation.max_string'),
            'employer_job_app.required' => __('validation.required'),
            'employer_job_app.max' => __('validation.max_string'),
            'employer_interview_assign.required' => __('validation.required'),
            'employer_interview_assign.max' => __('validation.max_string'),
            'candidate_interview_assign.required' => __('validation.required'),
            'candidate_interview_assign.max' => __('validation.max_string'),
            'candidate_quiz_assign.required' => __('validation.required'),
            'candidate_quiz_assign.max' => __('validation.max_string'),
            'team_creation.required' => __('validation.required'),
            'team_creation.max' => __('validation.max_string'),
            'banner_text.max' => __('validation.max_string'),
            'before_blogs_text.max' => __('validation.max_string'),
            'after_blogs_text.max' => __('validation.max_string'),
            'before_how_text.max' => __('validation.max_string'),
            'after_how_text.max' => __('validation.max_string'),
            'footer_col_1.max' => __('validation.max_string'),
            'footer_col_2.max' => __('validation.max_string'),
            'footer_col_3.max' => __('validation.max_string'),
            'footer_col_4.max' => __('validation.max_string'),
        ]);

        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Preparing data for email templates
        $dataTemplates['candidate_job_app'] = templateInput('candidate_job_app');
        $dataTemplates['employer_job_app'] = templateInput('employer_job_app');
        $dataTemplates['employer_interview_assign'] = templateInput('employer_interview_assign');
        $dataTemplates['candidate_interview_assign'] = templateInput('candidate_interview_assign');
        $dataTemplates['candidate_quiz_assign'] = templateInput('candidate_quiz_assign');
        $dataTemplates['team_creation'] = templateInput('team_creation');

        //Now collecting other than email templates and including in main data array
        $dataSimple['banner_text'] = templateInput('banner_text');
        $dataSimple['before_blogs_text'] = templateInput('before_blogs_text');
        $dataSimple['after_blogs_text'] = templateInput('after_blogs_text');
        $dataSimple['before_how_text'] = templateInput('before_how_text');
        $dataSimple['after_how_text'] = templateInput('after_how_text');
        $dataSimple['footer_col_1'] = templateInput('footer_col_1');
        $dataSimple['footer_col_2'] = templateInput('footer_col_2');
        $dataSimple['footer_col_3'] = templateInput('footer_col_3');
        $dataSimple['footer_col_4'] = templateInput('footer_col_4');

        //Checking to validate reserved words
        $this->reservedWordsValidationForEmployerTemplates($dataTemplates);

        //Sanitizing all the templates (Adjust broken html and remove malicious things i.e. xss)
        $dataTemplates = sanitizeHtmlTemplates($dataTemplates);

        //Combining the two
        $data = array_merge($dataTemplates, $dataSimple);

        //Updating data
        AdminSetting::updateData($data);

        //Creating files for display in admin via iframe
        foreach ($dataTemplates as $key => $value) {
            $filename = str_replace('_', '-', $key).'.html';
            $filePath = storage_path('/app/'.config('constants.upload_dirs.main').'/employer-email-templates/'.$filename);
            $myfile = fopen($filePath, "w") or die("Unable to open file!");

            //Replacing tags with actual values
            $tags = array('((site_name))', '((site_link))', '((site_logo))');
            $values = array(setting('site_name'), url('/'), setting('site_logo'));
            $template = replaceTagsInTemplate(setting($key), $tags, $values);

            fwrite($myfile, $template);
            fclose($myfile);
        }

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.settings').' '.__('message.updated')))
        )));
    }

    /**
     * View Function to display form view
     *
     * @return html/string
     */
    public function jobPortalVsSaasSettings()
    {
        if (!allowedTo('portal_vs_multitenancy')) {
            die(__('message.not_allowed'));
        }
        $data['page'] = __('message.portal_vs_multitenancy').' '.__('message.settings');
        $data['menu'] = 'setting-jpvssaas';
        if (viewPrfx('only') == 'alpha') {
            return view('admin.settings.portal-vs-multitenancy-alpha', $data);
        } else {
            return view('admin.settings.portal-vs-multitenancy-beta', $data);
        }
    }

    /**
     * Function (for ajax) to process settings update form request
     *
     * @return redirect
     */
    public function updateJobPortalVsSaasSettings(Request $request)
    {
        $this->checkIfDemo('', true);

        $data = $request->input(); 
        unset($data['_token']);

        AdminSetting::updateData($data);

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.settings').' '.__('message.updated')))
        )));
    }

    private function uploadLogo($request, &$data)
    {
        $existing = setting('site_logo');

        //Uploading logo
        $upload1 = $this->uploadPublicFile(
            $request, 
            'site_logo', 
            config('constants.upload_dirs.identities'),
            array('site_logo' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(adminFileUploadLimit())]),
            array('site_logo.image' => __('validation.image')),
            'site-logo-original'
        );

        if ($upload1) {
            //Die if there is any error
            if ($upload1['success'] == 'false') {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => $upload1['message']))
                )));
            }

            //Delete previous file
            $path = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.identities'));
            if ($upload1['success'] == 'true') {
                if ($existing && file_exists($path.$existing) && $existing != $upload1['message']) {
                    unlink($path.$existing);
                }
            }

            $ext = explode('.', $upload1['message'])[1];
            $filepath = $path.'/site-logo-original.'.$ext;
            if (file_exists($filepath)) {
                $this->resizeByWidthOrHeight($path, 'site-logo', 'site-logo-original', $ext, 0, 45);
                foreach (array('png', 'jpg', 'jpeg', 'gif') as $extension) {
                    @unlink($path.'/site-logo-original.'.$extension);
                }
            }

            //adding filename to the passed array
            if ($upload1['message']) {
                $data['site_logo'] = str_replace('-original', '', $upload1['message']);
            }
        }
    }

    private function uploadBanner($request, &$data)
    {
        $existing = setting('site_banner');

        //Uploading logo
        $upload2 = $this->uploadPublicFile(
            $request, 
            'site_banner', 
            config('constants.upload_dirs.identities'),
            array('site_banner' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(adminFileUploadLimit())]),
            array('site_banner.image' => __('validation.image')),
            'site-banner'
        );

        if ($upload2) {
            //Die if there is any error
            if ($upload2['success'] == 'false') {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => $upload2['message']))
                )));
            }

            //Delete previous file
            $path = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.identities'));
            if ($upload2['success'] == 'true') {
                if ($existing && file_exists($path.$existing) && $existing != $upload2['message']) {
                    unlink($path.$existing);
                }
            }

            //adding filename to the passed array
            if ($upload2['message']) {
                $data['site_banner'] = $upload2['message'];
            }
        }
    }

    private function uploadTestimonialsImage($request, &$data)
    {
        $existing = setting('testimonials_banner');

        //Uploading logo
        $upload3 = $this->uploadPublicFile(
            $request, 
            'testimonials_banner', 
            config('constants.upload_dirs.identities'),
            array('testimonials_banner' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(adminFileUploadLimit())]),
            array('testimonials_banner.image' => __('validation.image')),
            'site-breadcrumb-image'
        );

        if ($upload3) {
            //Die if there is any error
            if ($upload3['success'] == 'false') {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => $upload3['message']))
                )));
            }

            //Delete previous file
            $path = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.identities'));
            if ($upload3['success'] == 'true') {
                if ($existing && file_exists($path.$existing) && $existing != $upload3['message']) {
                    unlink($path.$existing);
                }
            }

            //adding filename to the passed array
            if ($upload3['message']) {
                $data['testimonials_banner'] = $upload3['message'];
            }
        }
    }

    private function uploadFavicon($request, &$data)
    {
        $existing = setting('site_favicon');

        //Uploading logo
        $upload4 = $this->uploadPublicFile(
            $request, 
            'site_favicon', 
            config('constants.upload_dirs.identities'),
            array('site_favicon' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(adminFileUploadLimit())]),
            array('site_favicon.image' => __('validation.image')),
            'site-favicon-original'
        );

        if ($upload4) {
            //Die if there is any error
            if ($upload4['success'] == 'false') {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => $upload4['message']))
                )));
            }

            //Delete previous file
            $path = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.identities'));
            if ($upload4['success'] == 'true') {
                if ($existing && file_exists($path.$existing) && $existing != $upload4['message']) {
                    unlink($path.$existing);
                }
            }

            $ext = explode('.', $upload4['message'])[1];
            $filepath = $path.'/site-favicon-original.'.$ext;
            if (file_exists($filepath)) {
                $this->resizeByWidthOrHeight($path, 'site-favicon', 'site-favicon-original', $ext, 0, 45);
                foreach (array('png', 'jpg', 'jpeg', 'gif') as $extension) {
                    @unlink($path.'/site-favicon-original.'.$extension);
                }
            }

            //adding filename to the passed array
            if ($upload4['message']) {
                $data['site_favicon'] = str_replace('-original', '', $upload4['message']);
            }
        }
    }

    private function uploadGeneralImage($request, &$data, $name, $upload_file_name, $type)
    {
        $existing = setting($name);
        $dir = $type == 'feature' ? 'general-features' : 'general';
        $uc_name = $upload_file_name;
        $dashed_name = str_replace('_', '-', $uc_name);
        $dashed_name_original = $dashed_name.'-original';

        //Uploading logo
        $upload_general = $this->uploadPublicFile(
            $request, 
            $uc_name, 
            config('constants.upload_dirs.'.$dir),
            array($uc_name => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(adminFileUploadLimit())]),
            array($uc_name.'.image' => __('validation.image')),
            $name
        );

        if ($upload_general) {
            //Die if there is any error
            if ($upload_general['success'] == 'false') {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => $upload_general['message']))
                )));
            }

            //Delete previous file
            $path = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.'.$dir));
            if ($upload_general['success'] == 'true') {
                if ($existing && file_exists($path.$existing) && $existing != $upload_general['message']) {
                    unlink($path.$existing);
                }
            }

            //adding filename to the passed array
            if ($upload_general['message']) {
                $data[$uc_name] = $upload_general['message'];
            }
        }
    }

    /**
     * Function (for ajax) to validate reserved words in templates
     *
     * @return void
     */
    private function reservedWordsValidationForAdminTemplates($data)
    {
        $error = '';

        $reservedWords = array(
            array('((site_link))','((site_logo))', '((first_name))','((last_name))','((email))'),
            array('((site_link))','((site_logo))', '((first_name))','((last_name))','((email))','((link))'),
            array('((site_link))','((site_logo))', '((first_name))','((last_name))','((link))'),
            array('((site_link))','((site_logo))', '((first_name))','((last_name))','((email))','((package))'),
            array('((site_link))','((site_logo))', '((first_name))','((last_name))','((email))','((link))'),
            array('((site_link))','((site_logo))', '((first_name))','((last_name))','((link))'),
            array('((site_link))','((site_logo))', '((name))', '((first_name))','((last_name))','((link))'),
        );

        $templates = array(
            'candidate_signup',
            'candidate_verify_email',
            'candidate_reset_password',
            'employer_signup',
            'employer_verify_email',
            'employer_reset_password',
            'employer_refer_job',
        );

        for ($i=0; $i < count($reservedWords) ; $i++) { 
            if (checkReservedWords($reservedWords[$i], $data[$templates[$i]])) {
                $error .= __('message.these_words_are_reserved_in_template', array(
                    'tags' => implode($reservedWords[$i], ','), 
                    'template' => __('message.'.$templates[$i])
                )).'<br />';
            }
        }

        if ($error) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => $error))
            )));
        }
    }    

    /**
     * Function (for ajax) to validate reserved words in templates
     *
     * @return void
     */
    private function reservedWordsValidationForEmployerTemplates($data)
    {
        $error = '';

        $reservedWords = array(
            array('((first_name))','((last_name))','((job_title))','((site_name))','((site_link))','((site_logo))'),
            array('((job_title))','((site_name))','((site_link))','((site_logo))'),
            array('((first_name))','((last_name))','((job_title))','((site_link))','((site_logo))', '((date_time))', '((description))'),
            array('((first_name))','((last_name))','((job_title))','((site_name))','((site_link))','((site_logo))','((date_time))','((description))'),
            array('((first_name))','((last_name))','((job_title))','((site_link))','((site_logo))','((quiz))'),
            array('((first_name))','((last_name))','((email))','((password))', '((link))','((site_link))','((site_logo))'),
        );

        $templates = array(
            'candidate_job_app',
            'employer_job_app',
            'employer_interview_assign',
            'candidate_interview_assign',
            'candidate_quiz_assign',
            'team_creation',
        );

        for ($i=0; $i < count($reservedWords) ; $i++) { 
            if (checkReservedWords($reservedWords[$i], $data[$templates[$i]])) {
                $error .= __('message.these_words_are_reserved_in_template', array(
                    'tags' => implode($reservedWords[$i], ','), 
                    'template' => __('message.'.$templates[$i])
                )).'<br />';
            }
        }

        if ($error) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => $error))
            )));
        }
    }    
}