<?php

namespace TinyPixel\Acorn\Spectacle\Providers;

use TinyPixel\Acorn\Spectacle\InstagramAPI;
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
        $this->app->bind('InstagramAPI', function () {
            return new InstagramAPI();
        });
    }

    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
