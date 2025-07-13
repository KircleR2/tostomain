<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TransferClauTokenMiddleware
{
    // Cookie name constant for consistency
    const COOKIE_NAME = 'clau_token';
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if session exists
            if (!$request->hasSession()) {
                return $next($request);
            }
            
            // Check if token already exists in session
            $sessionToken = $request->session()->get('clauToken');
            $cookieToken = $request->cookie(self::COOKIE_NAME);
            
            // If token exists in cookie but not in session, transfer it to session
            if (!$sessionToken && $cookieToken) {
                try {
                    Log::info('Transferring token from cookie to session', [
                        'cookie_token_length' => strlen($cookieToken),
                        'session_id' => $request->session()->getId(),
                        'cookie_name' => self::COOKIE_NAME
                    ]);
                } catch (\Exception $e) {
                    // Silent fail if logging fails
                }
                
                // Store token in session
                $request->session()->put('clauToken', $cookieToken);
                $request->session()->save();
            }
            // If token exists in session but not in cookie, set the cookie
            else if ($sessionToken && !$cookieToken) {
                try {
                    Log::info('Transferring token from session to cookie', [
                        'session_token_length' => strlen($sessionToken),
                        'session_id' => $request->session()->getId(),
                        'cookie_name' => self::COOKIE_NAME
                    ]);
                } catch (\Exception $e) {
                    // Silent fail if logging fails
                }
                
                // Set cookie with appropriate settings for production
                $response = $next($request);
                $domain = parse_url(config('app.url'), PHP_URL_HOST);
                
                // If domain starts with www, make cookie available to subdomains
                if (strpos($domain, 'www.') === 0) {
                    $domain = substr($domain, 4); // Remove www.
                }
                
                // For localhost or IP testing
                if ($domain === 'localhost' || filter_var($domain, FILTER_VALIDATE_IP)) {
                    $domain = null;
                }
                
                return $response->withCookie(cookie(
                    self::COOKIE_NAME,   // name
                    $sessionToken,       // value
                    120,                 // minutes (2 hours)
                    '/',                 // path
                    $domain,             // domain
                    null,                // secure (null = auto-detect)
                    false,               // httpOnly (false to allow JS access)
                    true,                // raw
                    'lax'                // sameSite
                ));
            }
            
            return $next($request);
        } catch (\Exception $e) {
            try {
                Log::error('Error in TransferClauTokenMiddleware', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            } catch (\Exception $logException) {
                // Silent fail if logging fails
            }
            
            // Silent fail and continue
            return $next($request);
        }
    }
}
