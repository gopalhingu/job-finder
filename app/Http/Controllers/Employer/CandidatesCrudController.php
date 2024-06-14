<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\Candidate;
use App\Models\Admin\Employer;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

use SimpleExcel\SimpleExcel;
use Dompdf\Dompdf;
use Dompdf\Options;

class CandidatesCrudController extends Controller
{
    /**
     * View Function to display candidates list view page
     *
     * @return html/string
     */
    public function candidatesListView()
    {
        $data['page'] = __('message.candidates');
        $data['menu'] = 'candidates_crud';
        return view('employer.candidates.crud', $data);
    }

    /**
     * Function to get data for candidates jquery datatable
     *
     * @return json
     */
    public function candidatesList(Request $request)
    {
        echo json_encode(Candidate::candidatesList($request->all()));
    }

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $candidate_id
     * @return html/string
     */
    public function createOrEdit($candidate_id = NULL)
    {
        $data['candidate'] = objToArr(Candidate::getCandidate('candidate_id', $candidate_id));
        echo view('admin.candidates.create-or-edit', $data)->render();
    }

    /**
     * Function (for ajax) to process candidate create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('candidate_id') ? $request->input('candidate_id') : false;
        $rules['first_name'] = ['required', new MinString(2), new MaxString(50)];
        $rules['last_name'] = ['required', new MinString(2), new MaxString(50)];
        if ($edit) {
            $rules['email'] = 'required|email|unique:candidates,email,' . $edit . ',candidate_id';
        } else {
            $rules['email'] = 'required|email|unique:candidates';
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
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'password.required' => __('validation.required'),
            'phone.digits_between' => __('validation.digits_between'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $fileUpload = $this->uploadPublicFile(
            $request,
            'image',
            config('constants.upload_dirs.candidates'),
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
            $candidate = Candidate::getCandidate('candidate_id', $edit);
            $this->deleteOldFile($candidate['image']);
        }

        //updating db recrod
        Candidate::storeCandidate($request->all(), $edit, issetVal($fileUpload, 'message'));
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array(
                'success' => __('message.candidate') . ' ' . ($edit ? __('message.updated') : __('message.created'))
            ))
        )));
    }

    /**
     * Function (for ajax) to process candidate change status request
     *
     * @param integer $candidate_id
     * @param string $status
     * @return void
     */
    public function changeStatus($candidate_id = null, $status = null)
    {
        $this->checkIfDemo();
        Candidate::changeStatus($candidate_id, $status);
    }

    /**
     * Function (for ajax) to process candidate bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        Candidate::bulkAction($request->input('data'));
    }

    /**
     * Function (for ajax) to process candidate delete request
     *
     * @param integer $candidate_id
     * @return void
     */
    public function delete($candidate_id)
    {
        $this->checkIfDemo();

        //Deleting existing file
        $candidate = objToArr(Candidate::getCandidate('candidate_id', $candidate_id));
        $this->deleteOldFile($candidate['image']);

        Candidate::remove($candidate_id);
    }

    /**
     * Function (for ajax) to display form to send email to candidate
     *
     * @return void
     */
    public function messageView()
    {
        echo view('admin.candidates.message')->render();
    }

    /**
     * Function (for ajax) to send email to candidate
     *
     * @return void
     */
    public function message(Request $request)
    {
        $this->checkIfDemo();
        ini_set('max_execution_time', 5000);
        $data = $request->input();
        $candidates = explode(',', $data['ids']);

        $rules['msg'] = ['required', new MinString(10), new MaxString(10000)];
        $rules['subject'] = ['required', new MinString(2), new MaxString(100)];
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

        foreach ($candidates as $candidate_id) {
            $candidate = objToArr(Candidate::getCandidate('candidate_id', $candidate_id));
            $this->sendEmail(removeUselessLineBreaks($data['msg']), $candidate['email'], $data['subject']);
        }

        die(json_encode(array('success' => 'true', 'messages' => '')));
    }

    /**
     * Post Function to download candidate resume
     *
     * @return void
     */
    public function resumeDownload(Request $request)
    {
        ini_set('max_execution_time', '0');
        $ids = explode(',', $request->input('ids'));
        $resumes = '';
        foreach ($ids as $id) {
            $data['resume'] = Candidate::getCompleteResume($id);
            if ($data['resume']) {
                if ($data['resume']['type'] == 'detailed') {
                    $resumes .= view('admin.candidates.resume-pdf', $data)->render();
                } else {
                    $resumes .= "<hr />";
                    $resumes .= 'Resume of "' . $data['resume']['first_name'] . ' ' . $data['resume']['last_name'] . ' (' . $data['resume']['designation'] . ')" is static and can be downloaded separately';
                    $resumes .= "<br /><hr />";
                }
            }
        }

        if ($resumes) {
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($resumes);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream('Resumes.pdf');
        }
        exit;
    }

    /**
     * Post Function to download candidates data in excel
     *
     * @return void
     */
    public function candidatesExcel(Request $request)
    {
        $data = Candidate::getCandidatesForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');
        $excel->writer->setData($data);
        $excel->writer->saveFile('candidates');
        exit;
    }

    /**
     * Function to process login request by admin to login as candidate
     *
     * @param string $user_id
     * @param string $candidate_id
     * @return html/string
     */
    public function loginAs($candidate_id, $user_id)
    {
        //First decoding
        $user_id = decode($user_id);
        $candidate_id = decode($candidate_id);

        //Second checking if admin is logged in
        if ($user_id != adminSession()) {
            die(__('message.unauthorized'));
        }

        //Third checking if candidate exists
        $candidate = objToArr(Candidate::getCandidate('candidates.candidate_id', $candidate_id));

        if ($candidate['candidate_id'] != $candidate_id) {
            die(__('message.unauthorized'));
        }

        $employers = Employer::getAll();
        $slug = isset($employers[0]['slug']) ? $employers[0]['slug'] : die('error');

        //Forth loggin in as candidate if above two checks are correct
        setSession('candidate', $candidate);
        $sd = env('CFSAAS_ROUTE_SLUG') == 'slug' ? '' : '-sd';
        $slugOrSubDomainSlug = env('CFSAAS_ROUTE_SLUG') == 'slug' ? array('slug' => $slug) : array('subdomain_slug' => $slug);
        return redirect(route('candidate-home' . $sd, $slugOrSubDomainSlug));
    }
}
