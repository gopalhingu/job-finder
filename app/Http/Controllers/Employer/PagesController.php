<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Employer\Department;
use App\Models\Employer\Blog;

class PagesController extends Controller
{
    /**
     * View Function to display home page
     *
     * @return html/string
     */
    public function index()
    {
        $default = setting('default_landing_page');
        if ($default == 'home') {
            $data['page'] = setting('site_name');
            $data['departments'] = Department::getAll();
            $data['blogs'] = Blog::getPostsHome();
            return view('front.home', $data);
        } else if ($default == 'jobs') {
            return redirect('jobs');
        } else if ($default == 'news') {
            return redirect('blogs');
        }
    }   

    /**
     * Function to save session variable for sidebar goggle
     *
     * @return void
     */
    public function sidebarToggle()
    {
        $currentValue = getSessionValues('sidebar_toggle');
        $currentValue = $currentValue == 'off' ? 'on' : 'off';
        setSession('sidebar_toggle', $currentValue);
    }
    
    /**
     * Function to display default 404 page on 404 error
     *
     * @return void
     */
    public function notFoundPage()
    {   
        $data['page'] = setting('site_name').' | page not found';
        return view('front.404', $data);
    }

}
