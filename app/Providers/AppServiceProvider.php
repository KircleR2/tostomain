<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

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
        if(config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        
        // Set session domain and sanctum domains for production
        $domain = request()->getHost();
        if ($domain === 'www.tostocoffee.com' || $domain === 'tostocoffee.com') {
            Config::set('session.domain', '.tostocoffee.com');
            Config::set('sanctum.stateful_domains', [
                'www.tostocoffee.com',
                'tostocoffee.com',
            ]);
        }
    }
}
