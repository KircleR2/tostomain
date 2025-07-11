<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Temporarily exclude these endpoints for debugging
        // Remove these exceptions once the CSRF issue is resolved
        'api/register',
        'api/login',
        'api/recovery-password',
        'api/dashboard',
        'api/store-points',
        'api/gifts',
        'api/buy-product',
        'logout', // Temporarily exclude logout route for debugging
    ];
}
