<?php

namespace Todstoychev\Icr;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Todstoychev\Icr\Console\Commands\RebuildImages;
use Todstoychev\Icr\Handler\ConfigurationValidationHandler;
use Todstoychev\Icr\Handler\DirectoryHandler;
use Todstoychev\Icr\Handler\OriginalImageHandler;
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

        // Directory handler
        $this->app->bind('icr.directory.handler', function () {
            return new DirectoryHandler(
                $this->app->make('icr.config')
            );
        });

        // Original file handler
        $this->app->bind('icr.original_image.handler', function () {
            return new OriginalImageHandler(
                $this->app->make('icr.config')
            );
        });

        // ICR main processor class
        $this->app->bind('icr.processor', function() {
            return new Processor(
                $this->app->make('icr.configuration_validation.handler'),
                $this->app->make('icr.directory.handler'),
                $this->app->make('icr.original_image.handler')
            );
        });
    }

    public function provides()
    {
        return ['command.rebuild_images'];
    }
}
