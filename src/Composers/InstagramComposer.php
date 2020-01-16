<?php

namespace TinyPixel\Acorn\Instagram\Composers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Roots\Acorn\Application;
use Roots\Acorn\View\Composer;
use InstagramScraper\Instagram;
use InstagramScraper\Model\Account;

/**
 * Instagram Composer
 *
 * @package TinyPixel\Acorn\Instagram\Composers
 * @author  Kelly Mears <kelly@tinypixel.dev>
 */
abstract class InstagramComposer extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = ['index'];

    /**
     * Resolves Instagram service from the application container.
     *
     * @param \Roots\Acorn\Application $app
     */
    public function __construct(Application $app, Cache $cache)
    {
        $this->app       = $app;
        $this->cache     = $cache;
        $this->instagram = $this->instagram();
    }

    /**
     * Instagram connection
     *
     * @return InstagramScraper\Instagram
     */
    public function instagram()
    {
        $this->instagram = $this->cache::remember("instagram.{$this->acount}.connection", 86400, function () {
            return $this->app->make('instagram.authenticated');
        });
    }

    /**
     * Instagram account
     *
     * @return InstagramScraper\Model\Account
     */
    public function account()
    {
        return $this->cache::remember("instagram.{$this->account}.account", 86400, function () {
            return $this->instagram->getAccount($this->account);
        });
    }

    /**
     * Instagram media
     *
     * @return \Illuminate\Support\Collection
     */
    public function media(): Collection
    {
        return $this->cache::remember("instagram.{$this->account}.media", 86400, function () {
            return $this->instagram->getMedias($this->account, $this->count);
        });
    }
}
