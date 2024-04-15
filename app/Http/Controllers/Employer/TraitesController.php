<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Traite;
use App\Rules\MinString;
use App\Rules\MaxString;

class TraitesController extends Controller
{
    /**
     * View Function to display traits list view page
     *
     * @return html/string
     */
    public function listView()
    {
        $data['page'] = __('message.traites');
        $data['menu'] = 'traites';
        return view('employer.traites.list', $data);
    }

    /**
     * Function to get data for traites jquery datatable
     *
     * @return json
     */
    public function data(Request $request)
    {
        echo json_encode(Traite::traitesList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $traite_id
     * @return html/string
     */
    public function createOrEdit($traite_id = NULL)
    {
        $traite = objToArr(Traite::getTraite('traite_id', $traite_id));
        echo view('employer.traites/create-or-edit', compact('traite'))->render();
    }

    /**
     * Function (for ajax) to process traite create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('traite_id') ? $request->input('traite_id') : false;

        $this->checkActiveTraites($edit, $request->input('status'));

        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        } 

        if (Traite::valueExist('title', $request->input('title'), $edit)) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.traite_already_exist')))
            ));
        } else {
            Traite::storeTraite($request->all(), $edit);
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => __('message.traite').' ' . ($edit ? __('message.updated') : __('message.created'))))
            ));
        }
    }

    /**
     * Function (for ajax) to process traite change status request
     *
     * @param integer $traite_id
     * @param string $status
     * @return void
     */
    public function changeStatus($traite_id = null, $status = null)
    {
        $this->checkIfDemo();

        if ($status == '0') {
            $this->checkActiveTraites();
        }

        Traite::changeStatus($traite_id, $status);
    }

    /**
     * Function (for ajax) to process traite delete request
     *
     * @param integer $traite_id
     * @return void
     */
    public function delete($traite_id)
    {
        $this->checkIfDemo();
        Traite::remove($traite_id);
    }

    /**
     * Function to check active membership content
     *
     * @return html
     */
    private function checkActiveTraites($id = '', $status = '')
    {
        if ($id != '' && $status == '0') {
            return false;
        }

        //Checking if allowed in membership
        $totalActiveTraites = Traite::getTotalTraites($id);
        $totalAllowedActiveTraites = empMembership(employerId(), 'active_traites');
        if ($totalAllowedActiveTraites == '-1') {
            return false;
        }
        if ($totalActiveTraites >= $totalAllowedActiveTraites) {
            $detail = '<br />'.__('message.current_active').' : '.$totalActiveTraites;
            $detail .= '<br />'.__('message.allowed_in_membership').' : '.$totalAllowedActiveTraites.'<br />';
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.active_traites_limit_message').$detail))
            )));
        }        
    }    
}
