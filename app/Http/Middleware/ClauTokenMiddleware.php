<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClauTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = Session::get('clauToken');
        
        if (!$token) {
            // If we're already on the login page, don't redirect again
            if ($request->route()->getName() === 'auth.login') {
                return $next($request);
            }
            
            return redirect(route('auth.login'));
        }
        
        // Add token to the request for use in controllers
        $request->attributes->add(['clauToken' => $token]);
        
        return $next($request);
    }
}
