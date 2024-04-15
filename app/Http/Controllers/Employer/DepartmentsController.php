<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Department;
use App\Rules\MinString;
use App\Rules\MaxString;
use App\Rules\MaxFile;

class DepartmentsController extends Controller
{
    /**
     * View Function to display departments list view page
     *
     * @return html/string
     */
    public function listView()
    {
        if (setting('departments_creation') == 'only_admin') {
            abort(400);
        }
        $data['page'] = __('message.departments');
        $data['menu'] = 'departments';
        return view('employer.departments.list', $data);
    }

    /**
     * Function to get data for departments jquery datatable
     *
     * @return json
     */
    public function data(Request $request)
    {
        echo json_encode(Department::departmentsList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $department_id
     * @return html/string
     */
    public function createOrEdit($department_id = NULL)
    {
        $department = objToArr(Department::getDepartment('department_id', $department_id));
        echo view('employer.departments.create-or-edit', compact('department'))->render();
    }

    /**
     * Function (for ajax) to process department create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('department_id') ? $request->input('department_id') : false;
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

        //Deleting old file if any
        if ($edit && $request->input('image')) {
            $oldFile = Department::getDepartment('departments.department_id', $edit);
            $this->deleteOldFile(employerPath().'/departments/'.$oldFile['image']);
        }

        //Uploading new file
        $fileUpload = $this->uploadPublicFile(
            $request, 'image', employerPath().'/departments/', 
            array('image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', new MaxFile(generalFileUploadLimit())]),
            array('image.image' => __('validation.image'))
        );

        //Saving to db
        $data = Department::storeDepartment($request->all(), $edit, issetVal($fileUpload, 'message'));
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.department') . ($edit ? __('message.updated') : __('message.created')))),
            'data' => $data
        ));
    }

    /**
     * Function (for ajax) to process department change status request
     *
     * @param integer $department_id
     * @param string $status
     * @return void
     */
    public function changeStatus($department_id = null, $status = null)
    {
        $this->checkIfDemo();
        Department::changeStatus($department_id, $status);
    }

    /**
     * Function (for ajax) to process department bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        Department::bulkAction($request->all());
    }

    /**
     * Function (for ajax) to process department delete request
     *
     * @param integer $department_id
     * @return void
     */
    public function delete($department_id)
    {
        $this->checkIfDemo();

        //Deleting associated image file
        $oldFile = Department::getDepartment('departments.department_id', $department_id);
        $this->deleteOldFile($oldFile['image']);

        //Deleting record if any
        Department::remove($department_id);
    }    
}
