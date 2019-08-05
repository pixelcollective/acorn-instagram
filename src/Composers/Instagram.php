<?php

namespace App\Composers;

use function Roots\app;
use Roots\Acorn\View\Composer;
use Roots\Acorn\Application;

class Instagram extends Composer
{
    /** @var string  */
    public $account = '';

    /**
     * Resolves Instagram service
     * from the application container
     *
     * @return void
     */
    public function __construct(\Roots\Acorn\Application $app)
    {
        $this->insta = $app['instagram'];
        $this->cache = $app['cache']->store('file');
    }

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = ['index'];

    /**
     * Data to be passed to view before rendering.
     *
     * @param  array $data
     * @param  \Illuminate\View\View $view
     * @return array
     */
    public function with($data, $view)
    {
        return [
            'instagram' => (object) [
                'profile' => $this->cache->remember('insta.profile', 3600, function () {
                    return (array) $this->insta->getAccount($this->account);
                }),
                'media'  => $this->cache->rememberForever('insta.recent', function () {
                    return (array) $this->insta->getMedias($this->account, 5);
                }),
            ],
        ];
    }
}
