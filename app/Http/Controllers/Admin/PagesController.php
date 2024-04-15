<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\Page;
use App\Rules\MinString;
use App\Rules\MaxString;

use SimpleExcel\SimpleExcel;

class PagesController extends Controller
{
    /**
     * View Function to display pages list view page
     *
     * @return html/string
     */
    public function pagesListView()
    {
        $data['page'] = __('message.pages');
        $data['menu'] = 'pages';
        return view('admin.pages.list', $data);
    }

    /**
     * Function to get data for pages jquery datatable
     *
     * @return json
     */
    public function pagesList(Request $request)
    {
        echo json_encode(Page::pagesList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $page_id
     * @return html/string
     */
    public function createOrEdit($page_id = NULL)
    {
        $data['page'] = __('message.pages');
        $data['menu'] = 'pages';
        $data['record'] = objToArr(Page::getPage('page_id', $page_id));
        return view('admin.pages.create-or-edit', $data);
    }

    /**
     * Function (for ajax) to process page create or edit form request
     *
     * @return redirect
     */
    public function savePage(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('page_id') ? $request->input('page_id') : false;

        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $rules['summary'] = ['required', new MinString(10), new MaxString(255)];
        $rules['description'] = ['required', new MinString(10), new MaxString(50000)];

        $validator = Validator::make($request->all(), $rules, [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min_string'),
            'title.max' => __('validation.max_string'),
            'summary.required' => __('validation.required'),
            'summary.min' => __('validation.min_string'),
            'summary.max' => __('validation.max_string'),
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

        $data = $request->all();
        $data['description'] = sanitizeHtmlTemplates(templateInput('description'));
        Page::storePage($data, $edit);
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array(
                'success' => __('message.page').' ' . ($edit ? __('message.updated') : __('message.created'))
        )))));
    }

    /**
     * Function (for ajax) to process page change status request
     *
     * @param integer $page_id
     * @param string $status
     * @return void
     */
    public function changeStatus($page_id = null, $status = null)
    {
        $this->checkIfDemo();
        Page::changeStatus($page_id, $status);
    }

    /**
     * Function (for ajax) to process page bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        Page::bulkAction($request->input('data'));
    }

    /**
     * Function (for ajax) to process page delete request
     *
     * @param integer $page_id
     * @return void
     */
    public function delete($page_id)
    {
        $this->checkIfDemo();
        Page::remove($page_id);
    }

    /**
     * Post Function to download pages data in excel
     *
     * @return void
     */
    public function pagesExcel(Request $request)
    {
        $data = Page::getPagesForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');                    
        $excel->writer->setData($data);
        $excel->writer->saveFile('pages'); 
        exit;
    }
}