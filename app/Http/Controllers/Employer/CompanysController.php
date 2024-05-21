<?php

namespace App\Http\Controllers\Employer;

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

use SimpleExcel\SimpleExcel;

use App\Models\Employer\Candidate;
use App\Models\Employer\Department;
use App\Models\Employer\Job;
use App\Models\Employer\APIjob;
use App\Models\Employer\JobFilter;
use App\Models\Employer\Traite;
use App\Models\Employer\Quiz;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CompanysController extends Controller
{
    public function createOrEdit($company_id = NULL)
    {
        $data['company'] = objToArr(Company::getCompany('company.company_id', $company_id));
        $data['page'] = __('message.company');
        $data['menu'] = 'company';
        return view('employer.company.create-or-edit', $data);
    }

    public function save(Request $request)
    {
        $edit = $request->input('company_id') ? $request->input('company_id') : false;

        $rules['first_name'] = ['required', new MinString(2), new MaxString(200)];
        $rules['last_name'] = ['required', new MinString(2), new MaxString(200)];
        $rules['slug'] = ['alpha_dash', new MinString(2), new MaxString(200)];
        $rules['password'] = 'required';
        if($edit) {
            $rules['companyname'] = ['required', new MinString(2), new MaxString(200)];
            $rules['email'] = 'required|email';
        } else {
            $rules['companyname'] = ['required', new MinString(2), new MaxString(200), 'unique:company'];
            $rules['email'] = 'required|email|unique:company';
        }

        $validator = Validator::make($request->all(), $rules, [
            'first_name.required' => __('validation.required'),
            'first_name.min' => __('validation.min_string'),
            'first_name.max' => __('validation.max_string'),
            'last_name.required' => __('validation.required'),
            'last_name.min' => __('validation.min_string'),
            'last_name.max' => __('validation.max_string'),
            'companyname.required' => __('validation.required'),
            'companyname.min' => __('validation.min_string'),
            'companyname.max' => __('validation.max_string'),
            'slug.alpha_dash' => __('validation.alpha_dash'),
            'slug.min' => __('validation.min_string'),
            'slug.max' => __('validation.max_string'),
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'password.required' => __('validation.required'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $data = $request->all();
        $company_id = Company::storeCompany($data, $edit);
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.company') . ($edit ? __('message.updated') : __('message.created')))),
            'data' => ($edit)? $company_id : encode($company_id)
        ));
    }

    public function listView()
    {
        $data['page'] = __('message.companys');
        $data['menu'] = 'companys';
        return view('employer.company.list', $data);
    }

    public function data(Request $request)
    {
        echo json_encode(Company::companysList($request->all()));
    }
    
    public function changeStatus($company_id = null, $status = null)
    {
        Company::changeStatus($company_id, $status);
    }

    public function delete($company_id)
    {
        Company::remove($company_id);
    }

    public function excel(Request $request)
    {
        $data = Company::getCompanysForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');
        $excel->writer->setData($data);
        $excel->writer->saveFile('companys');
        exit;
    }

    public function importView(Request $request)
    {
        $data['page'] = __('message.company');
        $data['menu'] = 'import_companys';
        return view('employer.company.import', $data);
    }

    public function importSave(Request $request)
    {
        $rules['import_file'] = ['required', 'mimes:csv,xlsx'];

        $validator = Validator::make($request->all(), $rules, [
            'import_file.required' => __('validation.required'),
            'import_file.mimes' => __('validation.import_excel_file_type'),
        ]);

        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $file = $request->file('import_file');
        $path = $file->getRealPath();
        $extension = $file->getClientOriginalExtension();

        

        try {
            if ($extension == 'csv') {
                // 
            } elseif ($extension == 'xlsx') {
                // 
            } else {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => __('validation.file_not_supported')))
                )));
            }

            $data = [];
        $excel = new SimpleExcel(strtolower($extension));

        $path = public_path("e-assets/excel/sample_companys.csv");

            $excel->parser->loadFile($path);
            $data = $excel->parser->getField();

            echo "<pre>";
            print_r($data);
            die;
        } catch (\Exception $e) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => $e->getMessage()))
            )));
        }
    }
}