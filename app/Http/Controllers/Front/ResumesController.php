<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Front\CandidateMembership;
use App\Models\Front\Resume;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

class ResumesController extends Controller
{
    /**
     * View Function to display account resume listing page
     *
     * @return html/string
     */
    public function listing($id = null)
    {
        if (setting('enable_multiple_resume') == 'yes') {
            $data['page_title'] = __('message.resume_listing');
            $data['menu'] = 'resumes';
            $data['resumes'] = Resume::getCandidateResumes(candidateSession());
            return view('front'.viewPrfx().'candidates.resume-listing', $data);            
        } else {
            $resume = Resume::getFirstDetailedResume();
            return redirect(route('front-acc-resume-view', encode($resume)));
        }        
    }    

    /**
     * Function (for ajax) to process create resume form request
     *
     * @return redirect
     */
    public function create(Request $request)
    {
        $this->checkIfDemo();

        //Checking if job applications are allowed (if candidate packages are enabled.)
        if (setting('enable_candidate_packages') == 'yes') {
            $resumes = Resume::candidateTotalResumes();
            $allowed_resumes = CandidateMembership::getActiveMembership('allowed_resumes');
            if ($resumes >= $allowed_resumes) {
                $message = __('message.allowed_resumes_msg').' ('.__('message.allowed_resumes').' : '.$allowed_resumes.', '.__('message.current').' '.__('message.resumes').' : '.$resumes.')';
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => $message))
                )));
            }
        }

        $validator = Validator::make($request->all(), [
            'title' => ['required', new MinString(2), new MaxString(50)],
            'designation' => ['required', new MinString(2), new MaxString(50)],
        ], [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'designation.required' => __('validation.required'),
            'designation.min' => __('validation.min_string'),
            'designation.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $result = Resume::createResume($request->all());
        echo json_encode(array(
            'success' => 'true',
            'id' => encode($result['resume_id']),
            'messages' => $this->ajaxErrorMessage(array('success' => 'success'))
        ));
    }

    /**
     * View Function to display account resume detail page
     *
     * @return html/string
     */
    public function detailView(Request $request, $id = null)
    {
        $id = setting('enable_multiple_resume') == 'yes' ? $id : encode(Resume::getFirstDetailedResume());
        $data['page_title'] = __('message.resume_detail');
        $data['menu'] = 'resumes';
        $data['resume'] = Resume::getCompleteResume(decode($id));
        if ($data['resume']['type'] == 'detailed') {
            return view('front'.viewPrfx().'candidates.edit-resume', $data);
        } else {
            return view('front'.viewPrfx().'candidates.edit-resume-doc', $data);
        }
    }

    /**
     * Function (for ajax) to process resume general section update form request
     *
     * @return redirect
     */
    public function updateGeneral(Request $request)
    {
        $this->checkIfDemo();

        $validator = Validator::make($request->all(), [
            'title' => ['required', new MinString(2), new MaxString(50)],
            'designation' => ['required', new MinString(2), new MaxString(50)],
            'objective' => ['required', new MinString(50), new MaxString(1000)],
        ], [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'designation.required' => __('validation.required'),
            'designation.min' => __('validation.min_string'),
            'designation.max' => __('validation.max_string'),
            'objective.required' => __('validation.required'),
            'objective.min' => __('validation.min_string'),
            'objective.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }


        //Uploading new
        $resume_id = decode($request->input('id'));
        $fileUpload = $this->uploadPublicFile(
            $request, 
            'file', 
            config('constants.upload_dirs.resumes'),
            array('file' => ['file', 'mimes:doc,docx,pdf', new MaxFile(generalFileUploadLimit())]),
            array('file.file' => __('validation.file'))
        );

        //Deleting existing file
        if (issetVal($fileUpload, 'success') == 'true') {
            $resume = objToArr(Resume::getFirst('resume_id', $resume_id));
            $this->deleteOldFile($resume['file']);
        } 

        Resume::updateResumeGeneral($request->all(), issetVal($fileUpload, 'message'));
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.general_section_updated')))
        ));
    }

    /**
     * Function (for ajax) to process resume experiences section update form request
     *
     * @return redirect
     */
    public function updateExperience(Request $request)
    {
        $this->checkIfDemo();

        if (!$request->input('resume_experience_id')) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.please_add_item_first')))
            )));
        }

        $validator = Validator::make($request->all(), [
            'title.*' => ['required', new MinString(2), new MaxString(50)],
            'from.*' => ['required', new MinString(2), new MaxString(20)],
            'to.*' => ['required', new MinString(2), new MaxString(20)],
            'company.*' => ['required', new MinString(2), new MaxString(50)],
            'description.*' => ['required', new MinString(50), new MaxString(5000)],
        ], [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'from.required' => __('validation.required'),
            'from.min' => __('validation.min_string'),
            'from.max' => __('validation.max_string'),
            'to.required' => __('validation.required'),
            'to.min' => __('validation.min_string'),
            'to.max' => __('validation.max_string'),
            'company.required' => __('validation.required'),
            'company.min' => __('validation.min_string'),
            'company.max' => __('validation.max_string'),
            'description.required' => __('validation.required'),
            'description.min' => __('validation.min_string'),
            'description.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            $messages = validateArrayMessage($validator->messages()->toArray());
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $messages))
            )));
        }        

        Resume::updateResumeExperience($request->all());
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.experiences_updated')))
        ));
    }

    /**
     * Function (for ajax) to process resume qualifications section update form request
     *
     * @return redirect
     */
    public function updateQualification(Request $request)
    {
        $this->checkIfDemo();

        if (!$request->input('resume_qualification_id')) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.please_add_item_first')))
            )));
        }

        $validator = Validator::make($request->all(), [
            'title.*' => ['required', new MinString(2), new MaxString(50)],
            'from.*' => ['required', new MinString(2), new MaxString(20)],
            'to.*' => ['required', new MinString(2), new MaxString(20)],
            'marks.*' => ['required', new MinString(1), new MaxString(5), 'numeric'],
            'out_of.*' => ['required', new MinString(1), new MaxString(5), 'numeric'],
            'institution.*' => ['required', new MinString(2), new MaxString(50)],

        ], [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'from.required' => __('validation.required'),
            'from.min' => __('validation.min_string'),
            'from.max' => __('validation.max_string'),
            'to.required' => __('validation.required'),
            'to.min' => __('validation.min_string'),
            'to.max' => __('validation.max_string'),
            'marks.required' => __('validation.required'),
            'marks.min' => __('validation.min_string'),
            'marks.max' => __('validation.max_string'),
            'marks.max' => __('validation.numeric'),
            'out_of.required' => __('validation.required'),
            'out_of.min' => __('validation.min_string'),
            'out_of.max' => __('validation.max_string'),
            'out_of.max' => __('validation.numeric'),
            'institution.required' => __('validation.required'),
            'institution.min' => __('validation.min_string'),
            'institution.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            $messages = validateArrayMessage($validator->messages()->toArray());
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $messages))
            )));
        } 

        Resume::updateResumeQualification($request->all());
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.qualifications_updated')))
        ));
    }

    /**
     * Function (for ajax) to process resume language section update form request
     *
     * @return redirect
     */
    public function updateLanguage(Request $request)
    {
        $this->checkIfDemo();

        if (!$request->input('resume_language_id')) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.please_add_item_first')))
            )));
        }

        $validator = Validator::make($request->all(), [
            'title.*' => ['required', new MinString(2), new MaxString(50)],            
        ], [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),

        ]);
        if ($validator->fails()) {
            $messages = validateArrayMessage($validator->messages()->toArray());            
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $messages))
            )));
        } 

        Resume::updateResumeLanguage($request->all());
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.languages_updated')))
        ));
    }

    /**
     * Function (for ajax) to process resume skill section update form request
     *
     * @return redirect
     */
    public function updateSkill(Request $request)
    {
        $this->checkIfDemo();

        if (!$request->input('resume_skill_id')) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.please_add_item_first')))
            )));
        }

        $validator = Validator::make($request->all(), [
            'title.*' => ['required', new MinString(2), new MaxString(50)],            
        ], [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),

        ]);
        if ($validator->fails()) {
            $messages = validateArrayMessage($validator->messages()->toArray());            
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $messages))
            )));
        } 

        Resume::updateResumeSkill($request->all());
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.skills_updated')))
        ));
    }

    /**
     * Function (for ajax) to process resume achievement section update form request
     *
     * @return redirect
     */
    public function updateAchievement(Request $request)
    {
        $this->checkIfDemo();

        if (!$request->input('resume_achievement_id')) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.please_add_item_first')))
            )));
        }

        $validator = Validator::make($request->all(), [
            'title.*' => ['required', new MinString(2), new MaxString(50)],
            'date.*' => ['required', new MaxString(20)],
            'link.*' => [new MinString(2), new MaxString(250)],
            'description.*' => ['required', new MinString(50), new MaxString(5000)],
        ], [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'date.required' => __('validation.required'),
            'date.max' => __('validation.max_string'),
            'link.min' => __('validation.min_string'),
            'link.max' => __('validation.max_string'),
            'description.required' => __('validation.required'),
            'description.min' => __('validation.min_string'),
            'description.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            $messages = validateArrayMessage($validator->messages()->toArray());            
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $messages))
            )));
        } 

        Resume::updateResumeAchievement($request->all());
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.achievements_updated')))
        ));
    }

    /**
     * Function (for ajax) to process resume reference section update form request
     *
     * @return redirect
     */
    public function updateReference(Request $request)
    {
        $this->checkIfDemo();

        if (!$request->input('resume_reference_id')) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.please_add_item_first')))
            )));
        }

        $validator = Validator::make($request->all(), [
            'title.*' => ['required', new MinString(2), new MaxString(50)],
            'relation.*' => ['required', new MinString(2), new MaxString(20)],
            'email.*' => ['required', new MinString(2), new MaxString(50), 'email'],
            'company.*' => ['required', new MinString(2), new MaxString(100)],
            'phone.*' => ['required', new MinString(7), new MaxString(13)],
        ], [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'relation.required' => __('validation.required'),
            'relation.min' => __('validation.min_string'),
            'relation.max' => __('validation.max_string'),
            'email.required' => __('validation.required'),
            'email.min' => __('validation.min_string'),
            'email.max' => __('validation.max_string'),
            'email.email' => __('validation.email'),
            'company.required' => __('validation.required'),
            'company.min' => __('validation.min_string'),
            'company.max' => __('validation.max_string'),
            'phone.required' => __('validation.required'),
            'phone.digits_between' => __('validation.digits_between'),
        ]);
        if ($validator->fails()) {
            $messages = validateArrayMessage($validator->messages()->toArray());
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $messages))
            )));
        } 

        Resume::updateResumeReference($request->all());
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.references_updated')))
        ));
    }

    /**
     * Function (for ajax) to process resume section add request
     *
     * @param string $resume_id
     * @param string $type
     * @return void
     */
    public function addSection($resume_id, $type)
    {
        switch ($type) {
            case 'experience':
                $data['experience'] = emptyTableColumns('resume_experiences');
                $data['experience']['resume_id'] = decode($resume_id);
                $data['experience']['from'] = date('Y-m-d');
                $data['experience']['to'] = date('Y-m-d');
                echo view('front'.viewPrfx().'partials.account-edit-resume-experiences', $data)->render();
                break;
            case 'qualification':
                $data['qualification'] = emptyTableColumns('resume_qualifications');
                $data['qualification']['resume_id'] = decode($resume_id);
                $data['qualification']['from'] = date('Y-m-d');
                $data['qualification']['to'] = date('Y-m-d');
                echo view('front'.viewPrfx().'partials.account-edit-resume-qualifications', $data)->render();
                break;
            case 'language':
                $data['language'] = emptyTableColumns('resume_languages');
                $data['language']['resume_id'] = decode($resume_id);
                echo view('front'.viewPrfx().'partials.account-edit-resume-languages', $data)->render();
                break;
            case 'skill':
                $data['skill'] = emptyTableColumns('resume_skills');
                $data['skill']['resume_id'] = decode($resume_id);
                echo view('front'.viewPrfx().'partials.account-edit-resume-skills', $data)->render();
                break;                
            case 'achievement':
                $data['achievement'] = emptyTableColumns('resume_achievements');
                $data['achievement']['resume_id'] = decode($resume_id);
                $data['achievement']['date'] = date('Y-m-d');
                echo view('front'.viewPrfx().'partials.account-edit-resume-achievements', $data)->render();
                break;
            case 'reference':
                $data['reference'] = emptyTableColumns('resume_references');
                $data['reference']['resume_id'] = decode($resume_id);
                echo view('front'.viewPrfx().'partials.account-edit-resume-references', $data)->render();
                break;
            default:
                die('not_allowed');
                break;
        }
    }

    /**
     * Function (for ajax) to process resume section delete request
     *
     * @param integer $section_id
     * @param string $type
     * @return void
     */
    public function removeSection($section_id, $type)
    {
        $this->checkIfDemo();
        Resume::removeSection($section_id, $type);
    }

    /**
     * Function (for ajax) to process profile update form request
     *
     * @return redirect
     */
    public function updateDocResume(Request $request)
    {
        $this->checkIfDemo();

        $validator = Validator::make($request->all(), [
            'title' => ['required', new MinString(2), new MaxString(50)],
        ], [
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

        $resume_id = decode($request->input('resume_id'));

        //Uploading new
        $fileUpload = $this->uploadPublicFile(
            $request, 
            'file', 
            config('constants.upload_dirs.resumes'),
            array('file' => ['file', 'mimes:doc,docx,pdf', new MaxFile(generalFileUploadLimit())]),
            array('file.file' => __('validation.file'))
        );

        //Deleting existing file
        if (issetVal($fileUpload, 'success') == 'true') {
            $resume = objToArr(Resume::getFirst('resume_id', $resume_id));
            $this->deleteOldFile($resume['file']);
        }

        Resume::updateDocResume($request->all(), issetVal($fileUpload, 'message'));
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.resume_updated')))
        ));
    }
}

