<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RedirectIdClauTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated and trying to access login/register pages
        if (Session::get('clauToken')) {
            // Prevent redirect loops by checking if we're already on the dashboard
            if ($request->route()->getName() !== 'back.dashboard') {
                return redirect(route('back.dashboard'));
            }
        }
        
        return $next($request);
    }
}
