<?php

return [
    'api_url' => env('ZOHO_API_URL') ?? '',
    'api_refresh_url' => env('ZOHO_API_REFRESH_URL') ?? '',
    'client_id' => env('ZOHO_CLIENT_ID') ?? '',
    'client_secret' => env('ZOHO_CLIENT_SECRET') ?? '',
    'refresh_token' => env('ZOHO_REFRESH_TOKEN') ?? '',
    'auth_token' => env('ZOHO_AUTH_TOKEN') ?? ''
];
