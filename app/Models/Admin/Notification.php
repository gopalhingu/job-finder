<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    protected $table = 'notifications';
    protected static $tbl = 'notifications';
    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'notification_id',
        'title',
        'description',
        'is_read',
        'created_at',
    ];  

    public static function getAll($limit = '')
    {   
        //Marking all as read
        Self::whereNotNull('notifications.notification_id')->update(array('is_read' => 1));

        $query = Self::whereNotNull('notifications.notification_id');
        $query->orderBy('notification_id', 'DESC');
        $query = $query->paginate($limit);
        $results = $query->toArray();
        $results = $results ? $results['data'] : array();
        return array(
            'results' => $results,
            'pagination' => $query->links('admin.partials.pagination')
        );
    }

    public static function forWidget()
    {
        $query = Self::whereNotNull('notifications.notification_id');
        $query->where('notifications.is_read', '0');
        $query->orderBy('notification_id', 'DESC');
        $query->skip(0);
        $query->take(10);
        $results = $query->get();
        return array(
            'results' => $results ? objToArr($results->toArray()) : array(),
            'count' => count($results),
        );
    }

    public static function do($type, $title, $desc = '')
    {
        if ($type == 'message') {
            $desc = '<a href="'.route('admin-messages').'">'.__('message.view_message').'</a>';
        } elseif ($type == 'candidate_signup') {
            $desc = '<a href="'.route('admin-candidates').'">'.__('message.candidates').'</a>';
        } elseif ($type == 'employer_signup') {
            $desc = '<a href="'.route('admin-employers').'">'.__('message.employers').'</a>';
        } elseif ($type == 'membership') {
            $desc = '<a href="'.route('admin-memberships').'">'.__('message.memberships').'</a>';
        } elseif ($type == 'candidate_membership') {
            $desc = '<a href="'.route('admin-candidate-memberships').'">'.__('message.candidate').' '.__('message.memberships').'</a>';
        }
        $notification = array(
            'title' =>  $title, 
            'description' => $desc, 
            'type' => $type,
            'created_at' => date('Y-m-d G:i:s'),
        );
        Notification::insert($notification);
    }
}