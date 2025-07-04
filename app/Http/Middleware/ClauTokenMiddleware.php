<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClauTokenMiddleware
{
    public function handle (Request $request, Closure $next)
    {
        $token = Session::get('clauToken');
        if (!$token) {
            return redirect(route('auth.login'));
        }

        return $next($request);
    }
}
