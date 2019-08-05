<?php

namespace TinyPixel\Acorn\Instagram;

use InstagramScraper\Instagram as InstagramBase;
use Illuminate\Cache\CacheManager;

class Instagram extends InstagramBase
{
    /** @var Illuminate\Cache\CacheManager */
    protected static $acornCache;

    /**
     * @param string $username
     * @param string $password
     * @param null $sessionFolder
     *
     * @return Instagram
     */
    public static function withCredentials($username, $password, $sessionFolder = null)
    {
        static::$instanceCache = CacheManager::getInstance('files');

        $instance = new self();

        $instance->sessionUsername = $username;
        $instance->sessionPassword = $password;

        return $instance;
    }
}
