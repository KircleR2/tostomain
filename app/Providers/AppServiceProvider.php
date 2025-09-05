<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Always force HTTPS for URLs
        URL::forceScheme('https');
        
        // Set trusted proxies for proper HTTPS detection
        if (config('app.env') !== 'local') {
            $request = app('request');
            $request->setTrustedProxies(
                // Trust all proxies in production
                ['0.0.0.0/0'],
                \Illuminate\Http\Request::HEADER_X_FORWARDED_ALL
            );
        }
        
        Schema::defaultStringLength(191);
    }
}