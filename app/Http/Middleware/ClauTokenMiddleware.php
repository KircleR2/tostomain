<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ClauTokenMiddleware
{
    // Cookie name constant for consistency
    const COOKIE_NAME = 'clau_token';
    
    public function handle (Request $request, Closure $next)
    {
        try {
            // Check if session exists
            if (!$request->hasSession()) {
                try {
                    Log::warning('Dashboard access attempted without session');
                } catch (\Exception $e) {
                    // Silent fail if logging fails
                }
                return redirect(route('auth.login'))->with('error', 'Session not available');
            }
            
            // Get token from session
            $sessionToken = $request->session()->get('clauToken');
            
            // Check for backup cookie - direct access
            $allCookies = $request->cookies->all();
            $cookieToken = isset($allCookies[self::COOKIE_NAME]) ? $allCookies[self::COOKIE_NAME] : null;
            
            // Debug session and cookie state
            try {
                Log::debug('ClauTokenMiddleware check', [
                    'has_session' => $request->hasSession(),
                    'session_token_exists' => !empty($sessionToken),
                    'cookie_token_exists' => !empty($cookieToken),
                    'session_id' => $request->session()->getId(),
                    'cookie_name' => self::COOKIE_NAME,
                    'all_cookies' => array_keys($allCookies)
                ]);
            } catch (\Exception $e) {
                // Silent fail if logging fails
            }
            
            // If token exists in cookie but not in session, restore it to session
            if (!$sessionToken && $cookieToken) {
                try {
                    Log::info('Restoring token from cookie to session', [
                        'cookie_token_length' => strlen($cookieToken),
                        'session_id' => $request->session()->getId()
                    ]);
                } catch (\Exception $e) {
                    // Silent fail if logging fails
                }
                
                $request->session()->put('clauToken', $cookieToken);
                $request->session()->save();
                $sessionToken = $cookieToken;
            }
            
            if (!$sessionToken) {
                try {
                    Log::warning('Dashboard access attempted without token');
                } catch (\Exception $e) {
                    // Silent fail if logging fails
                }
                return redirect(route('auth.login'))->with('error', 'Please login to continue');
            }
            
            // Set the cookie again to ensure it's available
            $response = $next($request);
            
            // Only set cookie if it doesn't exist or is different from session token
            if (!$cookieToken || $cookieToken !== $sessionToken) {
                try {
                    Log::info('Setting cookie from session token', [
                        'session_token_length' => strlen($sessionToken),
                        'session_id' => $request->session()->getId(),
                        'cookie_name' => self::COOKIE_NAME
                    ]);
                } catch (\Exception $e) {
                    // Silent fail if logging fails
                }
                
                $domain = parse_url(config('app.url'), PHP_URL_HOST);
                
                // If domain starts with www, make cookie available to subdomains
                if (strpos($domain, 'www.') === 0) {
                    $domain = substr($domain, 4); // Remove www.
                }
                
                // For localhost or IP testing
                if ($domain === 'localhost' || filter_var($domain, FILTER_VALIDATE_IP)) {
                    $domain = null;
                }
                
                $response->withCookie(cookie(
                    self::COOKIE_NAME,   // name
                    $sessionToken,       // value
                    120,                 // minutes (2 hours)
                    '/',                 // path
                    $domain,             // domain
                    null,                // secure (null = auto-detect)
                    false,               // httpOnly (false to allow JS access)
                    false,               // raw - set to false to avoid encoding issues
                    'lax'                // sameSite
                ));
            }
            
            return $response;
        } catch (\Exception $e) {
            try {
                Log::error('Error in ClauTokenMiddleware', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            } catch (\Exception $logException) {
                // Silent fail if logging fails
            }
            
            return redirect(route('auth.login'))->with('error', 'An error occurred. Please try again.');
        }
    }
}
