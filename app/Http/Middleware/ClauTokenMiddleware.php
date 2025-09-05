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
        // Check for token in multiple places with enhanced debug logging
        $token = null;
        
        // Check session first
        $sessionToken = session('clauToken');
        if ($sessionToken) {
            $token = $sessionToken;
            Log::debug('Token found in session', [
                'token_prefix' => substr($token, 0, 10) . '...',
                'session_id' => session()->getId()
            ]);
        }
        
        // If not in session, try cookies
        if (!$token) {
            $cookieToken = $request->cookie('clau_token') ?? $request->cookie('clau_token_secure');
            if ($cookieToken) {
                $token = $cookieToken;
                Log::debug('Token found in cookie', [
                    'token_prefix' => substr($token, 0, 10) . '...',
                    'session_id' => session()->getId()
                ]);
                
                // Restore to session for future use
                session(['clauToken' => $token]);
                $request->session()->put('clauToken', $token);
                $request->session()->save();
            }
        }
        
        // Try manual cookie parsing as fallback
        if (!$token) {
            $cookies = $request->headers->get('cookie');
            if ($cookies) {
                $cookiesArray = explode(';', $cookies);
                foreach ($cookiesArray as $cookie) {
                    $parts = explode('=', trim($cookie));
                    if (count($parts) === 2) {
                        if ($parts[0] === 'clau_token' || $parts[0] === 'clau_token_secure') {
                            $token = urldecode($parts[1]);
                            Log::debug('Token found in raw cookie', [
                                'token_prefix' => substr($token, 0, 10) . '...',
                                'cookie_name' => $parts[0]
                            ]);
                            
                            // Restore to session for future use
                            session(['clauToken' => $token]);
                            $request->session()->put('clauToken', $token);
                            $request->session()->save();
                        }
                    }
                }
            }
        }
        
        // DEBUG: Check for token in debug file (this is temporary for debugging)
        if (!$token && file_exists(storage_path('logs/debug_token.txt'))) {
            $token = file_get_contents(storage_path('logs/debug_token.txt'));
            Log::debug('DEBUG: Using backup token from file', [
                'token_prefix' => substr($token, 0, 10) . '...'
            ]);
            
            // Restore to session for future use
            session(['clauToken' => $token]);
            $request->session()->put('clauToken', $token);
            $request->session()->save();
        }

        Log::debug('Auth check in middleware', [
            'has_token' => !empty($token),
            'token_prefix' => $token ? substr($token, 0, 10) . '...' : 'none',
            'session_id' => session()->getId(),
            'route' => $request->route()->getName() ?? 'unnamed',
            'cookie_clau_token' => $request->cookie('clau_token') ? 'present' : 'missing',
            'cookie_secure' => $request->cookie('clau_token_secure') ? 'present' : 'missing',
            'session_token' => session('clauToken') ? 'present' : 'missing'
        ]);
        
        if (!$token) {
            // If we're already on the login page, don't redirect again
            if ($request->route()->getName() === 'auth.login') {
                return $next($request);
            }
            
            Log::warning('No token found, redirecting to login');
            return redirect(route('auth.login'));
        }
        
        // Add token to the request for use in controllers
        $request->attributes->add(['clauToken' => $token]);
        
        return $next($request);
    }
}