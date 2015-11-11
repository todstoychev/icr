<?php

namespace Todstoychev\Icr;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Todstoychev\Icr\Console\RebuildCommand;
use Todstoychev\Icr\Handler;
use Todstoychev\Icr\Reader\DirectoryTreeReader;

/**
 * Service provider class
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'icr');

        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('icr/config.php'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        // Bind ICR configuration
        $this->app->bind('icr.config', function () {
            return 'icr.config';
        });

        // Configuration validator
        $this->app->bind('icr.configuration_validation.handler', function () {
            return new Handler\ConfigurationValidationHandler(
                $this->app->make('icr.config')
            );
        });

        // Directory handler
        $this->app->bind('icr.directory.handler', function () {
            return new Handler\DirectoryHandler(
                $this->app->make('icr.config')
            );
        });

        // Original file handler
        $this->app->bind('icr.uploaded_file.handler', function () {
            return new Handler\UploadedFileHandler(
                $this->app->make('icr.config')
            );
        });

        // Open image handler
        $this->app->bind('icr.open_image.handler', function () {
            return new Handler\OpenImageHandler(
                $this->app->make('icr.config')
            );
        });

        // Delete image handler
        $this->app->bind('icr.delete_image.handler', function () {
            return new Handler\DeleteImageHandler(
                $this->app->make('icr.config')
            );
        });

        // Directory tree reader
        $this->app->bind('icr.directory_tree.reader', function () {
            return new DirectoryTreeReader();
        });

        // ICR main processor class
        $this->app->bind('icr.processor', function () {
            return new Processor(
                $this->app->make('icr.configuration_validation.handler'),
                $this->app->make('icr.directory.handler'),
                $this->app->make('icr.uploaded_file.handler'),
                $this->app->make('icr.open_image.handler'),
                $this->app->make('icr.delete_image.handler'),
                $this->app->make('icr.directory_tree.reader')
            );
        });

        // Rebuild command
        $this->app->bind('icr.rebuild.command', function () {
            return new RebuildCommand(
                $this->app->make('icr.processor')
            );
        });

        // Commands
        $this->commands(['icr.rebuild.command']);
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return ['icr.rebuild.command'];
    }
}
