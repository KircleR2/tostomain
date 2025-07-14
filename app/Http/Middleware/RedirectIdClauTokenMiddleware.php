<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RedirectIdClauTokenMiddleware
{
    public function handle (Request $request, Closure $next)
    {
        if (Session::get('clauToken')) {
            return redirect(route('back.dashboard'));
        }
        return $next($request);
    }
}
