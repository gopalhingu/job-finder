<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\CandidatePackage;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

use SimpleExcel\SimpleExcel;

class CandidatePackagesController extends Controller
{
    /**
     * View Function to display candidate_packages list view page
     *
     * @return html/string
     */
    public function candidatePackagesListView()
    {
        $data['page'] = __('message.candidate_packages');
        $data['menu'] = 'candidate-packages';
        return view('admin.candidate-packages.list', $data);
    }

    /**
     * Function to get data for candidate_packages jquery datatable
     *
     * @return json
     */
    public function candidatePackagesList(Request $request)
    {
        echo json_encode(CandidatePackage::itemsList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $candidate_package_id
     * @return html/string
     */
    public function createOrEdit($candidate_package_id = NULL)
    {
        $data['candidate_package'] = objToArr(CandidatePackage::getSingle('candidate_package_id', $candidate_package_id));
        echo view('admin.candidate-packages.create-or-edit', $data)->render();
    }

    /**
     * Function (for ajax) to process candidate_package create or edit form request
     *
     * @return redirect
     */
    public function saveCandidatePackage(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('candidate_package_id') ? $request->input('candidate_package_id') : false;

        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $rules['currency'] = ['required', new MinString(1), new MaxString(50)];
        $rules['description'] = [new MaxString(5000)];
        $rules['monthly_price'] = 'required|numeric';
        $rules['yearly_price'] = 'required|numeric';
     
        $rules['allowed_job_applications'] = 'required|integer';
        $rules['allowed_resumes'] = 'required|integer';

        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'currency.required' => __('validation.required'),
            'currency.min' => __('validation.min_string'),
            'currency.max' => __('validation.max_string'),
            'description.required' => __('validation.required'),
            'description.min' => __('validation.min_string'),
            'description.max' => __('validation.max_string'),
            'monthly_price.required' => __('validation.required'),
            'monthly_price.integer' => __('validation.integer'),
            'yearly_price.required' => __('validation.required'),
            'yearly_price.integer' => __('validation.integer'),
            'allowed_job_applications.required' => __('validation.required'),
            'allowed_job_applications.integer' => __('validation.integer'),
            'allowed_resumes.required' => __('validation.required'),
            'allowed_resumes.integer' => __('validation.integer'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $fileUpload = $this->uploadPublicFile(
            $request, 'image', config('constants.upload_dirs.general'), 
            array('image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(adminFileUploadLimit())]),
            array('image.image' => __('validation.image'))
        );

        if (issetVal($fileUpload, 'success') == 'false') {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => $fileUpload['message']))
            )));
        }

        //Deleting existing file
        if (issetVal($fileUpload, 'success') == 'true' && $edit) {
            $candidate_package = CandidatePackage::getSingle('candidate_package_id', $edit);
            $this->deleteOldFile($candidate_package['image']);
        }

        CandidatePackage::storeItem($request->all(), $edit, issetVal($fileUpload, 'message'));
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array(
                'success' => __('message.candidate_package').' ' . ($edit ? __('message.updated') : __('message.created'))
        )))));
    }

    /**
     * Function (for ajax) to process candidate_package change status request
     *
     * @param integer $candidate_package_id
     * @param string $status
     * @return void
     */
    public function changeStatus($candidate_package_id = null, $status = null)
    {
        $this->checkIfDemo();
        CandidatePackage::changeStatus($candidate_package_id, $status);
    }

    /**
     * Function (for ajax) to process candidate_package change status for free request
     *
     * @param integer $candidate_package_id
     * @param string $status
     * @return void
     */
    public function changeStatusFree($candidate_package_id = null, $status = null)
    {
        $this->checkIfDemo();
        CandidatePackage::changeStatusFree($candidate_package_id, $status);
    }

    /**
     * Function (for ajax) to process candidate_package change status for top sale request
     *
     * @param integer $candidate_package_id
     * @param string $status
     * @return void
     */
    public function changeStatusTop($candidate_package_id = null, $status = null)
    {
        $this->checkIfDemo();
        CandidatePackage::changeStatusTop($candidate_package_id, $status);
    }

    /**
     * Function (for ajax) to process candidate_package bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        CandidatePackage::bulkAction($request->input('data'));
    }

    /**
     * Function (for ajax) to process candidate_package delete request
     *
     * @param integer $candidate_package_id
     * @return void
     */
    public function delete($candidate_package_id)
    {
        $this->checkIfDemo();
        CandidatePackage::remove($candidate_package_id);
    }

    /**
     * Post Function to download candidate_packages data in excel
     *
     * @return void
     */
    public function candidatePackagesExcel(Request $request)
    {
        $data = CandidatePackage::getItemsForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');                    
        $excel->writer->setData($data);
        $excel->writer->saveFile('candidate_packages'); 
        exit;
    }
}