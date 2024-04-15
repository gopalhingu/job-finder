<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Message extends Model
{
    protected $table = 'messages';
    protected static $tbl = 'messages';
    protected $primaryKey = 'message_id';

    protected $fillable = [
        'message_id',
        'name',
        'email',
        'subject',
        'description',
        'created_at',
        'updated_at',
    ];

    public static function getMessage($column, $value)
    {
    	$message = Self::where($column, $value)->first();
    	return $message ? $message : emptyTableColumns(Self::$tbl);
    }

    public static function storeMessage($data)
    {
        $data['created_at'] = date('Y-m-d G:i:s');
        $data['description'] = $data['message'];
        unset($data['_token'], $data['message'], $data['csrf_token']);
        Self::insert($data);
    }    

    public static function remove($message_id)
    {
        Self::where(array('message_id' => $message_id))->delete();
    }

    public static function getAll($active = true)
    {
        $query = Self::whereNotNull('messages.message_id');
        if ($active) {
            $query->where('status', 1);
        }
        $query->from(Self::$tbl);
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function messagesList($request)
    {
        $columns = array(
            '',
            'messages.name',
            'messages.email',
            'messages.subject',
            'messages.description',
            'messages.created_at',
            'messages.status',
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('messages.message_id');
        $query->from('messages');
        $query->select(
            'messages.*',
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('description', 'like', '%'.$srh.'%');
            });
        }
        $query->groupBy('messages.message_id');
        $query->orderBy($orderColumn, $orderDirection);
        $query->skip($offset);
        $query->take($limit);
        $result = $query->get();
        $result = $result ? $result->toArray() : array();
        $result = array(
            'data' => Self::prepareDataForTable($result),
            'recordsTotal' => Self::getTotal(),
            'recordsFiltered' => Self::getTotal($srh, $request),
        );

        return $result;
    }

    public static function getTotal($srh = false, $request = '')
    {
        $query = Self::whereNotNull('messages.message_id');
        $query->from('messages');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('messages.description', 'like', '%'.$srh.'%');
            });
        }
        $query->groupBy('messages.message_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($messages)
    {
        $sorted = array();
        foreach ($messages as $u) {
            $actions = '';
            $u = objToArr($u);
            if (allowedTo('delete_message')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-message" data-id="'.$u['message_id'].'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$u['message_id']."' />",
                esc_output($u['name']),
                esc_output($u['email']),
                esc_output($u['subject']),
                esc_output($u['description']),
                date('d M, Y', strtotime($u['created_at'])),
                $actions
            );
        }
        return $sorted;
    }

    public static function getMessagesForCSV($ids)
    {
        $query = Self::whereNotNull('messages.message_id');
        $query->from('messages');
        $query->select(
            'messages.*'
        );
        $query->whereIn('messages.message_id', explode(',', $ids));
        $query->groupBy('messages.message_id');
        $query->orderBy('messages.created_at', 'DESC');
        return $query->get();
    }    
}