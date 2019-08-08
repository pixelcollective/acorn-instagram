<?php

namespace App\Composers;

use TinyPixel\AcornInstagram\Composers\InstagramComposer;

/**
 * Instagram Composer
 *
 * @package TinyPixel\Acorn\Instagram\Composers
 * @author  Kelly Mears <kelly@tinypixel.dev>
 */
class Instagram extends InstagramComposer
{
    /**
     * Instagram account name.
     *
     * @var string
     **/
    protected static $account = '';

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'index',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @param  array $data
     * @param  Illuminate\View $view
     * @return array
     */
    public function with($data, $view)
    {
        return [
            'profile'  => (object) $this->account()->toArray(),
            'media'    => (object) $this->media()->toArray(),
            'hashtags' => (object) $this->collectedHashtags->all(),
        ];
    }
}
