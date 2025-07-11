<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        
        // Log the request for debugging
        Log::debug('CORS middleware processing', [
            'path' => $request->path(),
            'origin' => $origin,
            'method' => $request->method(),
            'is_static' => $this->isStaticFile($request->path())
        ]);
        
        // Process the request and get the response
        $response = $next($request);
        
        // Check if the origin is allowed
        if (in_array($origin, $allowedOrigins)) {
            // Add CORS headers with the specific origin
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization, X-CSRF-TOKEN');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            
            // Log for debugging
            Log::debug('CORS headers set for allowed origin', ['origin' => $origin]);
        } else {
            // If no valid origin is found or it's a direct request, use the primary domain
            // Only set this for static files to avoid interfering with normal requests
            if ($this->isStaticFile($request->path())) {
                $response->headers->set('Access-Control-Allow-Origin', '*');
                $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
                
                // Log for debugging
                Log::debug('CORS headers set for static file with wildcard origin');
            }
        }
        
        return $response;
    }
    
    /**
     * Check if the request is for a static file
     *
     * @param string $path
     * @return bool
     */
    private function isStaticFile($path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $staticExtensions = ['json', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'woff', 'woff2', 'ttf', 'eot', 'css', 'js', 'map'];
        
        return in_array(strtolower($extension), $staticExtensions);
    }
}
