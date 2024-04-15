<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ToDo  extends Model
{
    protected $table = 'to_dos';
    protected static $tbl = 'to_dos';
    protected $primaryKey = 'to_do_id';

    public static function getToDo($column, $value)
    {
        $value = $column == 'to_do_id' || $column == 'to_dos.to_do_id' ? decode($value) : $value;
        $result = Self::where($column, $value)->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function getTodos($data)
    {
        //Setting session for every parameter of the request
        setSessionValues($data);

        //First getting total records
        $total = Self::getTotalTodos();
        
        //Setting filters, search and pagination via posted session variables
        $page = getSessionValues('dashboard_todos_page', 1);
        $per_page = 5;

        $per_page = $per_page < $total ? $per_page : $total;
        $limit = $per_page;
        $offset = ($page == 1 ? 0 : ($page-1)) * $per_page;
        $offset = $offset < 0 ? 0 : $offset;

        $query = Self::whereNotNull('to_dos.to_do_id');
        $query->select('to_dos.*');
        $query->where('to_dos.employer_id', employerSession());
        $query->from('to_dos');
        $query->orderBy('to_dos.created_at', 'DESC');
        $query->skip($offset);
        $query->take($limit);
        $result = $query->get();
        $records = $result ? $result->toArray() : array();

        //Making pagination for display
        $total_pages = $total != 0 ? ceil($total/$per_page) : 0;
        $pagination = ($offset == 0 ? 1 : ($offset+1));
        $pagination .= ' - ';
        $pagination .= $total_pages == $page ? $total : ($limit*$page);
        $pagination .= ' of ';
        $pagination .= $total;

        //Returning final results
        return array(
            'records' => $records,
            'total' =>  $total,
            'total_pages' => $total_pages,
            'pagination' => $pagination
        );
    }

    public static function getTotalTodos()
    {
        return Self::where('to_dos.employer_id', employerSession())->count();
    }

    public static function todoStatus($id, $status)
    {
        Self::where('to_dos.to_do_id', decode($id))->update(array('status' => $status));
    } 

    public static function store($data)
    {
        $data['employer_id'] = employerSession();
        $to_do_id = decode($data['to_do_id']);
        unset($data['_token'], $data['to_do_id']);

        if ($to_do_id) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('to_do_id', $to_do_id)->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::insert($data);
        }
    }

    public static function remove($to_do_id)
    {
        Self::where(array('to_do_id' => decode($to_do_id)))->delete();
    }
}