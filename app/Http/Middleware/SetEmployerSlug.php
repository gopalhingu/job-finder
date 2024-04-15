<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetEmployerSlug
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $subslug = \Route::input('subdomain_slug');
        $slug = $subslug ? $subslug : \Request::segment(1);
        $slug_emp = getSession('slug_emp');
        $employer_ss_setting = issetVal($slug_emp, 'separate_site', 0);
        $admin_ses_setting = setting('enable_separate_employer_site');

        if ($admin_ses_setting == 'yes' || ($admin_ses_setting == 'only_for_employers_with_separate_site' && $employer_ss_setting == '1')) {
            //Do nothing
        } elseif ($admin_ses_setting == 'no' || (empty($employer_ss_setting) && !empty($slug_emp))) {
            return redirect(env('APP_URL'));
        }
        
        if ($slug !== issetVal($slug_emp, 'slug')) {
            $employer = \App\Models\Employer\Employer::getEmployerBySlug($slug);
            if (!$employer) {
                return redirect(env('APP_URL'));
            } else {
                setSession('slug_emp', $employer);
            }
        }
        
        return $next($request);
    }
}
