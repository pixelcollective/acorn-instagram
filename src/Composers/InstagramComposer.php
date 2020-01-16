<?php

namespace TinyPixel\Acorn\Instagram\Composers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Roots\Acorn\Application;
use Roots\Acorn\View\Composer;
use InstagramScraper\Model\Account;

/**
 * Instagram Composer
 *
 * @package TinyPixel\Acorn\Instagram\Composers
 * @author  Kelly Mears <kelly@tinypixel.dev>
 */
abstract class InstagramComposer extends Composer
{
    /**
     * Username
     *
     * @var string
     */
    public $username;

    /**
     * Password
     *
     * @var string
     */
    public $password;

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = ['index'];

    /**
     * Resolves Instagram service from the application container.
     *
     * @param \Roots\Acorn\Application $app
     */
    public function __construct(Application $app)
    {
        $this->instagram = $app->make('instagram.authenticated');

        $this->instagram->authenticate();
    }

    /**
     * Data to be passed to view before rendering.
     *
     * @param  array $data
     * @param  View $view
     * @return array
     */
    public function with()
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
    public function account(): Collection
    {
        $account = $this->instagram->getAccount($this->account);

        return $this->getAccount($account);
    }

    /**
     * Instagram media
     *
     * @return Collection
     */
    public function media(): Collection
    {
        $media = Collection::make($this->instagram->getMedias($this->account, $this->count));

        return $this->getMedia($media);
    }

    /**
     * Process account data.
     *
     * @param  Collection $account
     * @return Collection
     */
    public function getAccount(Account $account): Collection
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
    public function getMedia(Collection $media): Collection
    {
        $collected = Collection::make();

        $media->each(function ($item) use (&$collected) {
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
    public function linkHashtags(string $text): string
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

        preg_match_all($pattern, $text, $hashtags);

        Collection::make($hashtags)->each(function ($hashtag) {
            if (!empty($hashtag[0])) {
                if (!$this->collectedHashtags->contains($hashtag[0])) {
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
