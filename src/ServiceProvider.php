<?php

namespace Todstoychev\Icr;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Todstoychev\Icr\Handler\DirectoryHandler;
use Todstoychev\Icr\Validator\ConfigurationValidator;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'icr');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('icr/config.php'),
        ]);
    }
}
