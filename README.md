# Marketplace SDK

Marketplace SDK provides an easy interface to the Code23 Marketplace Engine API.  Designed and developed for use with Laravel, it will reduce the 
work involved when integrating with the Markeplace Engine api from a new front-end application.

Authentication is handled by installing the necessary models, views and components so you can use the Auth facade in the same way as traditional
Laravel applications but but without the need for a database.

## Before Installation

Please be aware that installing the Marketplace SDK on a default Laravel installation will replace the base User model with the SDK User model 
developed to work directly with the API. 

## Installation

You can install the package via composer:

```bash
composer require code23/marketplace-sdk
```

Once the package is included in your project you can install the models, config, views and components by running the following install command:
```bash
php artisan marketplace-sdk:install
```

Note:  If you don't want to install all of the resources included with Marketplace SDK you can install them individually:
```bash
php artisan vendor:publish --tag=mpe-config
php artisan vendor:publish --tag=mpe-models
php artisan vendor:publish --tag=mpe-views
php artisan vendor:publish --tag=mpe-view-components
```

## Usage

Update config.auth by commenting out the current driver for users and adding the custom driver provided by Marketplace SDK
```php
<?php

return [
    // 'users' => [
    //     'driver' => 'eloquent',
    //     'model' => App\Models\User::class,
    // ],

    'providers' => [
        'users' => [
            'driver' => 'marketplace-sdk-custom'
        ],
    ],

    ....
```

## Services

When creating a Service and its corresponding Facade you will make reference to the service alias from the Facade rather than the service itself. Then remember to register the service with its alias in `MarketplaceSDKServiceProvider`.

### Facade
```php
    class MPEAuthentication extends Facade
    {
        protected static function getFacadeAccessor()
        {
            // return the alias
            return 'mpe-authentication';
        }
    }
```

### MarketplaceSDKServiceProvider
```php
    // bind the service to an alias
    $this->app->bind('mpe-authentication', function () {
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
