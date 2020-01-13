<?php

namespace TinyPixel\AcornInstagram;

use InstagramScraper\Instagram as InstagramBase;
use Illuminate\Cache\Repository;

class Instagram extends InstagramBase
{
    /**
     * Acorn cache
     *
     * @var Repository $acornCache
     */
    protected static $acornCache;

    /**
     * Access Instagram with authentication
     *
     * @param string     $username
     * @param string     $password
     * @param string     $dir
     *
     * @return Instagram
     */
    public static function withCredentials($username, $password)
    {
        static::$instanceCache = Repository::getInstance();

        $instance = new self();

        $instance->sessionUsername = $username;
        $instance->sessionPassword = $password;

        return $instance;
    }
}
