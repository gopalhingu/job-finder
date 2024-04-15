<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Testimonial extends Model
{
    protected $table = 'testimonials';
    protected static $tbl = 'testimonials';
    protected $primaryKey = 'testimonial_id';

    protected $fillable = [
        'employer_id',
        'testimonial_id',
        'description',
        'status',
        'created_at',
        'updated_at',
    ];

    public static function getEmployerTestimonial()
    {
    	$testimonial = Self::where('employer_id', employerId())->first();
        $testimonial = $testimonial ? array('testimonial' => $testimonial->description, 'rating' =>  $testimonial->rating) : array();
    	return $testimonial;
    }

    public static function storeTestimonial($description, $rating)
    {
        $existing = Self::getEmployerTestimonial();
        if ($existing) {
            $data['updated_at'] = date('Y-m-d G:i:s');
            $data['description'] = $description;
            $data['rating'] = $rating;
            Self::where('employer_id', employerId())->update($data);
        } else {
            $data['employer_id'] = employerId();
            $data['description'] = $description;
            $data['rating'] = $rating;
            $data['updated_at'] = date('Y-m-d G:i:s');
            $data['created_at'] = date('Y-m-d G:i:s');
            $data['status'] = 1;
            Self::insert($data);
        }
    }
}