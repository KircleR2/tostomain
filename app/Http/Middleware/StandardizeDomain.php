<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StandardizeDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Choose your preferred domain (with or without www)
        $preferredDomain = 'www.tostocoffee.com'; // or 'www.tostocoffee.com'
        
        // Get the current host
        $host = $request->getHost();
        
        // If we're not on the preferred domain, redirect
        if ($host !== $preferredDomain && $host !== 'localhost' && !app()->environment('local')) {
            $scheme = $request->isSecure() ? 'https' : 'http';
            $path = $request->path() === '/' ? '' : '/'.$request->path();
            $query = $request->getQueryString() ? '?'.$request->getQueryString() : '';
            
            return redirect()->to($scheme.'://'.$preferredDomain.$path.$query, 301);
        }
        
        return $next($request);
    }
} 