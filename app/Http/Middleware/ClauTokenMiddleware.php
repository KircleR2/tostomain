<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClauTokenMiddleware
{
    public function handle (Request $request, Closure $next)
    {
        try {
            // Check if session exists
            if (!$request->hasSession()) {
                return redirect(route('auth.login'))->with('error', 'Session not available');
            }
            
            // Get token from session
            $sessionToken = $request->session()->get('clauToken');
            
            // Check for backup cookie
            $cookieToken = $request->cookie('clau_token');
            
            // If token exists in cookie but not in session, restore it to session
            if (!$sessionToken && $cookieToken) {
                $request->session()->put('clauToken', $cookieToken);
                $request->session()->save();
                $sessionToken = $cookieToken;
            }
            
            if (!$sessionToken) {
                return redirect(route('auth.login'))->with('error', 'Please login to continue');
            }
            
            return $next($request);
        } catch (\Exception $e) {
            return redirect(route('auth.login'))->with('error', 'An error occurred. Please try again.');
        }
    }
}
