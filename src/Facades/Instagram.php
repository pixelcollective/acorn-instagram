<?php

namespace TinyPixel\Acorn\Instagram\Facades;

use Roots\Acorn\Facade;

class Instagram extends Facade
{
    /**
     * Facade Accessor
     */
    protected static function getFacadeAccessor()
    {
        return 'instagram.facade';
    }
}
