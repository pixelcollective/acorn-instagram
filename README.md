## Acorn Instagram

Easily work with the Instagram API in WordPress. No more than a svelte wrapper for [`postaddictme/instagram-php-scraper`](https://github.com/postaddictme/instagram-php-scraper/blob/master/examples/getSidecarMediaByUrl.php) with Acorn goodies like prebuilt View Composers and Facades. No authentication required for most tasks.

## Requirements

[Sage](https://github.com/roots/sage) >= 10.0

[PHP](https://secure.php.net/manual/en/install.php) >= 7.3

[Composer](https://getcomposer.org)

## Installation

Install via composer:

```bash
composer require tiny-pixel/acorn-instagram
```

After installation run the following command to publish the configuration file and view composer.

```bash
wp acorn vendor:publish
```

## Usage examples

```php
<img src="{!! Instagram::getMediaByUrl('https://www.instagram.com/p/B0wiRW2ghGP/')->getHighResolutionUrl() !!}" />
```

```php
{!! Instagram::getAccount('thedreamdefenders')->getFullName !!}
```

Made with ❤ for [the Dream Defenders](http://dreamdefenders.org).
