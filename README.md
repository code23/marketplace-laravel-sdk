# Marketplace Laravel SDK

Marketplace Laravel SDK provides an easy interface to the Code23 Marketplace Engine API.  Designed and developed for use with Laravel, it will reduce the 
work involved when integrating with the Markeplace Engine api from a new front-end application.

Authentication is handled by installing the necessary models, views and components so you can use the Auth facade in the same way as traditional
Laravel applications but but without the need for a database.

## Before Installation

Please be aware that installing the Marketplace Laravel SDK on a default Laravel installation will replace the base User model with the SDK User model 
developed to work directly with the API. 

The blade files provided by the SDK utilise Tailwind CSS so it's recommended that you install Tailwind on your Laravel instance :-
```php
npm install -D tailwindcss@latest postcss@latest autoprefixer@latest
npx tailwindcss init
```

Once installed add the following lines to your tailwind.config.js :-
```php
// tailwind.config.js
module.exports = {
   purge: [
     './resources/**/*.blade.php',
     './resources/**/*.js',
     './resources/**/*.vue',
   ],
   ...
```

And finally, configure Laravel Mix :-
```php
// webpack.mix.js
mix.js("resources/js/app.js", "public/js")
    .postCss("resources/css/app.css", "public/css", [
        require("tailwindcss"),
    ]);
```

## SDK Installation

You can install this package via composer.  Marketplace Laravel SDK resides in a private GitHub repository so you will need to update your composer.json by
adding the following:
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

Note:  If you don't want to install all of the resources included with Marketplace SDK you can install them individually:
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

Before you can connect to the Marketplace Engine you'll need to add the following to your `.env` file. The API key and secret will be provided by a Marketplace Super Admin and the PAC key can be found in the Tenant admin dashboard.  The PAC key combined with the Origin (site url TLD and host only) allows the website to authenticate with MPE without needing a user to login.  This allows the website to consume certain endpoints e.g. product lists, categories etc.

### Authentication

```bash
# Marketplace
MPE_VERSION=v1
MPE_ORIGIN=tenanturl.com
MPE_API_BASE_PATH=https://mpe.test
MPE_API_KEY=93ec3d35-f905-47bd-a22e-0090906776f4
MPE_API_SECRET=PuT2NZZ1fJAL30wxVcovPrS2Al8pVNzJ5nAFsC2b
MPE_PAC_KEY=94736f1c-970b-41c7-a29d-a14cc09d4d13
```
Make sure your `MPE_API_BASE_PATH` reflects whether you are using an SSL certificate or not. Mismatched http/https in base path and environment will cause request route errors.

You will also need to add the MPEPACAuthentication middleware to your `web` middleware group in `App\Http\Kernel.php`.  You MUST make sure that it is the last middleware in the middleware `web` group.

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
            \Code23\MarketplaceLaravelSDK\Http\Middleware\MPEPACAuthentication::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];
```

### Currencies

If you require multi-currency options, you'll need to include the `MPESessionCurrencies` middleware *below* the `MPEPACAuthentication` class in your `web` middleware group in `App\Http\Kernel.php`.

This middleware stores the available currencies to an object in the user's session.

## Usage

To ensure that your application authenticates using Marketplace Laravel SDK you'll need to ensure that Laravel knows where to look for users.  To do this you'll need to update your config.auth by commenting out the current driver for `users` and adding the custom driver provided by Marketplace Laravel SDK
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

## Creating SDK Services

When creating a Service and its corresponding Facade, reference the Service via it's class name and add a `use` statement at the top of the file.

### Facade
```php
    use Code23\MarketplaceLaravelSDK\Services\AuthenticationService;

    class MPEAuthentication extends Facade
    {
        protected static function getFacadeAccessor()
        {
            // return the service
            return AuthenticationService::class;
        }
    }
```

## Onboarding images mixin

Place images.js in resources/js and add to webpack.mix.js:
```js
mix.js('resources/js/app.js', 'public/js')
    ...
    .js('resources/js/images.js', 'public/js')
```

Import in app.js like so:
```js
import Images from './images.js'
window.Images = Images
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
