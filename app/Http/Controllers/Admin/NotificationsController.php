<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\Notification;

use SimpleExcel\SimpleExcel;

class NotificationsController extends Controller
{
    /**
     * View Function to display notifications list view page
     *
     * @return html/string
     */
    public function listView(Request $request)
    {
        $notifications = Notification::getAll(25);
        $data['page'] = __('message.notifications');
        $data['menu'] = 'notifications';
        $data['notifications'] = $notifications['results'];
        $data['pagination'] = $notifications['pagination'];
        return view('admin.notifications.list', $data);
    }

    /**
     * View Function to display notifications list as widget
     *
     * @return html/string
     */
    public function listWidget()
    {
        $data['notifications'] = Notification::forWidget();
        return view('admin.notifications.widget', $data);
    }
}