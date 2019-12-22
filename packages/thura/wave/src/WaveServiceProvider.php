<?php

namespace Thura\Wave;

use Illuminate\Support\ServiceProvider;

class WaveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        require __DIR__ . '/routes/routes.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('thura-wave', function () {
            return new Wave();
        });
    }
}
