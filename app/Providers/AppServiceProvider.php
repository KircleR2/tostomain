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
        
        // Get the current domain
        $domain = request()->getHost();
        
        // Set session domain and sanctum domains based on the current domain
        if ($domain === 'www.tostocoffee.com' || $domain === 'tostocoffee.com') {
            // For main production domains
            Config::set('session.domain', '.tostocoffee.com');
            Config::set('sanctum.stateful_domains', [
                'www.tostocoffee.com',
                'tostocoffee.com',
            ]);
        } elseif ($domain === 'tostomain-achxn.ondigitalocean.app') {
            // For Digital Ocean domain
            Config::set('session.domain', 'tostomain-achxn.ondigitalocean.app');
            Config::set('sanctum.stateful_domains', [
                'tostomain-achxn.ondigitalocean.app',
            ]);
        } else {
            // For local development or other environments
            $statefulDomains = config('sanctum.stateful_domains', []);
            if (!in_array($domain, $statefulDomains)) {
                $statefulDomains[] = $domain;
                Config::set('sanctum.stateful_domains', $statefulDomains);
            }
        }
    }
}
