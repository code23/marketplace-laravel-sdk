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
php artisan vendor:publish --tag=marketplace-laravel-sdk-models
php artisan vendor:publish --tag=marketplace-laravel-sdk-views
php artisan vendor:publish --tag=marketplace-laravel-sdk-view-components
```

## SDK Configuration

Before you can connect to the Marketplace Engine you'll need to add the following to your `.env` file. The api key and secret can be provided by your Marketplace Engine Super Admin.

```bash
# Marketplace
MPE_VERSION=v1
MPE_API_BASE_PATH=https://mpe.test
MPE_API_KEY=93ec3d35-f905-47bd-a22e-0090906776f4
MPE_API_SECRET=PuT2NZZ1fJAL30wxVcovPrS2Al8pVNzJ5nAFsC2b
```

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

## Services

When creating a Service and its corresponding Facade you will make reference to the service alias from the Facade rather than the service itself. Then remember to register the service with its alias in `MarketplaceLaravelSDKServiceProvider`.

### Facade
```php
    class MPEAuthentication extends Facade
    {
        protected static function getFacadeAccessor()
        {
            // return the alias
            return 'marketplace-laravel-sdk-authentication';
        }
    }
```

### MarketplaceLaravelSDKServiceProvider
```php
    // bind the service to an alias
    $this->app->bind('marketplace-laravel-sdk-authentication', function () {
        return new AuthenticationService();
    });
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
