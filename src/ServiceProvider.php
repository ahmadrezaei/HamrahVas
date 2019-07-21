<?php

namespace Alirezadp10\Hamrahvas;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Publishes configuration file.
     *
     * @return  void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/hamrahvas.php' => config_path('hamrahvas.php'),
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return  void
     */
    public function register()
    {
        $this->app->singleton(Hamrahvas::class, function ($app) {
            return new Hamrahvas(config('hamrahvas'));
        });
    }
}