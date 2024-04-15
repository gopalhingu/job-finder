<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Blog;
use App\Models\Employer\BlogCategory;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

class BlogsController extends Controller
{
    /**
     * View Function to display blogs list view page
     *
     * @return html/string
     */
    public function listView()
    {
        $data['page'] = __('message.blogs');
        $data['menu'] = 'blogs';
        return view('employer.blogs.listing', $data);
    }

    /**
     * Function to get data for blogs jquery datatable
     *
     * @return json
     */
    public function data(Request $request)
    {
        echo json_encode(Blog::blogsList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $blog_id
     * @return html/string
     */
    public function createOrEdit($blog_id = NULL)
    {
        $data['blog'] = objToArr(Blog::getBlog('blogs.blog_id', $blog_id));
        $data['categories'] = objToArr(BlogCategory::getAll());
        $data['page'] = __('message.blogs');
        $data['menu'] = 'blogs';
        return view('employer.blogs.create-or-edit', $data);        
    }

    /**
     * Function (for ajax) to process blog create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('blog_id') ? $request->input('blog_id') : false;
        $rules['title'] = ['required', new MinString(2), new MaxString(50)];
        $rules['description'] = ['required', new MinString(10), new MaxString(10000)];
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

        if (Blog::valueExist('title', $request->input('title'), $edit)) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.blog_already_exist')))
            )));
        };

        //Deleting old file if any
        if ($edit && $request->input('image')) {
            $oldFile = Blog::getBlog('blogs.blog_id', $edit);
            $this->deleteOldFile(employerPath().'/blogs/'.$oldFile['image']);
        }

        //Uploading new file
        $fileUpload = $this->uploadPublicFile(
            $request, 'image', employerPath().'/blogs/', 
            array('image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(generalFileUploadLimit())]),
            array('image.image' => __('validation.image'))
        );

        $data = $request->all();
        $data['description'] = sanitizeHtmlTemplates(templateInput('description'));
        $data = Blog::store($data, $edit, issetVal($fileUpload, 'message'));
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.blog') . ($edit ? __('message.updated') : __('message.created')))),
            'data' => $data
        ));
    }

    /**
     * Function (for ajax) to process blog change status request
     *
     * @param integer $blog_id
     * @param string $status
     * @return void
     */
    public function changeStatus($blog_id = null, $status = null)
    {
        $this->checkIfDemo();
        Blog::changeStatus($blog_id, $status);
    }

    /**
     * Function (for ajax) to process blog bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        Blog::bulkAction($request->all());
    }

    /**
     * Function (for ajax) to process blog delete request
     *
     * @param integer $blog_id
     * @return void
     */
    public function delete($blog_id)
    {
        $this->checkIfDemo();
        Blog::remove($blog_id);
    }    
}
