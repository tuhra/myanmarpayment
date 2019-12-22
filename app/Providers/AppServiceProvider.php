<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app['request']->server->set('HTTPS', true);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\AffiliateServices\AffiliateService::class,
            \App\AffiliateServices\HasOfferService::class,
            \App\AffiliateServices\AffiseService::class);
    }
}
