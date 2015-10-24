<?php

namespace Todstoychev\Icr;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Todstoychev\Icr\Console\Commands\RebuildImages;
use Todstoychev\Icr\Handler\ConfigurationValidationHandler;
use Todstoychev\Icr\Handler\DirectoryHandler;
use Todstoychev\Icr\Handler\RebuildImagesHandler;
use Todstoychev\Icr\Reader\DirectoryTreeReader;
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

        // Bind ICR cofiguration
        $this->app->bind('icr.config', function () {
            return Config::get('icr.config');
        });

        // Configuration validator
        $this->app->bind('icr.configuration_validation.handler', function () {
            return new ConfigurationValidationHandler(
                $this->app->make('icr.config')
            );
        });

        // ICR main processor class
        $this->app->bind('icr.processor', function() {
            return new Processor();
        });

        // Commands
        $this->commands(['command.rebuild_images']);
    }

    public function provides()
    {
        return ['command.rebuild_images'];
    }
}
