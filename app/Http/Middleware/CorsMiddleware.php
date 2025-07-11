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
            
            // Get the origin from the request
            $origin = $request->header('Origin');
            
            // List of allowed domains
            $allowedOrigins = [
                'https://www.tostocoffee.com',
                'https://tostocoffee.com',
                'https://tostomain-achxn.ondigitalocean.app',
                'http://localhost:8000',
                'http://localhost'
            ];
            
            // Check if the origin is allowed
            if (in_array($origin, $allowedOrigins)) {
                // Add CORS headers with the specific origin
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
            } else {
                // If no valid origin is found, use the primary domain
                $response->headers->set('Access-Control-Allow-Origin', 'https://www.tostocoffee.com');
                $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
            }
            
            return $response;
        }
        
        return $next($request);
    }
} 