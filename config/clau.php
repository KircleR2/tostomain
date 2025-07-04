<?php

return [
    'api_url' => env('CLAU_API_URL') ?? '',
    'api_pos_key' => env('CLAU_API_POS_KEY')?? '',
    'api_auth_key' => env('CLAU_API_AUTH_KEY')?? '',
    'apikey_provider' => env('CLAU_API_KEY_PROVIDER')?? '',
    'appid' => env('CLAU_APPID') ?? '',
    'origin' => env('CLAU_ORIGIN')?? '',
    'webhook_origin' => env('CLAU_WEBHOOK_ORIGIN')?? '',
    'webhook_key' => env('CLAU_WEBHOOK_KEY')?? '',
];
