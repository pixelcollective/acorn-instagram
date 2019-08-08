<?php

namespace TinyPixel\AcornInstagram\Facades;

use Roots\Acorn\Facade;

class Instagram extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'instagram.facade';
    }
}
