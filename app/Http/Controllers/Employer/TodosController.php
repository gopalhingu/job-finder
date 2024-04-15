<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\ToDo;
use App\Rules\MinString;
use App\Rules\MaxString;

class TodosController extends Controller
{
    /**
     * Function (via ajax) to get to do list for team members
     *
     * @return json
     */
    public function listView(Request $request)
    {
        $todosResults = ToDo::getTodos($request->all());
        $todos = $todosResults['records'];
        echo json_encode(array(
            'pagination' => $todosResults['pagination'],
            'total_pages' => $todosResults['total_pages'],
            'list' => view('employer.todos.item', compact('todos'))->render(),
        ));
    }

    /**
     * Function (via ajax) to view create or edit to do
     *
     * @param $to_do_id integer
     * @return void
     */
    public function createOrEditToDo($to_do_id = '')
    {
        $to_do = ToDo::getToDo('to_do_id', $to_do_id);
        echo view('employer.todos.create-or-edit', compact('to_do'))->render();
    }

    /**
     * Function (for ajax) to process question create or edit form request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $rules['title'] = ['required', new MinString(3), new MaxString(100)];
        $rules['description'] = ['required', new MinString(10), new MaxString(1000)];

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

        
        $edit = $request->input('to_do_id') ? $request->input('to_do_id') : false;

        ToDo::store($request->all());
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.to_do_item').' ' . ($edit ? __('message.updated') : __('message.created'))))
        ));
    }

    /**
     * Function (via ajax) to mark status of to do list item
     *
     * @param $id integer
     * @param $status integer
     * @return void
     */
    public function todoStatus($id, $status)
    {
        ToDo::todoStatus($id, $status);
    }

    /**
     * Function (for ajax) to process todo delete request
     *
     * @param integer $to_do_id
     * @return void
     */
    public function delete($to_do_id)
    {
        $this->checkIfDemo();
        ToDo::remove($to_do_id);
    }
}
