<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
        'sanctum/csrf-cookie',
        'webhook/*'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        // Log CSRF token information for debugging
        if ($request->method() !== 'GET' && $request->method() !== 'HEAD') {
            $hasToken = $request->hasHeader('X-CSRF-TOKEN');
            $hasXsrfToken = $request->hasHeader('X-XSRF-TOKEN');
            $tokenFromHeader = $request->header('X-CSRF-TOKEN');
            $xsrfTokenFromHeader = $request->header('X-XSRF-TOKEN');
            
            Log::debug('CSRF Token Information', [
                'uri' => $request->path(),
                'method' => $request->method(),
                'has_token_header' => $hasToken,
                'has_xsrf_token_header' => $hasXsrfToken,
                'token_length' => $tokenFromHeader ? strlen($tokenFromHeader) : 0,
                'xsrf_token_length' => $xsrfTokenFromHeader ? strlen($xsrfTokenFromHeader) : 0,
                'session_has_token' => $request->session() ? $request->session()->has('_token') : false,
                'is_excluded' => $this->isExcluded($request)
            ]);
        }
        
        return parent::handle($request, $next);
    }

    /**
     * Check if the request URI is excluded from CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isExcluded(Request $request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
