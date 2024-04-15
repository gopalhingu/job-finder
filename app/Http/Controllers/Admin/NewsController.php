<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\News;
use App\Models\Admin\NewsCategory;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

use SimpleExcel\SimpleExcel;

class NewsController extends Controller
{
    /**
     * View Function to display news list view page
     *
     * @return html/string
     */
    public function newsListView()
    {
        $data['page'] = __('message.news');
        $data['menu'] = 'news';
        return view('admin.news.list', $data);
    }

    /**
     * Function to get data for news jquery datatable
     *
     * @return json
     */
    public function newsList(Request $request)
    {
        echo json_encode(News::newsList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $news_id
     * @return html/string
     */
    public function createOrEdit($news_id = NULL)
    {
        $data['page'] = __('message.news');
        $data['menu'] = 'news';
        $data['news'] = objToArr(News::getNews('news_id', $news_id));
        $data['categories'] = objToArr(NewsCategory::getAll());
        return view('admin.news.create-or-edit', $data);        
    }

    /**
     * Function (for ajax) to process news create or edit form request
     *
     * @return redirect
     */
    public function saveNews(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('news_id') ? $request->input('news_id') : false;

        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $rules['summary'] = ['required', new MinString(10), new MaxString(255)];
        $rules['description'] = [new MaxString(5000)];

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
            $package = News::getNews('news.news_id', $edit);
            $this->deleteOldFile($package['image']);
        }

        $data = $request->all();
        $data['description'] = sanitizeHtmlTemplates(templateInput('description'));
        News::storeNews($data, $edit, issetVal($fileUpload, 'message'));
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array(
                'success' => __('message.news').' ' . ($edit ? __('message.updated') : __('message.created'))
        )))));
    }

    /**
     * Function (for ajax) to process news change status request
     *
     * @param integer $news_id
     * @param string $status
     * @return void
     */
    public function changeStatus($news_id = null, $status = null)
    {
        $this->checkIfDemo();
        News::changeStatus($news_id, $status);
    }

    /**
     * Function (for ajax) to process news bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        News::bulkAction($request->input('data'));
    }

    /**
     * Function (for ajax) to process news delete request
     *
     * @param integer $news_id
     * @return void
     */
    public function delete($news_id)
    {
        $this->checkIfDemo();
        News::remove($news_id);
    }

    /**
     * Post Function to download news data in excel
     *
     * @return void
     */
    public function newsExcel(Request $request)
    {
        $data = News::getNewsForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');                    
        $excel->writer->setData($data);
        $excel->writer->saveFile('news'); 
        exit;
    }
}