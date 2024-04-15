<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\Message;
use App\Rules\MinString;
use App\Rules\MaxString;

use SimpleExcel\SimpleExcel;

class MessagesController extends Controller
{
    /**
     * View Function to display messages list view page
     *
     * @return html/string
     */
    public function messagesListView()
    {
        $data['page'] = __('message.messages');
        $data['menu'] = 'messages';
        return view('admin.messages.list', $data);
    }

    /**
     * Function to get data for messages jquery datatable
     *
     * @return json
     */
    public function messagesList(Request $request)
    {
        echo json_encode(Message::messagesList($request->all()));
    }    

    /**
     * Function (for ajax) to process message delete request
     *
     * @param integer $message_id
     * @return void
     */
    public function delete($message_id)
    {
        $this->checkIfDemo();
        Message::remove($message_id);
    }

    /**
     * Function (for ajax) to display form to send email to message
     *
     * @return void
     */
    public function messageView()
    {
        echo view('admin.messages.message')->render();
    }

    /**
     * Function (for ajax) to send email to message
     *
     * @return void
     */
    public function message(Request $request)
    {
        $this->checkIfDemo();
        ini_set('max_execution_time', 5000);
        $data = $request->input();
        $ids = explode(',', $data['ids']);

        $rules['message'] = ['required', new MinString(10), new MaxString(10000)];
        $rules['subject'] = ['required', new MinString(1), new MaxString(100)];
        $validator = Validator::make($request->all(), $rules, [
            'message.required' => __('validation.required'),
            'message.min' => __('validation.min_string'),
            'message.max' => __('validation.max_string'),
            'subject.required' => __('validation.required'),
            'subject.min' => __('validation.min_string'),
            'subject.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        foreach ($ids as $message_id) {
            $message = objToArr(Message::getMessage('message_id', $message_id));
            $this->sendEmail(removeUselessLineBreaks($data['message']), $message['email'], $data['subject']);
        }

        echo json_encode(array(
            'success' => 'true',
            'messages' => ''
        ));
    }

    /**
     * Post Function to download messages data in excel
     *
     * @return void
     */
    public function messagesExcel(Request $request)
    {
        $data = Message::getMessagesForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');                    
        $excel->writer->setData($data);
        $excel->writer->saveFile('messages'); 
        exit;
    }
}