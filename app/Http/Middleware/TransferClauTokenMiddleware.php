<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TransferClauTokenMiddleware
{
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
                Log::error('No session available in TransferClauTokenMiddleware');
                return $next($request);
            }
            
            // Check if token already exists in session
            if ($request->session()->has('clauToken')) {
                Log::debug('Token already exists in session, no need to transfer from cookie');
                return $next($request);
            }
            
            // Check if token exists in cookie
            $cookieToken = $request->cookie('clau_token');
            if (!empty($cookieToken)) {
                Log::info('Transferring token from cookie to session', [
                    'cookie_token_length' => strlen($cookieToken),
                    'session_id' => $request->session()->getId()
                ]);
                
                // Store token in session
                $request->session()->put('clauToken', $cookieToken);
                $request->session()->save();
            }
            
            return $next($request);
        } catch (\Exception $e) {
            Log::error('Exception in TransferClauTokenMiddleware: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $next($request);
        }
    }
}
