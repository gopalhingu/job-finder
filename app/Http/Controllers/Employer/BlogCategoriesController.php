<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\BlogCategory;
use App\Rules\MinString;
use App\Rules\MaxString;

class BlogCategoriesController extends Controller
{
    /**
     * View Function to display Blog Categories list view page
     *
     * @return html/string
     */
    public function listView()
    {
        $data['page'] = __('message.blog_categories');
        $data['menu'] = 'blog_categories';
        return view('employer.blog-categories.list', $data);
    }

    /**
     * Function to get data for Blog Categories jquery datatable
     *
     * @return json
     */
    public function data(Request $request)
    {
        echo json_encode(BlogCategory::blogCategoriesList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $blog_category_id
     * @return html/string
     */
    public function createOrEdit($blog_category_id = NULL)
    {
        $blog_category = objToArr(BlogCategory::get('blog_category_id', $blog_category_id));
        echo view('employer.blog-categories/create-or-edit', compact('blog_category'))->render();
    }

    /**
     * Function (for ajax) to process Blog Category create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('blog_category_id') ? $request->input('blog_category_id') : false;
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

        if (BlogCategory::valueExist('title', $request->input('title'), $edit)) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.blog_category_already_exist')))
            ));
        } else {
            BlogCategory::store($request->all(), $edit);
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => __('message.blog_category') . ($edit ? __('message.updated') : __('message.created')))
            )));
        }
    }

    /**
     * Function (for ajax) to process Blog Category change status request
     *
     * @param integer $blog_category_id
     * @param string $status
     * @return void
     */
    public function changeStatus($blog_category_id = null, $status = null)
    {
        $this->checkIfDemo();
        BlogCategory::changeStatus($blog_category_id, $status);
    }

    /**
     * Function (for ajax) to process Blog Category bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        BlogCategory::bulkAction($request->all());
    }

    /**
     * Function (for ajax) to process Blog Category delete request
     *
     * @param integer $blog_category_id
     * @return void
     */
    public function delete($blog_category_id)
    {
        $this->checkIfDemo();
        BlogCategory::remove($blog_category_id);
    }
}
