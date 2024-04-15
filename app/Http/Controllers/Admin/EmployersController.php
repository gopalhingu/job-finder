<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Models\Admin\Employer;
use App\Models\Admin\Role;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

use SimpleExcel\SimpleExcel;

class EmployersController extends Controller
{
    /**
     * View Function to display employers list view page
     *
     * @return html/string
     */
    public function employersListView()
    {
        $data['page'] = __('message.employers');
        $data['menu'] = 'employers';
        $data['roles'] = objToArr(Role::getAll('employer'));
        return view('admin.employers.list', $data);
    }

    /**
     * Function to get data for employers jquery datatable
     *
     * @return json
     */
    public function employersList(Request $request)
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
        $data['roles'] = objToArr(Role::getAll('employer'));
        $data['employerRoles'] = explode(',', Role::getEmployerRoles($employer_id));
        echo view('admin.employers.create-or-edit', $data)->render();
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
        $rules['first_name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['last_name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['image'] = ['image', 'mimes:jpeg,png,jpg,gif,svg', 'dimensions:max_height=250,max_width=250', new MaxFile(adminFileUploadLimit())];
        $rules['logo'] = ['image', 'mimes:jpeg,png,jpg,gif,svg', 'dimensions:max_height=250,max_width=250', new MaxFile(adminFileUploadLimit())];
        if ($edit) {
            $rules['email'] = 'required|email|unique:employers,email,'.$edit.',employer_id';
            $rules['company'] = ['required', new MinString(2), new MaxString(50), 'unique:employers,company,'.$edit.',employer_id'];
        } else {
            $rules['email'] = 'required|email|unique:employers';
            $rules['company'] = ['required', new MinString(2), new MaxString(50), 'unique:employers'];
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
            'company.required' => __('validation.required'),
            'company.min' => __('validation.min_string'),
            'company.max' => __('validation.max_string'),
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'password.required' => __('validation.required'),
            'phone.digits_between' => __('validation.digits_between'),
            'image.image' => __('validation.image'),
            'image.dimensions' => __('validation.dimensions').' ('.__('message.max_allowed').' 250x250px)',
            'logo.image' => __('validation.image'),
            'logo.dimensions' => __('validation.dimensions').' ('.__('message.max_allowed').' 250x250px)'
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        //Uploading image if any
        $imageUpload = $this->uploadPublicFile(
            $request, 'image', config('constants.upload_dirs.employers'), 
            array('image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'dimensions:max_height=250,max_width=250', new MaxFile(adminFileUploadLimit())]),
            array('image.image' => __('validation.image'), 'image.dimensions' => __('validation.dimensions').' ('.__('message.max_allowed').' 250x250px)')
        );

        //Deleting existing file if new is uploaded
        if (issetVal($imageUpload, 'success') == 'true' && $edit) {
            $employer = objToArr(Employer::getEmployer('employer_id', $edit));
            $this->deleteOldFile($employer['image']);
        }

        //Uploading image if any
        $logoUpload = $this->uploadPublicFile(
            $request, 'logo', config('constants.upload_dirs.employers'), 
            array('logo' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'dimensions:max_height=250,max_width=250', new MaxFile(adminFileUploadLimit())]),
            array('logo.image' => __('validation.image'), 'logo.dimensions' => __('validation.dimensions').' ('.__('message.max_allowed').' 250x250px)')
        );

        //Deleting existing file if new is uploaded
        if (issetVal($logoUpload, 'success') == 'true' && $edit) {
            $employer = objToArr(Employer::getEmployer('employer_id', $edit));
            $this->deleteOldFile($employer['logo']);
        }

        //updating db recrod
        $employer_id = Employer::storeEmployer($request->all(), $edit, issetVal($imageUpload, 'message'), issetVal($logoUpload, 'message'));
        Employer::importEmployerSettings($employer_id);
        if (setting('import_employer_dummy_data_on_creation') == 'yes') {
            Employer::importEmployerDummyData($employer_id);
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
        Employer::changeStatus($employer_id, $status);
    }

    /**
     * Function (for ajax) to process employer bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        Employer::bulkAction($request->input('data'));
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

        //Getting the detail
        $employer = objToArr(Employer::getEmployer('employer_id', $employer_id));
        $this->deleteOldFile($employer['image']);

        //Deleting the folder
        $employerFolder = storage_path('/app/'.config('constants.upload_dirs.main').'/'.config('constants.upload_dirs.employers').$employer['slug']);
        deleteDirWithContents($employerFolder);
        if (is_file($employerFolder)) {
            unlink($employerFolder);
        }

        //Deleting contents from all the tables
        $tables = DB::select('SHOW TABLES');
        $tables = array_values(objToArr($tables));
        $counts = array();
        foreach ($tables as $key => $table) {
            $table = array_values($table)[0];
            $table = str_replace('cf_', '', $table);
            if (Schema::hasColumn($table, 'employer_id')) {
                DB::table($table)->where('employer_id', $employer_id)->delete();
                DB::table('employers')->where('parent_id', $employer_id)->delete();
            }
        }

        //Deleting the employer finally.
        Employer::remove($employer_id);
    }

    /**
     * Function (for ajax) to display form to send email to employer
     *
     * @return void
     */
    public function messageView()
    {
        echo view('admin.employers.message')->render();
    }

    /**
     * Function (for ajax) to send email to employer
     *
     * @return void
     */
    public function message(Request $request)
    {
        $this->checkIfDemo();
        ini_set('max_execution_time', 5000);
        $data = $request->input();
        $employers = explode(',', $data['ids']);

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

        foreach ($employers as $employer_id) {
            $employer = objToArr(Employer::getEmployer('employers.employer_id', $employer_id));
            $this->sendEmail(removeUselessLineBreaks($data['msg']), $employer['email'], $data['subject']);
        }

        echo json_encode(array(
            'success' => 'true',
            'messages' => ''
        ));
    }

    /**
     * Post Function to download employers data in excel
     *
     * @return void
     */
    public function employersExcel(Request $request)
    {
        $data = Employer::getEmployersForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');                    
        $excel->writer->setData($data);
        $excel->writer->saveFile('employers'); 
        exit;
    }

    /**
     * Function to process login request by admin to login as employer
     *
     * @param string $user_id
     * @param string $employer_id
     * @return html/string
     */
    public function loginAs($employer_id, $user_id)
    {
        //First decoding
        $user_id = decode($user_id);
        $employer_id = decode($employer_id);

        //Second checking if admin is logged in
        if ($user_id != adminSession()) {
            die(__('message.unauthorized'));
        }

        //Third checking if employer exists
        $employer = objToArr(Employer::getEmployer('employers.employer_id', $employer_id));

        if ($employer['employer_id'] != $employer_id) {
            die(__('message.unauthorized'));   
        }

        //Forth loggin in as employer if above two checks are correct
        setSession('employer', $employer);
        return redirect(route('employer-dashboard'));
    }

}