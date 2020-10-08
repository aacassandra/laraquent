<?php

namespace Laraquent;

use Illuminate\Support\ServiceProvider;

class BeautyEloquentProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Laraquent\BeautyEloquent');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../resources/config/laraquent.php' => config_path('laraquent.php'),
        ]);
    }
}
