<?php

namespace SocialiteProviders\Manager;

use function Roots\app;
use Laravel\Socialite\SocialiteServiceProvider;
use SocialiteProviders\Manager\Contracts\Helpers\ConfigRetrieverInterface;
use SocialiteProviders\Manager\Helpers\ConfigRetriever;

class SocialiteManagerServiceProvider extends SocialiteServiceProvider
{
    /**
     * Bootstrap the provider services.
     */
    public function boot()
    {
        $socialiteWasCalled = app(SocialiteWasCalled::class);

        $event = $this->app->make('event');
        $event($socialiteWasCalled);
    }

    /**
     * Register the provider services.
     */
    public function register()
    {
        parent::register();

        define('SOCIALITEPROVIDERS_STATELESS', true);
    }
}
