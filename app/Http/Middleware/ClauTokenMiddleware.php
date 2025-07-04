<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ClauTokenMiddleware
{
    public function handle (Request $request, Closure $next)
    {
        try {
            // Check if session exists
            if (!$request->hasSession()) {
                Log::error('No session available in middleware');
                return redirect(route('auth.login'))->with('error', 'Session not available');
            }
            
            // Get token from session
            $sessionToken = $request->session()->get('clauToken');
            
            // Check for backup cookie
            $cookieToken = $request->cookie('clau_token');
            
            // Log for debugging
            Log::debug('Session check in middleware', [
                'has_session' => $request->hasSession(),
                'session_token_exists' => !empty($sessionToken),
                'cookie_token_exists' => !empty($cookieToken),
                'session_id' => $request->session()->getId(),
                'session_driver' => config('session.driver'),
                'request_path' => $request->path(),
                'request_method' => $request->method(),
                'user_agent' => $request->header('User-Agent')
            ]);
            
            // If token exists in cookie but not in session, restore it to session
            if (!$sessionToken && $cookieToken) {
                $request->session()->put('clauToken', $cookieToken);
                $request->session()->save();
                Log::info('Restored session token from cookie');
                $sessionToken = $cookieToken;
            }
            
            if (!$sessionToken) {
                Log::warning('No token found in session or cookie, redirecting to login');
                return redirect(route('auth.login'))->with('error', 'Please login to continue');
            }
            
            return $next($request);
        } catch (\Exception $e) {
            Log::error('Exception in ClauTokenMiddleware: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect(route('auth.login'))->with('error', 'An error occurred. Please try again.');
        }
    }
}
