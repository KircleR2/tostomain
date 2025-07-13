<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
                return $next($request);
            }
            
            // Check if token already exists in session
            if ($request->session()->has('clauToken')) {
                return $next($request);
            }
            
            // Check if token exists in cookie
            $cookieToken = $request->cookie('clau_token');
            if (!empty($cookieToken)) {
                // Store token in session
                $request->session()->put('clauToken', $cookieToken);
                $request->session()->save();
            }
            
            return $next($request);
        } catch (\Exception $e) {
            // Silent fail and continue
            return $next($request);
        }
    }
}
