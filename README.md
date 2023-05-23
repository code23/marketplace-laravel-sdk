# Marketplace Laravel SDK

Marketplace Laravel SDK provides an easy interface to the Code23 Marketplace Engine API. Designed and developed for use with Laravel, it will reduce the work involved when integrating with the Markeplace Engine api from a new front-end application.

Authentication is handled by installing the necessary models, views and components so you can use the Auth facade in the same way as traditional
Laravel applications but but without the need for a database.

## Server Requirements

PHP 8

You may need to increase the server max file upload size to allow onboarding requests.

Forge - click server's PHP tab:

Max File Upload Size: 16

## Before Installation

Please be aware that installing the Marketplace Laravel SDK on a default Laravel installation will replace the base User model with the SDK User model developed to work directly with the API.

<!-- The blade files provided by the SDK utilise Tailwind CSS so it's recommended that you install Tailwind on your Laravel instance :-
```php
npm install -D tailwindcss@latest postcss@latest autoprefixer@latest
npx tailwindcss init
``` -->

<!-- Once installed add the following lines to your tailwind.config.js :- -->
<!-- TODO: update this part for tailwind 3 & JIT -->
<!-- ```php
// tailwind.config.js
module.exports = {
   purge: [
     './resources/**/*.blade.php',
     './resources/**/*.js',
     './resources/**/*.vue',
   ],
   ...
``` -->

<!-- And finally, configure Laravel Mix :-
```php
// webpack.mix.js
mix.js("resources/js/app.js", "public/js")
    .postCss("resources/css/app.css", "public/css", [
        require("tailwindcss"),
    ]);
``` -->

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

Once the package is included in your project you can install the models, config, views and components by running the following install command:

```bash
php artisan marketplace-laravel-sdk:install
```

Note: If you don't want to install all of the resources included with Marketplace SDK you can install them individually:

```bash
php artisan vendor:publish --tag=marketplace-laravel-sdk-config
php artisan vendor:publish --tag=marketplace-laravel-sdk-controllers
php artisan vendor:publish --tag=marketplace-laravel-sdk-middleware
php artisan vendor:publish --tag=marketplace-laravel-sdk-models
php artisan vendor:publish --tag=marketplace-laravel-sdk-views
php artisan vendor:publish --tag=marketplace-laravel-sdk-view-components
php artisan vendor:publish --tag=marketplace-laravel-sdk-livewire-traits
```

## SDK Configuration

Before you can connect to the Marketplace Engine you'll need to add the following to your `.env` file. The API key and secret will be provided by a Marketplace Super Admin and the PAC key can be found in the Tenant admin dashboard. The PAC key combined with the Origin (site url TLD and host only) allows the website to authenticate with MPE without needing a user to login. This allows the website to consume certain endpoints e.g. product lists, categories etc.

### Authentication

```bash
# Marketplace
MPE_VERSION=v1
MPE_ORIGIN=marketplace.code23.co.uk
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

### Categories, Tags, Attributes

Add `MPESessionCategories` middleware _below_ the `MPESessionAuthentication` class in your `web` middleware group in `App\Http\Kernel.php`.

This middleware stores the available categories to an object in the user's session, to reduce API traffic.

The rate at which the categories are updated can be set via the env var `CATEGORY_RETRIEVAL_RATE`, default 10 minutes.

Tags has a separate middleware called `MPESessionTags` that functions the same way as categories, as does `MPESessionAttributes`.

_Note_ session data cannot be used in views/templates that dont use middleware, such as the 404 page. To overcome this, add the following to the bottom of the web.php routes file:

```
// Adding this fallback route to the 404 view solves the issue of 404 page not having access to session data.
// which is used to populate the header navigation with categories
// https://laravel.com/docs/9.x/routing#fallback-routes
Route::fallback(function () {
    return view('errors/404');
});
```

### Currencies

If you require multi-currency options, you'll need to include the `MPESessionCurrencies` middleware _below_ the `MPESessionAuthentication` class in your `web` middleware group in `App\Http\Kernel.php`.

This middleware stores the available currencies to an object in the user's session.

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

## Onboarding images mixin

<!-- TODO: include these files in package and make publishable -->

Place images.js in resources/js and add to webpack.mix.js:

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

If you discover any security related issues, please email dev@code23.com instead of using the issue tracker.

## Credits

-   [Code23](https://github.com/code23)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
