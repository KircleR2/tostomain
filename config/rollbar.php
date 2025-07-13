<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rollbar Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for configuring the Rollbar service which can be used
    | to log errors. This configuration follows the format expected by
    | the Rollbar PHP SDK.
    |
    */

    'access_token' => env('ROLLBAR_TOKEN'),
    'environment' => env('APP_ENV'),
    
    'root' => base_path(),
    
    // Only report errors equal to or above this level
    'minimum_level' => env('ROLLBAR_LEVEL', 'error'),
    
    // Capture bindings on SQL queries
    'capture_sql_bindings' => true,
    
    // Capture Laravel logs
    'capture_log' => true,
    
    // Capture Laravel dumps
    'capture_dumps' => true,
    
    // Capture Laravel console command outputs
    'capture_command_output' => true,
    
    // Capture uncaught exceptions
    'capture_uncaught' => true,
    
    // Enable/disable reporting
    'enabled' => env('ROLLBAR_TOKEN') ? true : false,
    
    // Person tracking
    'person_fn' => function() {
        if (auth()->check()) {
            return [
                'id' => auth()->user()->id,
                'username' => auth()->user()->email,
                'email' => auth()->user()->email,
            ];
        }
        return null;
    },
    
    'scrub_fields' => [
        'password',
        'password_confirmation',
        'credit_card',
        'cvv',
        'card_number',
    ],
    
    'timeout' => 3,
    
    'max_items' => 10,
]; 