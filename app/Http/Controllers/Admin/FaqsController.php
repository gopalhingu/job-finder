<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\Faqs;
use App\Models\Admin\FaqsCategory;
use App\Rules\MinString;
use App\Rules\MaxString;

use SimpleExcel\SimpleExcel;

class FaqsController extends Controller
{
    /**
     * View Function to display faqs list view page
     *
     * @return html/string
     */
    public function faqsListView()
    {
        $data['page'] = __('message.faqs');
        $data['menu'] = 'faqs';
        return view('admin.faqs.list', $data);
    }

    /**
     * Function to get data for faqs jquery datatable
     *
     * @return json
     */
    public function faqsList(Request $request)
    {
        echo json_encode(Faqs::faqsList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $faqs_id
     * @return html/string
     */
    public function createOrEdit($faqs_id = NULL)
    {
        $data['page'] = __('message.faqs');
        $data['menu'] = 'faqs';
        $data['faqs'] = objToArr(Faqs::getFaqs('faqs_id', $faqs_id));
        $data['categories'] = objToArr(FaqsCategory::getAll());
        return view('admin.faqs.create-or-edit', $data);        
    }

    /**
     * Function (for ajax) to process faqs create or edit form request
     *
     * @return redirect
     */
    public function saveFaqs(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('faqs_id') ? $request->input('faqs_id') : false;

        $rules['question'] = ['required', new MinString(2), new MaxString(5000)];
        $rules['answer'] = ['required', new MinString(2), new MaxString(5000)];

        $validator = Validator::make($request->all(), $rules, [
            'question.required' => __('validation.required'),
            'question.min' => __('validation.min_string'),
            'question.max' => __('validation.max_string'),
            'answer.required' => __('validation.required'),
            'answer.min' => __('validation.min_string'),
            'answer.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        $data = $request->all();
        Faqs::storeFaqs($data, $edit);
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array(
                'success' => __('message.faqs').' ' . ($edit ? __('message.updated') : __('message.created'))
        )))));
    }

    /**
     * Function (for ajax) to process faqs change status request
     *
     * @param integer $faqs_id
     * @param string $status
     * @return void
     */
    public function changeStatus($faqs_id = null, $status = null)
    {
        $this->checkIfDemo();
        Faqs::changeStatus($faqs_id, $status);
    }

    /**
     * Function (for ajax) to process faqs bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        Faqs::bulkAction($request->input('data'));
    }

    /**
     * Function (for ajax) to process faqs delete request
     *
     * @param integer $faqs_id
     * @return void
     */
    public function delete($faqs_id)
    {
        $this->checkIfDemo();
        Faqs::remove($faqs_id);
    }

    /**
     * Post Function to download faqs data in excel
     *
     * @return void
     */
    public function faqsExcel(Request $request)
    {
        $data = Faqs::getFaqsForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');                    
        $excel->writer->setData($data);
        $excel->writer->saveFile('faqs'); 
        exit;
    }
}