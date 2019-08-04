<?php

namespace TinyPixel\Acorn\Spectacle;

use GuzzleHttp\Client;

/**
 * Instagram API
 *
 * @author  Kelly Mears <kelly@tinypixel.dev>
 * @since   1.0.0
 * @license MIT
 *
 * @package    Spectacle
 * @subpackage Instagram
 */
class InstagramAPI
{
    /** @var string */
    private $client;

    /** @var string */
    private $access_token;

    /** @var string */
    protected $baseInstagramUri = 'https://api.instagram.com/v1/';

    /**
     * Initializes Guzzle.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseInstagramUri,
        ]);
    }

    /**
     * Sets Access Token
     *
     * @param string $token
     */
    public function setAccessToken(string $token)
    {
        $this->access_token = $token;
    }

    /**
     * Get Instagram User.
     *
     * @return array
     */
    public function getUser()
    {
        if ($this->access_token) {
            $response = $this->client->request('GET', 'users/self/', [
                'query' => [
                    'access_token' =>  $this->access_token
                ]
            ]);

            return json_decode($response->getBody()->getContents())->data;
        }

        return [];
    }

    /**
     * Get Instagram Posts
     *
     * @return array
     */
    public function getPosts()
    {
        if ($this->access_token) {
            $response = $this->client->request('GET', 'users/self/media/recent/', [
                'query' => ['access_token' =>  $this->access_token]
            ]);

            return json_decode($response->getBody()->getContents())->data;
        }

        return [];
    }

    /**
     * Get posts with tag
     *
     * @param  string $tags
     * @return string
     */
    public function getTagPosts($tags)
    {
        if ($this->access_token) {
            $response = $this->client->request('GET', "tags/{$tags}/media/recent/", [
                'query' => [
                    'access_token' =>  $this->access_token
                ]
            ]);
            return json_decode($response->getBody()->getContents())->data;
        }
        return [];
    }
}
