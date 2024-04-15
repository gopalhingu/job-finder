<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Testimonial extends Model
{
    protected $table = 'testimonials';
    protected static $tbl = 'testimonials';
    protected $primaryKey = 'testimonial_id';

    protected $fillable = [
        'testimonial_id',
        'description',
        'status',
        'created_at',
        'updated_at',
    ];

    public static function getTestimonial($column, $value)
    {
    	$testimonial = Self::where($column, $value)->first();
    	return $testimonial ? $testimonial : emptyTableColumns(Self::$tbl);
    }

    public static function storeTestimonial($data, $edit = null)
    {
        unset($data['testimonial_id'], $data['_token']);
        if ($edit) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            Self::where('testimonial_id', $edit)->update($data);
        } else {
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['status'] = 1;
            Self::insert($data);
        }
    }

    public static function changeStatus($testimonial_id, $status)
    {
        Self::where('testimonial_id', $testimonial_id)->update(array('status' => ($status == 1 ? 0 : 1)));
    }

    public static function remove($testimonial_id)
    {
        Self::where(array('testimonial_id' => $testimonial_id))->delete();
    }

    public static function bulkAction($data)
    {
        $data = objToArr(json_decode($data));
        $action = $data['action'];
        $ids = $data['ids'];
        switch ($action) {
            case "activate":
                Self::whereIn('testimonial_id', $ids)->update(array('status' => '1'));
            break;
            case "deactivate":
                Self::whereIn('testimonial_id', $ids)->update(array('status' => '0'));
            break;
        }
    }

    public static function getAll($active = true)
    {
        $query = Self::whereNotNull('testimonials.testimonial_id');
        if ($active) {
            $query->where('status', 1);
        }
        $result = $query->get();
        return $result ? $result->toArray() : array();
    }

    public static function getForHome($status = true, $limit, $orderByCol, $orderByDir)
    {
        $query = Self::whereNotNull('testimonials.description');
        $query->select(
            'testimonials.*',
            'employers.company',
            'employers.logo',
            DB::Raw('CONCAT('.dbprfx().'employers.first_name, " ", '.dbprfx().'employers.last_name) as employer_name')
        );
        if ($status) {
            $query->where('testimonials.status', 1);
        }
        $query->skip(0);
        if ($orderByCol) {
            $query->skip(0);
            $query->take($limit);
        }
        $query->take($limit);
        $query->leftJoin('employers', 'testimonials.employer_id', '=', 'employers.employer_id');
        if ($orderByCol) {
            $query->orderBy($orderByCol, $orderByDir);
        }
        $result = $query->get();
        return $result ? objToArr($result->toArray()) : array();
    }

    public static function testimonialsList($request)
    {
        $columns = array(
            '',
            'testimonials.description',
            'testimonials.company',
            'testimonials.created_at',
            'testimonials.status',
        );
        $orderColumn = $columns[($request['order'][0]['column'] == 0 ? 5 : $request['order'][0]['column'])];
        $orderDirection = $request['order'][0]['dir'];
        $srh = $request['search']['value'];
        $limit = $request['length'];
        $offset = $request['start'];

        $query = Self::whereNotNull('testimonials.testimonial_id');
        $query->from('testimonials');
        $query->select(
            'testimonials.*',
            'employers.company'
        );
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('description', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('testimonials.status', $request['status']);
        }
        $query->leftJoin('employers', 'testimonials.employer_id', '=', 'employers.employer_id');
        $query->groupBy('testimonials.testimonial_id');
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
        $query = Self::whereNotNull('testimonials.testimonial_id');
        $query->from('testimonials');
        if ($srh) {
            $query->where(function($q) use ($srh) {
                $q->where('testimonials.description', 'like', '%'.$srh.'%');
            });
        }
        if (isset($request['status']) && $request['status'] != '') {
            $query->where('testimonials.status', $request['status']);
        }
        $query->leftJoin('employers', 'testimonials.employer_id', '=', 'employers.employer_id');
        $query->groupBy('testimonials.testimonial_id');
        return $query->get()->count();
    }

    private static function prepareDataForTable($testimonials)
    {
        $sorted = array();
        foreach ($testimonials as $u) {
            $actions = '';
            $u = objToArr($u);
            $id = $u['testimonial_id'];
            if ($u['status'] == 1) {
                $button_text = __('message.active');
                $button_class = 'success';
                $button_title = __('message.click_to_deactivate');
            } else {
                $button_text = __('message.inactive');
                $button_class = 'danger';
                $button_title = __('message.click_to_activate');
            }
            if (allowedTo('edit_testimonial')) { 
            $actions .= '
                <button type="button" class="btn btn-primary btn-xs create-or-edit-testimonial" data-id="'.$id.'"><i class="far fa-edit"></i></button>
            ';
            }
            if (allowedTo('delete_testimonial')) { 
            $actions .= '
                <button type="button" class="btn btn-danger btn-xs delete-testimonial" data-id="'.$id.'"><i class="far fa-trash-alt"></i></button>
            ';
            }
            $sorted[] = array(
                "<input type='checkbox' class='minimal single-check' data-id='".$id."' />",
                esc_output($u['description']),
                esc_output($u['company']),
                date('d M, Y', strtotime($u['created_at'])),
                '<button type="button" title="'.$button_title.'" class="btn btn-'.$button_class.' btn-xs change-testimonial-status" data-status="'.$u['status'].'" data-id="'.$id.'">'.$button_text.'</button>',
                $actions
            );
        }
        return $sorted;
    }

    public static function getTestimonialsForCSV($ids)
    {
        $query = Self::whereNotNull('testimonials.testimonial_id');
        $query->from('testimonials');
        $query->select(
            'testimonials.*'
        );
        $query->whereIn('testimonials.testimonial_id', explode(',', $ids));
        $query->groupBy('testimonials.testimonial_id');
        $query->orderBy('testimonials.created_at', 'DESC');
        return $query->get();
    }    
}