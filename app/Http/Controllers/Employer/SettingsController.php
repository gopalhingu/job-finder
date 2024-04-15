<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Candidate;
use App\Models\Employer\Update;
use App\Models\Employer\Setting;
use App\Models\Employer\Testimonial;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

class SettingsController extends Controller
{
    /**
     * View Function to display general settings page view
     *
     * @return html/string
     */
    public function general()
    {
        if (!empAllowedTo('general')) {
            die(__('message.not_allowed'));
        }
        $data['page'] = __('message.settings').' '.__('message.general');
        $data['menu'] = 'general';
        $data['testimonial'] = Testimonial::getEmployerTestimonial();
        return view('employer.settings.general', $data);
    }

    /**
     * Function (for ajax) to process settings update form request
     *
     * @return redirect
     */
    public function updateGeneral(Request $request)
    {
        $this->checkIfDemo();

        $rules["admin_email"] = ['required', 'email', new MaxString(50)];
        $rules["from_email"] = ['required', 'email', new MaxString(50)];
        $rules["jobs_per_page"] = ['required', 'numeric', new MaxString(50)];
        $rules["blogs_per_page"] = ['required', 'numeric', new MaxString(50)];
        $rules["charts_count_on_dashboard"] = "required";
        $rules["default_landing_page"] = "required";
        $rules["enable_home_banner"] = "required";
        $rules["home_how_it_works"] = "required";
        $rules["home_department_section"] = "required";
        $rules["home_blogs_section"] = "required";
        $rules["testimonial"] = [new MinString(50), new MaxString(500)];

        $validator = Validator::make($request->all(), $rules, [
            'admin_email.required' => __('validation.required'),
            'from_email.required' => __('validation.required'),
            'jobs_per_page.required' => __('validation.required'),
            'jobs_per_page.numeric' => __('validation.numeric'),
            'blogs_per_page.required' => __('validation.required'),
            'blogs_per_page.numeric' => __('validation.numeric'),
            'charts_count_on_dashboard.required' => __('validation.required'),
            'default_landing_page.required' => __('validation.required'),
            'enable_home_banner.required' => __('validation.required'),
            'home_how_it_works.required' => __('validation.required'),
            'home_department_section.required' => __('validation.required'),
            'home_blogs_section.required' => __('validation.required'),
            'testimonial.min' => __('validation.min_string'),
            'testimonial.max' => __('validation.max_string'),
        ]);

        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Seggregating data
        $data = $request->all();
        $testimonial = $data['testimonial'];
        $rating = $data['rating'];
        unset($data['testimonial'], $data['rating']);

        //Updating settings
        Setting::updateData($data);

        //Updating testimonial
        Testimonial::storeTestimonial($testimonial, $rating);

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.settings').' '.__('message.updated')))
        )));
    }

    /**
     * View Function to display branding settings page view
     *
     * @return html/string
     */
    public function branding()
    {
        if (!empAllowedTo('branding')) {
            die(__('message.not_allowed'));
        }

        $data['page'] = __('message.settings').' '.__('message.branding');
        $data['menu'] = 'branding';
        return view('employer.settings.branding', $data);
    }

    /**
     * Function (for ajax) to process settings update form request
     *
     * @return redirect
     */
    public function updateBranding(Request $request)
    {
        $this->checkIfDemo();

        $rules["site_name"] = "max:100";
        $rules["site_keywords"] = "max:1000";
        $rules["site_description"] = "max:1000";
        $rules["banner_text"] = "max:1000";
        $rules["before_blogs_text"] = "max:10000";
        $rules["after_blogs_text"] = "max:10000";
        $rules["before_how_text"] = "max:10000";
        $rules["after_how_text"] = "max:10000";
        $rules["footer_col_1"] = "max:2000";
        $rules["footer_col_2"] = "max:2000";
        $rules["footer_col_3"] = "max:2000";
        $rules["footer_col_4"] = "max:2000";

        $validator = Validator::make($request->all(), $rules, [
            'site_name.max' => __('validation.max_string'),
            'site_keywords.max' => __('validation.max_string'),
            'site_description.max' => __('validation.max_string'),
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

        //Collecting simple fields
        $data["site_name"] = $request->input('site_name');
        $data["site_keywords"] = $request->input('site_keywords');
        $data["site_description"] = $request->input('site_description');

        //Sanitizing all the templates (Adjust broken html and remove malicious things i.e. xss)
        $data["banner_text"] = sanitizeHtmlTemplates(templateInput('banner_text'));
        $data["before_blogs_text"] = sanitizeHtmlTemplates(templateInput('before_blogs_text'));
        $data["after_blogs_text"] = sanitizeHtmlTemplates(templateInput('after_blogs_text'));
        $data["before_how_text"] = sanitizeHtmlTemplates(templateInput('before_how_text'));
        $data["after_how_text"] = sanitizeHtmlTemplates(templateInput('after_how_text'));
        $data["footer_col_1"] = sanitizeHtmlTemplates(templateInput('footer_col_1'));
        $data["footer_col_2"] = sanitizeHtmlTemplates(templateInput('footer_col_2'));
        $data["footer_col_3"] = sanitizeHtmlTemplates(templateInput('footer_col_3'));
        $data["footer_col_4"] = sanitizeHtmlTemplates(templateInput('footer_col_4'));

        $this->uploadLogo($request, $data);
        $this->uploadBanner($request, $data);
        $this->uploadFavicon($request, $data);

        Setting::updateData($data);

        if (isset($data['site_banner'])) {
            $variables = array(
                'body-bg',
                'main-menu-bg',
                'main-menu-font-color',
                'main-menu-font-highlight-color',
                'main-banner-bg',
                'main-banner-height',
                'breadcrumb-image',
                'main-banner',
            );
            $breadcrumb_image = isset($data['site_breadcrumb_image']) ? $data['site_breadcrumb_image'] : setting('site_breadcrumb_image');
            $site_banner = isset($data['site_banner']) ? $data['site_banner'] : setting('site_banner');
            $values = array(
                setting('body_bg'),
                setting('main_menu_bg'),
                setting('main_menu_font_color'),
                setting('main_menu_font_highlight_color'),
                setting('main_banner_bg'),
                setting('main_banner_height'),
                "url(".route('uploads-view', $breadcrumb_image).")",
                "url(".route('uploads-view', $site_banner).")",
            );
            Setting::updateCssVariables($variables, $values);
        }

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.settings').' '.__('message.updated')))
        )));
    }

    /**
     * View Function to display emails settings page view
     *
     * @return html/string
     */
    public function emails()
    {
        if (!empAllowedTo('emails')) {
            die(__('message.not_allowed'));
        }

        $data['page'] = __('message.settings').' '.__('message.emails');
        $data['menu'] = 'emails';
        return view('employer.settings.emails', $data);
    }

    /**
     * Function (for ajax) to process settings update form request
     *
     * @return redirect
     */
    public function updateEmails(Request $request)
    {
        $this->checkIfDemo();

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
        ]);

        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Preparing data for email templates
        $data = array(
            'candidate_job_app' => templateInput('candidate_job_app'),
            'employer_job_app' => templateInput('employer_job_app'),
            'employer_interview_assign' => templateInput('employer_interview_assign'),
            'candidate_interview_assign' => templateInput('candidate_interview_assign'),
            'candidate_quiz_assign' => templateInput('candidate_quiz_assign'),
            'team_creation' => templateInput('team_creation'),
        );

        //Checking to validate reserved words
        $this->reservedWordsValidation($data);

        //Sanitizing all the templates (Adjust broken html and remove malicious things i.e. xss)
        $data = sanitizeHtmlTemplates($data);

        //Updating data
        Setting::updateData($data);

        //Creating files for display in admin via iframe
        foreach ($data as $key => $value) {
            $filename = str_replace('_', '-', $key).'.html';
            $folder = storage_path('/app/'.config('constants.upload_dirs.main').'/'.employerPath().'/templates');
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            $filePath = storage_path('/app/'.config('constants.upload_dirs.main').'/'.employerPath().'/templates/'.$filename);
            $myfile = fopen($filePath, "w") or die("Unable to open file!");

            //Replacing tags with actual values
            $tags = array('((site_name))','((site_link))','((site_logo))');
            $separate_site = empMembership(employerId(), 'separate_site');
            $values = array(settingEmp('site_name'), frontEmpUrl(employerId('slug'), $separate_site), settingEmp('site_logo'));
            $template = replaceTagsInTemplate(settingEmp($key, true),$tags,$values);

            fwrite($myfile, $template);
            fclose($myfile);
        }

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('message' => __('message.settings').' '.__('message.updated')))
        )));
    }

    /**
     * Function (for ajax) to validate reserved words in templates
     *
     * @return void
     */
    private function reservedWordsValidation($data)
    {
        $error = '';

        $reservedWords = array('((first_name))','((last_name))','((job_title))','((site_name))','((site_link))','((site_logo))');
        if (checkReservedWords($reservedWords, $data['candidate_job_app'])) {
            $error .= __('message.these_words_are_reserved_in_template', array(
                'tags' => implode($reservedWords, ','), 
                'template' => __('message.candidate_job_app')
            )).'<br />';
        }

        $reservedWords = array('((job_title))','((site_name))','((site_link))','((site_logo))');
        if (checkReservedWords($reservedWords, $data['employer_job_app'])) {
            $error .= __('message.these_words_are_reserved_in_template', array(
                'tags' => implode($reservedWords, ','), 
                'template' => __('message.employer_job_app')
            ));
        }

        $reservedWords = array('((first_name))','((last_name))','((job_title))','((site_link))','((site_logo))', '((date_time))', '((description))');
        if (checkReservedWords($reservedWords, $data['employer_interview_assign'])) {
            $error .= __('message.these_words_are_reserved_in_template', array(
                'tags' => implode($reservedWords, ','), 
                'template' => __('message.employer_interview_assign')
            ));
        }

        $reservedWords = array('((first_name))','((last_name))','((job_title))','((site_name))','((site_link))','((site_logo))', '((date_time))', '((description))');
        if (checkReservedWords($reservedWords, $data['candidate_interview_assign'])) {
            $error .= __('message.these_words_are_reserved_in_template', array(
                'tags' => implode($reservedWords, ','), 
                'template' => __('message.candidate_interview_assign')
            ));
        }

        $reservedWords = array('((first_name))','((last_name))','((job_title))','((site_link))','((site_logo))','((quiz))');
        if (checkReservedWords($reservedWords, $data['candidate_quiz_assign'])) {
            $error .= __('message.these_words_are_reserved_in_template', array(
                'tags' => implode($reservedWords, ','), 
                'template' => __('message.candidate_quiz_assign')
            ));
        }

        $reservedWords = array('((first_name))','((last_name))','((email))','((password))', '((link))','((site_link))','((site_logo))');;
        if (checkReservedWords($reservedWords, $data['team_creation'])) {
            $error .= __('message.these_words_are_reserved_in_template', array(
                'tags' => implode($reservedWords, ','), 
                'template' => __('message.team_creation')
            ));
        }

        if ($error) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => $error))
            )));
        }
    }    

    /**
     * View Function to display css settings page view
     *
     * @return html/string
     */
    public function css()
    {
        if (!empAllowedTo('css')) {
            die(__('message.not_allowed'));
        }

        $data['page'] = __('message.settings').' '.__('message.css');
        $data['menu'] = 'css';
        return view('employer.settings.css', $data);
    }

    /**
     * Function (for ajax) to process css update form request
     *
     * @return redirect
     */
    public function updateCss(Request $request)
    {
        $this->checkIfDemo();

        //Writing to file
        $css = sanitizeHtmlTemplates(templateInput('css'));
        $dir_path = base_path().'/public/'.employerPath().'/custom-style.css';
        createDirectoryIfNotExists($dir_path);
        $file = fopen($dir_path, "w");
        fwrite($file, $css);
        fclose($file);

        //Saving to db
        Setting::updateData(array('css' => $css));

        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.settings_updated')))
        ));
    }


    private function uploadLogo($request, &$data)
    {
        $existing = settingEmp('site_logo', employerId());
        $path = employerPath().'/branding/';

        //Uploading logo
        $upload = $this->uploadPublicFile(
            $request, 
            'site_logo', 
            $path,            
            array('site_logo' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(generalFileUploadLimit())]),
            array('site_logo.image' => __('validation.image')),
            'site-logo-original'
        );

        if ($upload) {
            //Die if there is any error
            if ($upload['success'] == 'false') {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => $upload['message']))
                )));
            }

            //Delete previous file
            $full_path = storage_path('/app/'.config('constants.upload_dirs.main').'/'.$path);
            if ($upload['success'] == 'true') {
                if ($existing && file_exists($full_path.$existing) && $existing != $upload['message']) {
                    unlink($full_path.$existing);
                }
            }

            $ext = explode('.', $upload['message'])[1];
            $filepath = $full_path.'/site-logo-original.'.$ext;
            if (file_exists($filepath)) {
                $this->resizeByWidthOrHeight($full_path, 'site-logo', 'site-logo-original', $ext, 0, 45);
                foreach (array('png', 'jpg', 'jpeg', 'gif') as $extension) {
                    @unlink($full_path.'/site-logo-original.'.$extension);
                }
            }

            //adding filename to the passed array
            if ($upload['message']) {
                $data['site_logo'] = str_replace('-original', '', $upload['message']);
            }
        }
    }

    private function uploadBanner($request, &$data)
    {
        $existing = settingEmp('site_banner', employerId());
        $path = employerPath().'/branding/';

        //Uploading logo
        $upload = $this->uploadPublicFile(
            $request, 
            'site_banner', 
            $path, 
            array('site_banner' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(generalFileUploadLimit())]),
            array('site_banner.image' => __('validation.image')),
            'site-banner'
        );

        if ($upload) {
            //Die if there is any error
            if ($upload['success'] == 'false') {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => $upload['message']))
                )));
            }

            //Delete previous file
            $full_path = storage_path('/app/'.config('constants.upload_dirs.main').'/'.$path);
            if ($upload['success'] == 'true') {
                if ($existing && file_exists($full_path.$existing) && $existing != $upload['message']) {
                    unlink($full_path.$existing);
                }
            }

            //adding filename to the passed array
            if ($upload['message']) {
                $data['site_banner'] = $upload['message'];
            }
        }
    }

    private function uploadFavicon($request, &$data)
    {
        $existing = settingEmp('site_favicon', employerId());
        $path = employerPath().'/branding/';

        //Uploading logo
        $upload = $this->uploadPublicFile(
            $request, 
            'site_favicon', 
            $path, 
            array('site_favicon' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(generalFileUploadLimit())]),
            array('site_favicon.image' => __('validation.image')),
            'site-favicon-original'
        );

        if ($upload) {
            //Die if there is any error
            if ($upload['success'] == 'false') {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => $upload['message']))
                )));
            }

            //Delete previous file
            $full_path = storage_path('/app/'.config('constants.upload_dirs.main').'/'.$path);
            if ($upload['success'] == 'true') {
                if ($existing && file_exists($full_path.$existing) && $existing != $upload['message']) {
                    unlink($path.$existing);
                }
            }

            $ext = explode('.', $upload['message'])[1];
            $filepath = $full_path.'/site-favicon-original.'.$ext;
            if (file_exists($filepath)) {
                $this->resizeByWidthOrHeight($full_path, 'site-favicon', 'site-favicon-original', $ext, 0, 45);
                foreach (array('png', 'jpg', 'jpeg', 'gif') as $extension) {
                    @unlink($full_path.'/site-favicon-original.'.$extension);
                }
            }

            //adding filename to the passed array
            if ($upload['message']) {
                $data['site_favicon'] = str_replace('-original', '', $upload['message']);
            }
        }
    }
}
