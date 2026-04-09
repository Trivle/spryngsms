<?php

namespace Oscar\Spryngsms;

use Illuminate\Support\ServiceProvider;

class SpryngsmsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [__DIR__.'/../config/spryngsms.php' => config_path('spryngsms.php')],
                'spryngsms-config'
            );
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/spryngsms.php', 'spryngsms');
    }
}
