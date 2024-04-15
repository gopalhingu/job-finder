<?php

namespace App\Models\Candidate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Blog extends Model
{
    protected $table = 'blogs';
    protected static $tbl = 'blogs';
    protected $primaryKey = 'blog_id';

    public static function getBlog($column, $value)
    {
        $query = Self::whereNotNull('blogs.blog_id');
        $query->where($column, $value);
        $query->select(
            'blogs.*', 
            'blog_categories.title as category'
        );        
        $query->leftJoin('blog_categories', function($join) {
            $join->on('blog_categories.blog_category_id', '=', 'blogs.blog_category_id');
        });                
        $result = $query->first();
        return $result ? $result->toArray() : emptyTableColumns(Self::$tbl);
    }

    public static function getPostsHome()
    {
        $query = Self::whereNotNull('blogs.blog_id');
        $query->select(
            'blogs.*', 
            'blog_categories.title as category'
        );        
        $query->where('blogs.employer_id', employerIdBySlug());
        $query->where('blogs.status', 1);
        $query->orderBy('blogs.created_at', 'DESC');
        $query->leftJoin('blog_categories', function($join) {
            $join->on('blog_categories.blog_category_id', '=', 'blogs.blog_category_id');
        });        
        $query->skip(0);
        $query->take(6);
        $query = $query->get();
        return $query ? objToArr($query->toArray()) : array();
    }

    public static function getAll($srh, $categories)
    {
        $limit = settingEmpSlug('blogs_per_page');
        $query = Self::whereNotNull('blogs.blog_id');
        $query->select(
            'blogs.*', 
            'blog_categories.title as category'
        );        
        if ($srh) {
            $query->where(function($q) use($srh) {
                $q->where('blogs.title', 'like', '%'.$srh.'%')
                ->orWhere('blogs.description', 'like', '%'.$srh.'%');
            });
        }
        if ($categories) {
            $query->whereIn('blogs.blog_category_id', Self::sortForSearch($categories));
        }
        $query->leftJoin('blog_categories', function($join) {
            $join->on('blog_categories.blog_category_id', '=', 'blogs.blog_category_id');
        });        
        $query->where('blogs.employer_id', employerIdBySlug());
        $query->where('blogs.status', 1);
        $query->orderBy('blogs.created_at', 'DESC');
        $query->groupBy('blogs.blog_id');
        $query = $query->paginate($limit);
        $results = $query->toArray();
        $results = $results ? objToArr($results['data']) : array();
        return array(
            'results' => $results,
            'pagination' => $query->links('candidate'.viewPrfx().'partials.pagination-blogs')
        );
    }

    public static function getCategories($active = true)
    {
        $query = DB::table('blog_categories')->whereNotNull('blog_categories.blog_category_id');
        if ($active) {
            $query->where('status', 1);
        }
        $query->where('blog_categories.employer_id', employerIdBySlug());
        $query = $query->get();
        return $query ? objToArr($query->toArray()) : array();
    }

    private static function sortForSearch($data)
    {
        $return = array();
        $array = explode(',', $data);
        foreach ($array as $value) {
            if ($value) {
                $return[] = decode($value);
            }
        }
        return $return;
    }
}