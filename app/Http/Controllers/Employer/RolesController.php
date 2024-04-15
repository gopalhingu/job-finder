<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Role;
use App\Rules\MinString;
use App\Rules\MaxString;

class RolesController extends Controller
{
    /**
     * Function (for ajax) to display roles list in right modal
     *
     * @return view
     */
    public function listView()
    {
        $data['roles'] = Role::getAll();
        $firstRoleId = isset($data['roles'][0]['role_id']) ? $data['roles'][0]['role_id'] : '';
        $data['permissions'] = Role::getPermissions($firstRoleId);
        echo view('employer.roles.list', $data)->render();
    }

    /**
     * Function (for ajax) to process role create or edit form request
     *
     * @return redirect
     */
    public function saveRole(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('role_id') ? $request->input('role_id') : false;
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

        if (Role::valueExist('title', $request->input('title'), $edit)) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.role_already_exist')))
            ));
        } else {
            $data = Role::storeRole($request->all(), $edit);
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => __('message.role').' ' . ($edit ? __('message.updated') : __('message.created')))),
                'data' => $data
            ));
        }
    }

    /**
     * Function (for ajax) to process role delete request
     *
     * @param integer $role_id
     * @return void
     */
    public function delete($role_id)
    {
        $this->checkIfDemo();
        Role::remove($role_id);
    }

    /**
     * Function (for ajax) to get permissions for a role
     *
     * @param integer $role_id
     * @return void
     */
    public function getRolePermissions($role_id)
    {
        $permissions = Role::getPermissions($role_id);
        echo view('employer.roles.permissions-select', compact('permissions'))->render();
    }

    /**
     * Function (for ajax) to add permission to a role
     *
     * @param integer $role_id
     * @param integer $permission_id
     * @return void
     */
    public function addPermission(Request $request)
    {
        $this->checkIfDemo();
        Role::addPermission($request->input('data'));
    }


    /**
     * Function (for ajax) to remove permission to a role
     *
     * @param integer $role_id
     * @param integer $permission_id
     * @return void
     */
    public function removePermission(Request $request)
    {
        $this->checkIfDemo();
        Role::removePermission($request->input('data'));
    }
}
