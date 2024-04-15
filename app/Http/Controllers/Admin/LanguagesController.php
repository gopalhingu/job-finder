<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\Language;
use App\Rules\MinString;
use App\Rules\MaxString;

class LanguagesController extends Controller
{
    /**
     * View Function to display languages list view page
     *
     * @return html/string
     */
    public function listView()
    {
        $data['page'] = __('message.languages');
        $data['menu'] = 'languages';
        return view('admin.languages.list', $data);
    }

    /**
     * Function to get data for languages jquery datatable
     *
     * @return json
     */
    public function data(Request $request)
    {
        echo json_encode(Language::languagesList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @return html/string
     */
    public function createOrEdit(Request $request, $language_id = NULL)
    {
        $language = objToArr(Language::getLanguage('language_id', $language_id));
        echo view('admin.languages.create-or-edit', compact('language'))->render();
    }

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $language_id
     * @return html/string
     */
    public function editMessages($language_id = NULL)
    {
        if ($language_id == 1) {
            redirect('admin/languages');
        }
        $language = objToArr(Language::getLanguage('language_id', $language_id));

        //Getting the default lang array
        include(languagePath('origin/message.php'));
        $default = $lang;

        //Getting the selected lang array
        include(languagePath($language['slug'].'/message.php'));
        $entries = $lang;

        $data['page'] = __('message.languages');
        $data['menu'] = 'languages';
        $data['language'] = $language;
        $data['entries'] = $entries;
        $data['default'] = $default;
        return view('admin.languages.edit', $data);
    }

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $language_id
     * @return html/string
     */
    public function editValidations($language_id = NULL)
    {
        if ($language_id == 1) {
            redirect('admin/languages');
        }
        $language = objToArr(Language::getLanguage('language_id', $language_id));

        //Getting the default lang array
        include(languagePath('origin/validation.php'));
        $default = $langValidation;

        //Getting the selected lang array
        include(languagePath($language['slug'].'/validation.php'));
        $entries = $langValidation;

        $data['page'] = __('message.languages');
        $data['menu'] = 'languages';
        $data['language'] = $language;
        $data['entries'] = $entries;
        $data['default'] = $default;
        return view('admin.languages.edit-validations', $data);
    }

    /**
     * Function (for ajax) to process language create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();
        $edit = $request->input('language_id') ? $request->input('language_id') : false;
        if ($edit) {
            $rules['title'] = ['required', new MinString(2), new MaxString(50), 'unique:languages,title,'.$edit.',language_id'];
        } else {
            $rules['title'] = ['required', new MinString(2), new MaxString(50), 'unique:languages'];
        }

        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'title.unique' => __('validation.unique'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Storing language to db
        $data = Language::storeLanguage($request->all(), $edit);

        if (!$edit) {
            //Creating directory for new language
            $dir = languagePath($data['slug']);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            //Creating message file
            $message_file = languagePath($data['slug'].'/message.php');
            if (!file_exists($message_file)) {
                //Loading message strings from default language
                include(languagePath('origin/message.php'));

                //Creating other file and copying
                $message_file = fopen($message_file, "w");
                fwrite($message_file, arrayToString($lang, $data['slug']));
                fclose($message_file);
            }

            //Creating message file
            $validation_file = languagePath($data['slug'].'/validation.php');
            if (!file_exists($validation_file)) {
                //Loading validation strings from default language
                include(languagePath('origin/validation.php'));

                //Creating other file and copying
                $validation_file = fopen($validation_file, "w");
                fwrite($validation_file, arrayToString($langValidation, $data['slug'], 'validation'));
                fclose($validation_file);
            }
            $message = __('message.created');
        } else {
            $message = __('message.updated');
        }

        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.language') . $message)),
            'data' => $data
        )));
    }

    /**
     * Function (for ajax) to process language update form request
     *
     * @return redirect
     */
    public function updateMessages(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('language_id') ? $request->input('language_id') : false;
        $rules['title'] = ['required', new MinString(2), new MaxString(50), 'unique:languages'];        
        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'title.unique' => __('validation.unique'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        if (Language::valueExist('title', $request->input('title'), $edit)) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.language_already_exist')))
            ));
        } else {
            $data = $request->input();

            //removing some variables
            $language_id = $data['language_id'];
            unset($data['language_title'], $data['language_id'], $data['_token']);

            //Writing to file
            $language = objToArr(Language::getLanguage('language_id', $language_id));
            $file = fopen(languagePath($language['slug'].'/message.php'), "w");
            fwrite($file, arrayToString($data));
            fclose($file);
            die(json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => __('message.language') . ($edit ? __('message.updated') : __('message.created')))),
            )));
        }
    }

    /**
     * Function (for ajax) to process language update form request
     *
     * @return redirect
     */
    public function updateValidations(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('language_id') ? $request->input('language_id') : false;

        $data = $request->input();

        //removing some variables
        $language_id = $data['language_id'];
        unset($data['language_title'], $data['language_id'], $data['_token']);

        //Writing to file
        $language = objToArr(Language::getLanguage('language_id', $language_id));
        $file = fopen(languagePath($language['slug'].'/validation.php'), "w");
        fwrite($file, arrayToString($data, '', 'validation'));
        fclose($file);
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.language') . ($edit ? __('message.updated') : __('message.created')))),
        )));
    }

    /**
     * Function (for ajax) to process language change status request
     *
     * @param integer $language_id
     * @param string $status
     * @return void
     */
    public function changeStatus($language_id = null, $status = null)
    {
        $this->checkIfDemo();
        Language::changeStatus($language_id, $status);
    }

    /**
     * Function (for ajax) to process language change status request
     *
     * @param integer $language_id
     * @param string $status
     * @return void
     */
    public function changeDefault($language_id = null)
    {
        $this->checkIfDemo();
        Language::changeDefault($language_id);
    }

    /**
     * Function (for ajax) to process language change selected request
     *
     * @param integer $language_id
     * @return void
     */
    public function changeSelected($language_id = null)
    {
        $this->checkIfDemo();
        Language::changeSelected($language_id);

        //Getting selected language from db
        $language = objToArr(Language::getLanguage('language_id', $language_id));

        //Loading strings/file of selected language
        $messages = include(languagePath($language['slug'].'/message.php'));
        $validationMessages = include(languagePath($language['slug'].'/validation.php'));

        //Writing to js lang.js file
        $file = fopen(public_path().'/g-assets/js/lang.js', "w");
        $lang = array_merge($messages, $validationMessages);
        fwrite($file, arrayToStringJs($lang));
        fclose($file);
    }

    /**
     * Function (for ajax) to process language bulk action request
     *
     * @return void
     */
    public function bulkAction()
    {
        $this->checkIfDemo();
        Language::bulkAction();
    }

    /**
     * Function (for ajax) to process language delete request
     *
     * @param integer $language_id
     * @return void
     */
    public function delete($language_id)
    {
        $this->checkIfDemo();

        //Deleting directory with files
        $language = objToArr(Language::getLanguage('language_id', $language_id));
        $dirname = languagePath($language['slug']);
        if (file_exists($dirname)) {
            array_map('unlink', glob("$dirname/*.*"));
            rmdir($dirname);
        }

        //Deleting from db
        Language::remove($language_id);
    }

    /**
     * Function to display languages dropdown
     *
     * @return void
     */
    public function languageSelect($type = 'front')
    {
        $data['languages'] = Language::getAll();
        if ($type == 'employer') {
            return view('employer.partials.language-select', $data)->render();
        } else {
            return view('front.'.viewPrfx().'.partials.language-select', $data)->render();
        }
    }

}
