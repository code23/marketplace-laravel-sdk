# Marketplace Laravel SDK

Marketplace Laravel SDK provides an easy interface to the Markko API. Designed and developed for use with Laravel, it will reduce the work involved when integrating with the Markeplace Engine API from a new front-end application.

Authentication is handled by installing the necessary models so you can use the Auth facade in the same way as traditional Laravel applications but without the need for a database.

## Server Requirements

PHP 8 or higher

You may need to increase the server max file upload size to allow onboarding requests.

Laravel Forge - click server's PHP tab:

Max File Upload Size: 100

## Before Installation

Please be aware that installing the Marketplace Laravel SDK on a default Laravel installation will replace the base User model with the SDK User model developed to work directly with the API.

## SDK Installation

You can install this package via composer. Marketplace Laravel SDK resides in a private GitHub repository so you will need to update your composer.json by adding the following:

```bash
    ...

    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/code23/marketplace-laravel-sdk"
        }
    ],

    ...
```

Once you've provided composer with the location of the Marketplace Laravel SDK package you can install it as normal:

```bash
composer require code23/marketplace-laravel-sdk
```

Once the package is included in your project you can install the models and config by running the following install command:

```bash
php artisan marketplace-laravel-sdk:install
```

Note: If you don't want to install all of the resources included with Marketplace SDK you can install them individually:

```bash
php artisan vendor:publish --tag=marketplace-laravel-sdk-config
php artisan vendor:publish --tag=marketplace-laravel-sdk-controllers
php artisan vendor:publish --tag=marketplace-laravel-sdk-middleware
php artisan vendor:publish --tag=marketplace-laravel-sdk-models
php artisan vendor:publish --tag=marketplace-laravel-sdk-livewire-traits
```

## SDK Configuration

Before you can connect to the Marketplace Engine you'll need to add the following to your `.env` file. The API credentials will be provided by a Marketplace Super Admin.

### Authentication

```bash
# Marketplace
MPE_VERSION=v1
MPE_ORIGIN=example.markko.me
MPE_API_BASE_PATH=https://exquisite-brook-che6tcbbksr0.vapor-farm-d1.com
MPE_PASSWORD_KEY=946786a6-bf7d-48e9-8c54-9e57e6365a76
MPE_PASSWORD_SECRET=r7kiMYyqYTi9qpNRf33rLGFlME3oZlfptJ83sp2U
MPE_CLIENT_CREDENTIAL_KEY=9728f551-1f91-403c-9b24-ae55431e995a
MPE_CLIENT_CREDENTIAL_SECRET=VK9PkEAOsJxhRTOAqjKLI4uIwYlV09j1bhAA0hzl
```

Make sure your `MPE_API_BASE_PATH` reflects whether you are using an SSL certificate or not. Mismatched http/https in base path and environment will cause request route errors.

You will also need to add the MPESessionAuthentication middleware to your `web` middleware group in `App\Http\Kernel.php`. Place this as the last middleware in the middleware `web` group.

You can publish this middleware to your project should you need to make changes to it, just remember to reference the published version from within the kernel rather than the SDK version.

```php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        ...
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            ...
            \Code23\MarketplaceLaravelSDK\Http\Middleware\MPESessionAuthentication::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];
```

## Config

In your config `auth.php` file, make sure to switch the provider for the Users to the custom SDK one like so:

```php
    'users' => [
        'driver' => 'marketplace-laravel-sdk-custom'
    ],
```

## Middleware

In addition to the above auth middleware, add the following in the same fashion:

### Users

Add `MPEUserEmailVerification` middleware _below_ the `MPESessionAuthentication` class in your `web` middleware group in `App\Http\Kernel.php`.

This will detect the `email_verified=true` query parameter on the redirect url after a user has confirmed their email address via their confirmation email, and redirect the user to a route named `email-verified`. Add this route to your web.php routes file and a view informing the user of their successful email verification.

NOTE: requires your homepage route to be named `home`.

Overwrite the route name using the env variable `EMAIL_VERIFIED_ROUTE_NAME` and adjust your route declaration to match.

## Users

To ensure that your application authenticates using Marketplace Laravel SDK you'll need to ensure that Laravel knows where to look for users. To do this you'll need to update your config.auth by commenting out the current driver for `users` and adding the custom driver provided by Marketplace Laravel SDK

```php
<?php

return [
    ...

    'providers' => [
        // 'users' => [
        //     'driver' => 'eloquent',
        //     'model' => App\Models\User::class,
        // ],

        'users' => [
            'driver' => 'marketplace-laravel-sdk-custom'
        ],
    ],

    ....
```

Remember to add the appropriate middleware outlined previously for user email verification.

## Filters & Cached Data

To reduce data transfer and improve performance, filters and other data from the API that is used across multiple pages is cached, via Jobs. This data is updated on a schedule defined in the app/Console/Kernel.php file. Queue workers should be run every minute on servers to ensure the scheduled jobs are running, or use Horizon to manage the queue.

The cached data is accessed via the `MPECache` facade's `get()` method, registered in the SDK service provider, so you can use it anywhere in your application. Should the data not be present in the cache, the get method will retrieve the data from the API and store it in the cache for future use.

MPECache data can be freshed manually by calling `php artisan mpe-cache:refresh` from the command line, fetching all possible cache data (not currently configurable to specific keys). However, this command accepts a `--key` option to refresh a specific cache key only, e.g. `php artisan mpe-cache:refresh --key=categories`.

Examples:

```php
use Code23\MarketplaceLaravelSDK\Facades\v1\MPECache;

// Get all categories
$categories = MPECache::get('categories');

```

## Onboarding images mixin

<!-- TODO: include these files in package and make publishable -->

Place images.js in resources/js and add to whichever asset manager you are using, Webpack, Vite, etc:

Webpack example:

```js
mix.js('resources/js/app.js', 'public/js')
    ...
    .js('resources/js/images.js', 'public/js')
```

Import in app.js like so:

```js
import Images from "./images.js";
window.Images = Images;
```

Add route to web.php:

```php
/*
 |--------------------------------------------------------------------------
 | Signed Storage URL - S3 bucket access
 |--------------------------------------------------------------------------
 |
 | Used to create signed storage urls allowing the streaming of files from the
 | front-end prior to registering with MPE
 |
 */
Route::post('/signed-storage-url', [SignedStorageUrlController::class, 'store']);
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email dev@meetmarkko.com instead of using the issue tracker.

## Credits

-   [Markko](https://github.com/code23)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
