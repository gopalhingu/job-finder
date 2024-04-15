<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\CandidateMembership;
use App\Models\Admin\Candidate;
use App\Models\Admin\CandidatePackage;
use App\Rules\MinString;
use App\Rules\MaxString;

use SimpleExcel\SimpleExcel;

class CandidateMembershipsController extends Controller
{
    /**
     * View Function to display candidate_memberships list view page
     *
     * @return html/string
     */
    public function itemsListView()
    {
        $data['page'] = __('message.candidate_memberships');
        $data['menu'] = 'candidate-memberships';
        $data['candidate_packages'] = objToArr(CandidatePackage::getAll());
        $data['candidates'] = objToArr(Candidate::getAll());
        $data['payment_types'] = CandidateMembership::$payment_types;
        $data['package_types'] = CandidateMembership::$package_types;
        return view('admin.candidate-memberships.list', $data);
    }

    /**
     * Function to get data for candidate_memberships jquery datatable
     *
     * @return json
     */
    public function itemsList(Request $request)
    {
        echo json_encode(CandidateMembership::itemsList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $candidate_membership_id
     * @return html/string
     */
    public function createOrEdit($candidate_membership_id = NULL)
    {
        $data['candidate_membership'] = objToArr(CandidateMembership::getCandidateMembership('candidate_membership_id', $candidate_membership_id));
        $data['candidate_packages'] = objToArr(CandidatePackage::getAll());
        $data['candidates'] = objToArr(Candidate::getAll());
        $data['payment_types'] = CandidateMembership::$payment_types;
        $data['package_types'] = CandidateMembership::$package_types;
        echo view('admin.candidate-memberships.create-or-edit', $data)->render();
    }

    /**
     * Function (for ajax) to process candidate_membership create or edit form request
     *
     * @return redirect
     */
    public function saveItem(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('candidate_membership_id') ? $request->input('candidate_membership_id') : false;      

        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $rules['price_paid'] = 'required|numeric';
        $rules['expiry'] = 'required|date';
        $rules['candidate_id'] = 'required';

        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'price_paid.required' => __('validation.required'),
            'price_paid.numeric' => __('validation.numeric'),
            'expiry.required' => __('validation.required'),
            'expiry.date' => __('validation.date'),
            'candidate_id.required' => __('validation.required'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        CandidateMembership::storeCandidateMembership($request->all(), $edit);
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array(
                'success' => __('message.membership').' ' . ($edit ? __('message.updated') : __('message.created'))
        )))));
    }

    /**
     * Function (for ajax) to process membership change status request
     *
     * @param integer $candidate_membership_id
     * @param string $status
     * @return void
     */
    public function changeStatus($candidate_membership_id = null, $status = null)
    {
        $this->checkIfDemo();
        CandidateMembership::changeStatus($candidate_membership_id, $status);
    }

    /**
     * Function (for ajax) to process membership bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        CandidateMembership::bulkAction($request->input('data'));
    }

    /**
     * Function (for ajax) to process membership delete request
     *
     * @param integer $candidate_membership_id
     * @return void
     */
    public function delete($candidate_membership_id)
    {
        $this->checkIfDemo();
        CandidateMembership::remove($candidate_membership_id);
    }

    /**
     * Post Function to download items data in excel
     *
     * @return void
     */
    public function itemsExcel(Request $request)
    {
        $data = CandidateMembership::getCandidateMembershipsForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');                    
        $excel->writer->setData($data);
        $excel->writer->saveFile('candidate-memberships'); 
        exit;
    }
}