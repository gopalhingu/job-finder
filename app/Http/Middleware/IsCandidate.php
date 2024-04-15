<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsCandidate
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
        if (candidateSession()) {
            return $next($request);
        }

        return redirect(routeWithSlug('candidate-login-view', array('slug' => empSlug())))
            ->withErrors(__('message.candidate_auth_error'));
    }
}
