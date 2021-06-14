# Marketplace SDK

![GitHub Actions](https://github.com/code23/marketplace-sdk/actions/workflows/main.yml/badge.svg)

Marketplace SDK provides an easy interface to the Code23 Marketplace Engine API.  Designed and developed for use with Laravel, it will reduce the 
work involved when integrating with the Markeplace Engine api from a new front-end application.

Authentication is handled by installing the necessary views and components in the same way as Laravel Breeze but without the need for a database.

## Installation

You can install the package via composer:

```bash
composer require code23/marketplace-sdk
```

Once the package is included in your project you can install the config, views and components by running the following install command:
```bash
php artisan marketplace-sdk:install
```

Note:  If you don't want to install all of the resources included with Marketplace SDK you can install them individually:

```bash
php artisan vendor:publish --tag=mpe-config
php artisan vendor:publish --tag=mpe-views
php artisan vendor:publish --tag=mpe-view-components
```

## Usage

```php
//
```

### Testing

```bash
composer test
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
