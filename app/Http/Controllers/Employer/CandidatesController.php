<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Candidate;
use App\Rules\MinString;
use App\Rules\MaxString;

use SimpleExcel\SimpleExcel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;

class CandidatesController extends Controller
{
    /**
     * View Function to display candidates list view page
     *
     * @return html/string
     */
    public function listView()
    {
        $data['page'] = __('message.candidates');
        $data['menu'] = 'candidates';
        return view('employer.candidates.list', $data);
    }

    /**
     * Function to get data for candidates jquery datatable
     *
     * @return json
     */
    public function data(Request $request)
    {
        echo json_encode(Candidate::candidatesList($request->all()));
    }

    /**
     * Function (for ajax) to process candidate bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        Candidate::bulkAction($request->all());
    }

    /**
     * Function (for ajax) to display candidate resume
     *
     * @param integer $candidate_id
     * @return void
     */
    public function resume($candidate_id)
    {
        $resume = Candidate::getCompleteResume($candidate_id);
        if ($resume) {
            $data['resume_id'] = $resume['resume_id'];
            $data['resume_file'] = $resume['file'];
            $data['type'] = $resume['type'];
            $data['file'] = $resume['file'];
            $data['resume'] = $resume;
            echo view('employer.candidates.resume', $data)->render();
        } else {
            echo __('message.no_resumes_found');
        }
    }

    /**
     * Function (for ajax) to display form to send email to candidate
     *
     * @return void
     */
    public function messageView()
    {
        echo view('employer.candidates.message')->render();
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
            $candidate = objToArr(Candidate::getCandidate('candidate_id', decode($candidate_id)));
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
        try {
            ini_set('max_execution_time', '0');
            $ids = explode(',', $request->input('ids'));
            $resumes = '';
            foreach ($ids as $id) {
                $data['resume'] = objToArr(Candidate::getCompleteResumeJobBoard($id));
                if (isset($data['resume']['type'])) {
                    if ($data['resume']['type'] == 'detailed') {
                        $resumes .= view('employer.candidates.resume-pdf', $data)->render();
                    } else {
                        $resumes .= "<hr />";
                        $resumes .= 'Resume of "'.$data['resume']['first_name'].' '.$data['resume']['last_name'].' ('.$data['resume']['designation'].')" is static and can be downloaded separately';
                        $resumes .= "<br /><hr />";
                    }
                } else {
                    $resumes .= 'No Record';
                } 
            }  

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($resumes);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream('Resumes.pdf');
        } catch (\Exception $e) {
            die($e->getMessage());
        }
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
    }

    public function addOrRemove($candidate_id = null) {
        $data = DB::table('employer_candidates')->where(['employer_id' => employerId(), 'candidate_id' => decode($candidate_id)])->first();
        if(!empty($data)) {
            DB::table('employer_candidates')->where(['employer_id' => employerId(), 'candidate_id' => decode($candidate_id)])->delete();
        } else {
            DB::table('employer_candidates')->insert(['employer_id' => employerId(), 'candidate_id' => decode($candidate_id)]);
        }
        return true;
    }
}
