<?php

namespace TinyPixel\Acorn\Spectacle\Exceptions;

class Handlers
{
    /**
     * Render Exception in browser.
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof GuzzleException) {
            return response('An error occurred when making request to Instagram.');
        }

        return parent::render($request, $exception);
    }
}
