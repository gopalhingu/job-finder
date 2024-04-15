<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\Package;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

use SimpleExcel\SimpleExcel;

class PackagesController extends Controller
{
    /**
     * View Function to display packages list view page
     *
     * @return html/string
     */
    public function packagesListView()
    {
        $data['page'] = __('message.packages');
        $data['menu'] = 'packages';
        return view('admin.packages.list', $data);
    }

    /**
     * Function to get data for packages jquery datatable
     *
     * @return json
     */
    public function packagesList(Request $request)
    {
        echo json_encode(Package::packagesList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $package_id
     * @return html/string
     */
    public function createOrEdit($package_id = NULL)
    {
        $data['package'] = objToArr(Package::getPackage('package_id', $package_id));
        echo view('admin.packages.create-or-edit', $data)->render();
    }

    /**
     * Function (for ajax) to process package create or edit form request
     *
     * @return redirect
     */
    public function savePackage(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('package_id') ? $request->input('package_id') : false;

        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $rules['currency'] = ['required', new MinString(1), new MaxString(50)];
        $rules['description'] = [new MaxString(5000)];
        $rules['monthly_price'] = 'required|numeric';
        $rules['yearly_price'] = 'required|numeric';
        $rules['active_jobs'] = 'required|integer';
        $rules['active_users'] = 'required|integer';
        $rules['active_custom_filters'] = 'required|integer';
        $rules['active_quizes'] = 'required|integer';
        $rules['active_interviews'] = 'required|integer';
        $rules['active_traites'] = 'required|integer';

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
            'active_jobs.required' => __('validation.required'),
            'active_jobs.integer' => __('validation.integer'),
            'active_users.required' => __('validation.required'),
            'active_users.integer' => __('validation.integer'),
            'active_custom_filters.required' => __('validation.required'),
            'active_custom_filters.integer' => __('validation.integer'),
            'active_quizes.required' => __('validation.required'),
            'active_quizes.integer' => __('validation.integer'),
            'active_interviews.required' => __('validation.required'),
            'active_interviews.integer' => __('validation.integer'),
            'active_traites.required' => __('validation.required'),
            'active_traites.integer' => __('validation.integer'),
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
            $package = Package::getPackage('package_id', $edit);
            $this->deleteOldFile($package['image']);
        }

        Package::storePackage($request->all(), $edit, issetVal($fileUpload, 'message'));
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array(
                'success' => __('message.package').' ' . ($edit ? __('message.updated') : __('message.created'))
        )))));
    }

    /**
     * Function (for ajax) to process package change status request
     *
     * @param integer $package_id
     * @param string $status
     * @return void
     */
    public function changeStatus($package_id = null, $status = null)
    {
        $this->checkIfDemo();
        Package::changeStatus($package_id, $status);
    }

    /**
     * Function (for ajax) to process package change status for free request
     *
     * @param integer $package_id
     * @param string $status
     * @return void
     */
    public function changeStatusFree($package_id = null, $status = null)
    {
        $this->checkIfDemo();
        Package::changeStatusFree($package_id, $status);
    }

    /**
     * Function (for ajax) to process package change status for top sale request
     *
     * @param integer $package_id
     * @param string $status
     * @return void
     */
    public function changeStatusTop($package_id = null, $status = null)
    {
        $this->checkIfDemo();
        Package::changeStatusTop($package_id, $status);
    }

    /**
     * Function (for ajax) to process package bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        Package::bulkAction($request->input('data'));
    }

    /**
     * Function (for ajax) to process package delete request
     *
     * @param integer $package_id
     * @return void
     */
    public function delete($package_id)
    {
        $this->checkIfDemo();
        Package::remove($package_id);
    }

    /**
     * Post Function to download packages data in excel
     *
     * @return void
     */
    public function packagesExcel(Request $request)
    {
        $data = Package::getPackagesForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');                    
        $excel->writer->setData($data);
        $excel->writer->saveFile('packages'); 
        exit;
    }
}