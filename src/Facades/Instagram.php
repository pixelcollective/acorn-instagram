<?php

namespace TinyPixel\AcornInstagram\Facades;

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
