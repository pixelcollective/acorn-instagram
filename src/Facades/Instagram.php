<?php

namespace TinyPixel\Acorn\Spectacle\Facades;

use Roots\Acorn\Facade;

class Instagram extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'api.instagram';
    }
}
