<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

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
        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        
        // Fix for MySQL < 5.7.7 and MariaDB < 10.2.2
        Schema::defaultStringLength(191);
        
        // Configure trusted proxies
        if (config('trustedproxy.proxies')) {
            Request::setTrustedProxies(
                config('trustedproxy.proxies') === '*' ? ['0.0.0.0/0', '::/0'] : config('trustedproxy.proxies'), 
                config('trustedproxy.headers')
            );
        }
    }
}
