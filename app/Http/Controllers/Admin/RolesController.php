<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\Role;

class RolesController extends Controller
{
    /**
     * Function (for ajax) to display roles list
     *
     * @return view
     */
    public function listView()
    {
        if (!allowedTo('roles_settings')) {
            die(__('message.not_allowed'));
        }
        
        $data['page'] = __('message.roles');
        $data['menu'] = 'roles';
        $data['roles'] = Role::getAll('all');
        return view('admin.roles.main', $data);
    }

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $role_id
     * @return html/string
     */
    public function createOrEdit($role_id = NULL)
    {
        $role = objToArr(Role::getRole('role_id', $role_id));
        echo view('admin.roles.create-or-edit', compact('role'))->render();
    }

    /**
     * Function (for ajax) to process role create or edit form request
     *
     * @return redirect
     */
    public function saveRole(Request $request)
    {
        $this->checkIfDemo();

        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ], [
            'title.required' => __('validation.required'),
        ]);

        if ($validator->fails()) {
            $errors =  $validator->messages()->toArray();
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }        

        if (Role::valueExist('title', $request->input('title'), $request->input('role_id'))) {
            echo json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('error' => __('message.role_already_exist')))
            ));
        } else {
            $data = Role::storeRole($request->all(), $request->input('role_id'));
            echo json_encode(array(
                'success' => 'true',
                'messages' => $this->ajaxErrorMessage(array('success' => __('message.role').' '.($request->input('role_id') ? __('message.updated') : __('message.created')))),
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
        $data['permissions'] = Role::getPermissions($role_id);
        echo view('admin.roles.permissions-dd', $data)->render();
    }

    /**
     * Function (for ajax) to add/remove permissions for a role
     *
     * @param integer $role_id
     * @param integer $permission_ids
     * @return void
     */
    public function updatePermissions(Request $request)
    {
        $this->checkIfDemo();
        Role::updatePermissions($request->input('data'));
    }

    /**
     * Function (for ajax) to display roles list in select2 multiselect
     *
     * @return view
     */
    public function rolesAsSelect2($type = 'admin')
    {
        $data['roles'] = Role::getAll($type);
        return view('admin.roles.bulk-assign', $data)->render();
    }

}
