<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiCheckMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //key: OE3KFIE649MRECGQ
        $validHash = '4d5a1156d586aacfc2770dde2880196f376c798b3406f97a275a5483a7647c59';
        $authKey = $request->authKey;
        // DEBUGGING PURPOSE
        //return response($authKey); // This will return whatever was passed as auth_key

        if (hash('sha256', $authKey) === $validHash) {
            return $next($request);
        } else {
            return response("You are not allowed to access this page.", 401);
        }
    }
}
