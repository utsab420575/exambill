<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if ($request->user() === null) {
            return redirect('home')->withErrors('You are not allowed to access this page.');
        }

        if ($request->user()->hasAnyRole($roles)) {
            return $next($request);
        }

        return redirect()->back()->withErrors('You are not allowed to access this page.');
    }
}
