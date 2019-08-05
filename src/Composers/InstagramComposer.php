<?php

namespace TinyPixel\Acorn\Instagram\Composers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Cache\CacheManager as Cache;
use Roots\Acorn\Application;
use Roots\Acorn\View\Composer;
use TinyPixel\Acorn\Instagram\Instagram as InstagramService;

/**
 * Instagram Composer
 *
 * @package TinyPixel\Acorn\Instagram\Composers
 * @author  Kelly Mears <kelly@tinypixel.dev>
 */
class InstagramComposer extends Composer
{
    /** @var string  */
    protected static $account;

    /** @var Collection */
    protected $collectedHashtags;

    /** @var Cache */
    protected $cache;

    /** @var Instagram */
    protected $instagram;

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = ['index'];

    /**
     * Resolves Instagram service.
     * from the application container
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->instagram = $app[InstagramService::class];
        $this->cache = $app[Cache::class]->store('file');
    }

    /**
     * Data to be passed to view before rendering.
     *
     * @param  array $data
     * @param  View $view
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

    /**
     * Instagram Account
     *
     * @return Collection
     */
    public function account() : Collection
    {
        $account = $this->cache->rememberForever('instagram-profile', function () {
            return $this->instagram->getAccount(self::account);
        });

        return $this->templateAccount($account);
    }

    /**
     * Instagram media
     *
     * @return Collection
     */
    public function media() : Collection
    {
        $media = $this->cache->rememberForever('instagram.recent', function () {
            return Collection::make($this->instagram->getMedias(self::account, 6));
        });

        return $this->templateMedia($media);
    }

    /**
     * Process account data.
     *
     * @param  Collection $account
     * @return Collection
     */
    public function templateAccount(\InstagramScraper\Model\Account $account) : Collection
    {
        return Collection::make([
            'username'        => $account->getUsername(),
            'fullname'        => $account->getFullname(),
            'profilePicUrl'   => $account->getProfilePicUrl(),
            'profilePicHdUrl' => $account->getProfilePicUrlHd(),
            'biographyUrl'    => $this->linkHashtags(nl2br($account->getBiography())),
            'profileUrl'      => $account->getExternalUrl(),
            'following'       => $account->getFollowsCount(),
            'followedCount'   => $account->getFollowedByCount(),
            'postCount'       => $account->getMediaCount(),
            'category'        => $account->getBusinessCategoryName(),
        ]);
    }

    /**
     * Process media items.
     *
     * @param  Collection $media
     * @return Collection
     */
    public function templateMedia(Collection $media) : Collection
    {
        $collected = Collection::make();

        $media->each(function ($item) use (& $collected) {
            $collected->push(Collection::make([
                'id'        => $item->getId(),
                'shortcode' => $item->getShortcode(),
                'type'      => $item->getType(),
                'caption'   => $this->linkHashtags(nl2br($item->getCaption())),
                'imageUrl'  => $item->getImageHighResolutionUrl(),
            ]));
        });

        return $collected;
    }

    /**
     * Link hashtags in a body of text
     *
     * @param  string $text
     * @return string $linked
     */
    public function linkHashtags(string $text) : string
    {
        $this->collectHashtags(
            $linked = preg_replace(
                '/#(\w*[a-zA-Z_]+\w*)/',
                '#<a href="https://www.instagram.com/explore/tags/\1">\1</a>',
                $text
            )
        );

        return $linked;
    }

    /**
     * Collect hashtags and store in aggregate
     *
     * @param  string $text
     * @return void
     */
    public function collectHashtags(string $text)
    {
        if (!isset($this->collectedHashtags)) {
            $this->collectedHashtags = Collection::make();
        }

        $pattern = '/#<a href=".+instagram.+explore.+">\w*[a-zA-Z_]+\w*<\/a>/';

        preg_match_all($pattern, $text,$hashtags);

        Collection::make($hashtags)->each(function ($hashtag) {
            if (! empty($hashtag[0])) {
                if (! $this->collectedHashtags->contains($hashtag[0])) {
                    $this->collectedHashtags->push($hashtag[0]);
                }
            }
        });
    }

    /**
     * Returns human-readable time difference
     *
     * @param int $time
     * @return string
     */
    public function timeSince(int $time)
    {
        return Carbon::createFromTimestamp($time)->diffForHumans();
    }
}
