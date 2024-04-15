<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\Membership;
use App\Models\Admin\Employer;
use App\Models\Admin\Package;
use App\Rules\MinString;
use App\Rules\MaxString;

use SimpleExcel\SimpleExcel;

class MembershipsController extends Controller
{
    /**
     * View Function to display memberships list view page
     *
     * @return html/string
     */
    public function membershipsListView()
    {
        $data['page'] = __('message.memberships');
        $data['menu'] = 'memberships';
        $data['packages'] = objToArr(Package::getAll());
        $data['employers'] = objToArr(Employer::getAll());
        $data['payment_types'] = Membership::$payment_types;
        $data['package_types'] = Membership::$package_types;
        return view('admin.memberships.list', $data);
    }

    /**
     * Function to get data for memberships jquery datatable
     *
     * @return json
     */
    public function membershipsList(Request $request)
    {
        echo json_encode(Membership::membershipsList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $membership_id
     * @return html/string
     */
    public function createOrEdit($membership_id = NULL)
    {
        $data['membership'] = objToArr(Membership::getMembership('membership_id', $membership_id));
        $data['packages'] = objToArr(Package::getAll());
        $data['employers'] = objToArr(Employer::getAll());
        $data['payment_types'] = Membership::$payment_types;
        $data['package_types'] = Membership::$package_types;
        echo view('admin.memberships.create-or-edit', $data)->render();
    }

    /**
     * Function (for ajax) to process membership create or edit form request
     *
     * @return redirect
     */
    public function saveMembership(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('membership_id') ? $request->input('membership_id') : false;      

        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $rules['price_paid'] = 'required|numeric';
        $rules['expiry'] = 'required|date';
        $rules['employer_id'] = 'required';

        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'price_paid.required' => __('validation.required'),
            'price_paid.numeric' => __('validation.numeric'),
            'expiry.required' => __('validation.required'),
            'expiry.date' => __('validation.date'),
            'employer_id.required' => __('validation.required'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        Membership::storeMembership($request->all(), $edit);
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array(
                'success' => __('message.membership').' ' . ($edit ? __('message.updated') : __('message.created'))
        )))));
    }

    /**
     * Function (for ajax) to process membership change status request
     *
     * @param integer $membership_id
     * @param string $status
     * @return void
     */
    public function changeStatus($membership_id = null, $status = null)
    {
        $this->checkIfDemo();
        Membership::changeStatus($membership_id, $status);
    }

    /**
     * Function (for ajax) to process membership bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        Membership::bulkAction($request->input('data'));
    }

    /**
     * Function (for ajax) to process membership delete request
     *
     * @param integer $membership_id
     * @return void
     */
    public function delete($membership_id)
    {
        $this->checkIfDemo();
        Membership::remove($membership_id);
    }

    /**
     * Post Function to download memberships data in excel
     *
     * @return void
     */
    public function membershipsExcel(Request $request)
    {
        $data = Membership::getMembershipsForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');                    
        $excel->writer->setData($data);
        $excel->writer->saveFile('memberships'); 
        exit;
    }
}