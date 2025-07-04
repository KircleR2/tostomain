<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class RedirectIdClauTokenMiddleware
{
    public function handle (Request $request, Closure $next)
    {
        try {
            // Check for token in session
            $sessionToken = $request->session()->get('clauToken');
            
            // Check for backup cookie
            $cookieToken = $request->cookie('clau_token');
            
            // Log for debugging
            Log::debug('RedirectIfClauToken check', [
                'has_session' => $request->hasSession(),
                'session_token_exists' => !empty($sessionToken),
                'cookie_token_exists' => !empty($cookieToken),
                'session_id' => $request->session()->getId()
            ]);
            
            // If token exists in either session or cookie, redirect to dashboard
            if ($sessionToken || $cookieToken) {
                // If token only exists in cookie, store it in session too
                if (!$sessionToken && $cookieToken) {
                    $request->session()->put('clauToken', $cookieToken);
                    $request->session()->save();
                    Log::info('Restored session token from cookie');
                }
                
                return redirect(route('back.dashboard'));
            }
            
            return $next($request);
        } catch (\Exception $e) {
            Log::error('Exception in RedirectIdClauTokenMiddleware: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return $next($request);
        }
    }
}
