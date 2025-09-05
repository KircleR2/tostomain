<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class ClauTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check for token in multiple places for redundancy
        $token = session('clauToken');
        
        // If not in session, try the cookie
        if (!$token) {
            $token = $request->cookie('clau_token') ?? $request->cookie('clau_token_secure');
            
            // If found in cookie but not in session, restore it to session for future use
            if ($token) {
                session(['clauToken' => $token]);
                $request->session()->put('clauToken', $token);
                $request->session()->save();
            }
        }

        Log::debug('Auth check', [
            'has_token' => !empty($token),
            'token_prefix' => $token ? substr($token, 0, 10) . '...' : 'none',
            'session_id' => session()->getId(),
            'route' => $request->route()->getName() ?? 'unnamed'
        ]);
        
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