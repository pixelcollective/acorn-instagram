<?php

namespace TinyPixel\AcornInstagram\Providers;

use Roots\Acorn\Application;
use Roots\Acorn\ServiceProvider;
use Illuminate\Support\Collection;
use TinyPixel\AcornInstagram\Instagram;

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
        $this->composers([
            __DIR__ . '/../../publish/Composers/Instagram.php',
        ], 'composers');
    }

    /**
     * Add publishable composers
     *
     * @param  array $source
     * @return void
     */
    public function composers(array $source, $group = null) : void
    {
        Collection::make($source)->each(function ($composer) use ($group) {
            $this->publishes([
                $composer => $this->composerPath() . basename($composer)
            ], isset($group) ? $group : null);
        });
    }

    public function composerPath() : string
    {
        return $this->app->basePath('app/Composers/');
    }
}
