<?php

namespace TinyPixel\Acorn\Instagram;

use InstagramScraper\Instagram as InstagramBase;

class Instagram extends InstagramBase
{
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
        $instance = new self();

        $instance->sessionUsername = $username;
        $instance->sessionPassword = $password;

        return $instance;
    }
}
