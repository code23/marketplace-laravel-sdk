<?php

namespace Code23\MarketplaceLaravelSDK;

use App\Models\User;

use App\View\Components\GuestLayout;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Code23\MarketplaceLaravelSDK\Services\BlogService;
use Code23\MarketplaceLaravelSDK\Services\UserService;
use Code23\MarketplaceLaravelSDK\Services\ImageService;
use Code23\MarketplaceLaravelSDK\Services\OrderService;
use Code23\MarketplaceLaravelSDK\Console\InstallCommand;
use Code23\MarketplaceLaravelSDK\Services\ReturnService;
use Code23\MarketplaceLaravelSDK\Services\ReviewService;
use Code23\MarketplaceLaravelSDK\Services\VendorService;
use Code23\MarketplaceLaravelSDK\View\Components\Layout;
use Code23\MarketplaceLaravelSDK\Services\AddressService;
use Code23\MarketplaceLaravelSDK\Services\ProductService;
use Code23\MarketplaceLaravelSDK\Services\CategoryService;
use Code23\MarketplaceLaravelSDK\Services\CheckoutService;
use Code23\MarketplaceLaravelSDK\Services\PaymentMethodService;
use Code23\MarketplaceLaravelSDK\Services\AuthenticationService;
use Code23\MarketplaceLaravelSDK\Services\ReferenceValuesService;

class MarketplaceLaravelSDKServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * load package assets
         */
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'marketplace-laravel-sdk');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            // config
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('marketplace-laravel-sdk.php'),
            ], 'marketplace-laravel-sdk-config');

            // publish the controllers
            $this->publishes([
                __DIR__.'/../src/Http/Controllers' => app_path('Http/Controllers'),
            ], 'marketplace-laravel-sdk-controllers');

            // publish the middleware
            $this->publishes([
                __DIR__.'/../src/Http/Middleware' => app_path('Http/Middleware'),
            ], 'marketplace-laravel-sdk-middleware');

            // publish the user model
            $this->publishes([
                __DIR__.'/../src/Models' => app_path('Models'),
            ], 'marketplace-laravel-sdk-models');

            // Publishing rules.
            $this->publishes([
                __DIR__ . '/../src/Rules' => app_path('Rules'),
            ], 'marketplace-laravel-sdk-rules');

            // publish the authentication views
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/marketplace-laravel-sdk'),
            ], 'marketplace-laravel-sdk-views');

            // Publishing view components.
            $this->publishes([
                __DIR__.'/../src/View/Components' => app_path('View/Components'),
            ], 'marketplace-laravel-sdk-view-components');

            // Publishing livewire traits.
            $this->publishes([
                __DIR__.'/../src/Http/Livewire/Traits' => app_path('Http/Livewire/Traits'),
            ], 'marketplace-laravel-sdk-livewire-traits');

            // registering package commands
            $this->commands([
                InstallCommand::class,
            ]);
        }

        /**
         * custom singletons
         */
        $this->singletons();

        /*
         * load view components if they do not already exist
         */
        if (!class_exists(GuestLayout::class)) {
            $this->loadViewComponentsAs('guest', [
                Layout::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'marketplace-laravel-sdk');

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-authentication', function () {
            return new AuthenticationService();
        });

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-user', function () {
            return new UserService();
        });

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-vendors', function () {
            return new VendorService();
        });

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-products', function () {
            return new ProductService();
        });

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-categories', function () {
            return new CategoryService();
        });

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-reference-values', function () {
            return new ReferenceValuesService();
        });

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-images', function () {
            return new ImageService();
        });

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-addresses', function () {
            return new AddressService();
        });

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-reviews', function () {
            return new ReviewService();
        });

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-checkout', function () {
            return new CheckoutService();
        });

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-blog', function () {
            return new BlogService();
        });

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-payment-methods', function () {
            return new PaymentMethodService();
        });

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-orders', function () {
            return new OrderService();
        });

        // bind the service to an alias
        $this->app->bind('marketplace-laravel-sdk-returns', function () {
            return new ReturnService();
        });
    }

    /**
     * customer singletons
     */
    protected function singletons()
    {
        $this->app->bind('user', static function (): ?User {
            return Auth::user();
        });

        $this->app->bind('token', static function (): ?string {
            return session('token');
        });
    }
}
