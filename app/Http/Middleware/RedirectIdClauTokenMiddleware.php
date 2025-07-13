<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RedirectIdClauTokenMiddleware
{
    public function handle (Request $request, Closure $next)
    {
        try {
            // Check for token in session
            $sessionToken = $request->session()->get('clauToken');
            
            // Check for backup cookie
            $cookieToken = $request->cookie('clau_token');
            
            // If token exists in either session or cookie, redirect to dashboard
            if ($sessionToken || $cookieToken) {
                // If token only exists in cookie, store it in session too
                if (!$sessionToken && $cookieToken) {
                    $request->session()->put('clauToken', $cookieToken);
                    $request->session()->save();
                }
                
                return redirect(route('back.dashboard'));
            }
            
            return $next($request);
        } catch (\Exception $e) {
            // Silent fail and continue
            return $next($request);
        }
    }
}
