<?php

namespace TinyPixel\Acorn\Instagram\Providers;

use InstagramScraper\Instagram;
use Roots\Acorn\Application;
use Roots\Acorn\ServiceProvider;

class InstagramServiceProvider extends ServiceProvider
{
    /**
     * Registers application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('instagram', function () {
            return new Instagram();
        });

        $this->app->singleton('instagram.global', function () {
            $settings = $this->app['config']->get('services.instagram.system');

            $instagram = Instagram::withCredentials(
                $settings['global']['username'],
                $settings['global']['password'],
                get_theme_file_path('storage/framework/cache')
            );

            return $instagram->login();
        });

        $this->app->bind('instagram.facade', function (Application $app) {
            return $app->make('instagram');
        });
    }

    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
