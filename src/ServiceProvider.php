<?php

namespace Todstoychev\Icr;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseProvider;
use Todstoychev\Icr\Handler\OpenImageHandler;
use Todstoychev\Icr\Manager\FileManager;
use Todstoychev\Icr\Manipulator\Box;
use Todstoychev\Icr\Manipulator\ManipulatorFactory;
use Todstoychev\Icr\Manipulator\Point;

class ServiceProvider extends BaseProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/' => config_path('icr/'),
            ]
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php',
            'icr'
        );

        $this->app->bind(
            'manipulator_factory',
            function () {
                return new ManipulatorFactory(new Box(), new Point());
            }
        );

        $this->app->bind(
            'icr_processor',
            function () {
                return new Processor(
                    Config::get('icr'),
                    $this->app->make('manipulator_factory'),
                    new OpenImageHandler(),
                    new FileManager()
                );
            }
        );
    }
}
