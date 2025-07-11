<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
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
        // Check if the request is for a static file
        $path = $request->path();
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        $staticExtensions = ['json', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'woff', 'woff2', 'ttf', 'eot'];
        
        if (in_array(strtolower($extension), $staticExtensions)) {
            $response = $next($request);
            
            // Add CORS headers
            $response->headers->set('Access-Control-Allow-Origin', 'https://www.tostocoffee.com');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
            
            return $response;
        }
        
        return $next($request);
    }
} 