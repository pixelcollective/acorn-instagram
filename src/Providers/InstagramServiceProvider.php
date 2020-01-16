<?php

namespace TinyPixel\Acorn\Instagram\Providers;

use Roots\Acorn\Application;
use Roots\Acorn\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Cache\ArrayStore;
use Cache\Adapter\Illuminate\IlluminateCachePool;
use InstagramScraper\Instagram;

class InstagramServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('instagram', function () {
            return new Instagram();
        });

        $this->app->singleton('instagram.authenticated', function ($app) {
            $this->settings = $this->app['config']->get('services.instagram');
            // Create an instance of an Illuminate's Store
            // Wrap the Illuminate's store with the PSR-6 adapter
            $store = new ArrayStore();
            $pool  = new IlluminateCachePool($store);

            $instagram = Instagram::withCredentials(
                $this->settings['username'],
                $this->settings['password'],
                $pool
            );

            $instagram->login();

            return $instagram;
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
    public function boot(): void
    {
        Collection::make([__DIR__ . '/../../publish/Composers/Instagram.php'])->each(function ($composer) {
            $this->publishes([$composer => $this->app->basePath('app/Composers/') . basename($composer)], 'instagram');
        });
    }
}
