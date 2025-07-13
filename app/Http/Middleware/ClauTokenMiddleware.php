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
                Log::error('No session available in middleware', [
                    'path' => $request->path(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'session_driver' => config('session.driver')
                ]);
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
                'session_token_length' => $sessionToken ? strlen($sessionToken) : 0,
                'cookie_token_exists' => !empty($cookieToken),
                'cookie_token_length' => $cookieToken ? strlen($cookieToken) : 0,
                'session_id' => $request->session()->getId(),
                'session_driver' => config('session.driver'),
                'request_path' => $request->path(),
                'request_method' => $request->method(),
                'user_agent' => $request->header('User-Agent'),
                'cookies' => array_keys($request->cookies->all()),
                'session_keys' => array_keys($request->session()->all())
            ]);
            
            // If token exists in cookie but not in session, restore it to session
            if (!$sessionToken && $cookieToken) {
                Log::info('Restored session token from cookie', [
                    'session_id' => $request->session()->getId(),
                    'cookie_token_length' => strlen($cookieToken)
                ]);
                
                $request->session()->put('clauToken', $cookieToken);
                $request->session()->save();
                $sessionToken = $cookieToken;
            }
            
            if (!$sessionToken) {
                Log::warning('No token found in session or cookie, redirecting to login', [
                    'session_id' => $request->session()->getId(),
                    'path' => $request->path(),
                    'session_keys' => array_keys($request->session()->all()),
                    'cookies' => array_keys($request->cookies->all())
                ]);
                return redirect(route('auth.login'))->with('error', 'Please login to continue');
            }
            
            return $next($request);
        } catch (\Exception $e) {
            Log::error('Exception in ClauTokenMiddleware: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'path' => $request->path(),
                'session_exists' => $request->hasSession()
            ]);
            
            return redirect(route('auth.login'))->with('error', 'An error occurred. Please try again.');
        }
    }
}
