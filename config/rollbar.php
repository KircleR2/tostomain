<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rollbar Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for configuring the Rollbar service which is used for
    | error tracking and logging in the application.
    |
    */

    // Rollbar access token
    'access_token' => env('ROLLBAR_TOKEN'),

    // Capture source code for stack traces
    'capture_source' => true,

    // Minimum log level to report to Rollbar
    'level' => env('ROLLBAR_LEVEL', 'error'),

    // Whether Rollbar is enabled
    'enabled' => env('ROLLBAR_ENABLED', true),

    // Don't collect user information
    'capture_email' => false,
    'capture_username' => false,

    // Environment name
    'environment' => env('APP_ENV', 'production'),

    // Root directory for source code
    'root' => base_path(),

    // Additional configuration
    'scrub_fields' => [
        'password',
        'password_confirmation',
        'credit_card',
        'auth_token',
        'secret',
    ],
]; 