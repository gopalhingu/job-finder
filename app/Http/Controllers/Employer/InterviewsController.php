<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use SimpleExcel\SimpleExcel;
use Dompdf\Dompdf;

use App\Models\Employer\Interview;
use App\Models\Employer\InterviewCategory;
use App\Rules\MinString;
use App\Rules\MaxString;

class InterviewsController extends Controller
{
    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $interview_id
     * @return html/string
     */
    public function createOrEdit($interview_id = NULL)
    {
        $data['interview'] = objToArr(Interview::get('interview_id', $interview_id));
        $data['interview_categories'] = InterviewCategory::getAll();
        echo view('employer.interviews.create-or-edit', $data)->render();
    }

    /**
     * Function (for ajax) to process interview create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo(); 

        $edit = $request->input('interview_id') ? $request->input('interview_id') : false;

        $this->checkActiveInterviews($edit, $request->input('status'));

        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $rules['description'] = ['required', new MinString(10), new MaxString(2500)];
        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'description.required' => __('validation.required'),
            'description.min' => __('validation.min_string'),
            'description.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        if (Interview::valueExist('title', $request->input('title'), $edit)) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.interview_already_exist')))
            ));
        } else {
            $result = Interview::store($request->all(), $edit);
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => __('message.interview') . ($edit ? __('message.updated') : __('message.created')))),
                'data' => $result
            ));
        }
    }

    /**
     * Function (for ajax) to process interview delete request
     *
     * @param integer $interview_id
     * @return void
     */
    public function delete($interview_id)
    {
        $this->checkIfDemo();
        Interview::remove($interview_id);
    }

    /**
     * View Function (for ajax) to return interview dropdown list
     *
     * @param integer $interview_category_id
     * @return html/string
     */
    public function dropdown($interview_category_id = NULL)
    {
        echo json_encode(Interview::getDropDown($interview_category_id));
    }   

    /**
     * View Function (for ajax) to display clone form page via modal
     *
     * @param integer $interview_id
     * @return html/string
     */
    public function cloneForm($interview_id = NULL)
    {
        if ($interview_id != '0') {
            $data['interview'] = objToArr(Interview::get('interview_id', $interview_id));
            $data['interview_categories'] = InterviewCategory::getAll();
            echo view('employer.interviews.clone', $data)->render();
        } else {
            echo view('employer.interviews.no-interview', array())->render();
        }
    }

    /**
     * Function (for ajax) to process interview clone form request
     *
     * @return redirect
     */
    public function cloneInterview(Request $request)
    {
        $this->checkIfDemo();

        $this->checkActiveInterviews('', $request->input('status'));

        $edit = $request->input('interview_id') ? $request->input('interview_id') : false;
        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $rules['description'] = ['required', new MinString(10), new MaxString(2500)];
        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'description.required' => __('validation.required'),
            'description.min' => __('validation.min_string'),
            'description.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        if (Interview::valueExist('title', $request->input('title'), $edit)) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.interview_already_exist')))
            ));
        } else {
            $result = Interview::cloneInterview($request->all());
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => __('message.interview_cloned'))),
                'data' => $result
            ));
        }
    }

    /**
     * Post Function to download interview
     *
     * @param integer $interview_id
     * @return void
     */
    public function download($interview_id = NULL)
    {
        $result = Interview::getCompleteInterview($interview_id);
        $interview = view('employer.interviews/interview-pdf', $result)->render();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($interview);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('interview.pdf');
        exit;
    }

    /**
     * Function to check active membership content
     *
     * @return html
     */
    private function checkActiveInterviews($id = '', $status = '')
    {
        if ($id != '' && $status == '0') {
            return false;
        }

        //Checking if allowed in membership
        $totalActiveInterviews = Interview::getTotalInterviews($id);
        $totalAllowedActiveInterviews = empMembership(employerId(), 'active_quizes');
        if ($totalAllowedActiveInterviews == '-1') {
            return false;
        }
        if ($totalActiveInterviews >= $totalAllowedActiveInterviews) {
            $detail = '<br />'.__('message.current_active').' : '.$totalActiveInterviews;
            $detail .= '<br />'.__('message.allowed_in_membership').' : '.$totalAllowedActiveInterviews.'<br />';
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.active_interviews_limit_message').$detail))
            )));
        }        
    }    
}
