<?php

namespace TinyPixel\Acorn\Instagram\Providers;

use Roots\Acorn\Application;
use Roots\Acorn\ServiceProvider;
use Illuminate\Support\Collection;
use TinyPixel\Acorn\Instagram\Instagram;

class InstagramServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register() : void
    {
        $this->app->singleton('instagram', function () {
            return new Instagram();
        });

        $this->app->singleton('instagram.authenticated', function () {
            $settings = $this->app['config']->get('services.instagram.system');

            $instagram = Instagram::withCredentials(
                $settings['global']['username'],
                $settings['global']['password'],
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
    public function boot() : void
    {
        Collection::make([__DIR__ . '/../../publish/Composers/Instagram.php'])->each(function ($composer) use ($group) {
            $this->publishes([
                $composer => $this->composerPath() . basename($composer)
            ], 'instagram');
        });
    }

    public function composerPath() : string
    {
        return $this->app->basePath('app/Composers/');
    }
}
