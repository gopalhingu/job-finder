<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Candidate\Department;
use App\Models\Candidate\Blog;

class PagesController extends Controller
{
    /**
     * View Function to display home page
     *
     * @return html/string
     */
    public function index()
    {
        $default = settingEmpSlug('default_landing_page');
        if ($default == 'home' || $default == '') {
            $data['page'] = settingEmpSlug('site_name');
            $data['departments'] = Department::getAll();
            $data['blogs'] = Blog::getPostsHome();
            return view('candidate'.viewPrfx().'home', $data);
        } else if ($default == 'jobs') {
            return redirect(routeWithSlug('candidate-jobs'));
        } else if ($default == 'news') {
            return redirect(routeWithSlug('candidate-blogs'));
        }
    }

    /**
     * View Function to display blog listing page
     *
     * @return html/string
     */
    public function blogListing(Request $request)
    {
        $search = urldecode($request->get('search'));
        $categories = $request->get('categories');
        $categories = $categories ? implode(',', decodeArray(explode(',', $categories))) : array();

        $blogs = Blog::getAll($search, $categories);
        $data['blogs'] = $blogs['results'];
        $data['pagination'] = $blogs['pagination'];
        $data['categories'] = Blog::getCategories();
        $data['search'] = $search;
        $data['categoriesSel'] = $categories;
        $data['page'] = __('message.blogs').' | ' . settingEmpSlug('site_name');
        return view('candidate'.viewPrfx().'blog-listing', $data);
    }

    /**
     * View Function to display blog listing page
     *
     * @return html/string
     */
    public function blogDetail(Request $request, $slug = null, $blog_id = null)
    {
        $search = urldecode($request->get('search'));
        $categories = $request->get('categories');
        $categories = $categories ? implode(',', decodeArray(explode(',', $categories))) : array();

        $data['blog'] = Blog::getBlog('blogs.blog_id', decode($blog_id));
        $data['image'] = route('uploads-view', $data['blog']['image']);
        $data['categories'] = Blog::getCategories();
        $data['search'] = $search;
        $data['categoriesSel'] = $categories;
        $data['page'] = $data['blog']['title'].' | ' . settingEmpSlug('site_name');
        return view('candidate'.viewPrfx().'blog-detail', $data);
    }
}
