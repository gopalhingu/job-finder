<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsCompany
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
        if (companySession()) {
            return $next($request);
        }

        return redirect(route('company-login'))->withErrors(__('message.company_auth_error'));
    }
}
