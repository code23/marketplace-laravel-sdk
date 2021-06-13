<?php

namespace Code23\MarketplaceSDK;

use App\View\Components\GuestLayout;

use Code23\MarketplaceSDK\View\Components\Layout;
use Code23\MarketplaceSDK\Services\AuthenticationService;
use Code23\MarketplaceSDK\Services\RegistrationService;
use Code23\MarketplaceSDK\Services\UserService;
use Code23\MarketplaceSDK\Console\InstallCommand;
use Illuminate\Support\ServiceProvider;

class MarketplaceSDKServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * load package assets
         */
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'marketplace-sdk');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        /*
         * load view components if they do not already exist
         */
        if (!class_exists(GuestLayout::class)) {
            $this->loadViewComponentsAs('guest', [
                Layout::class,
            ]);
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('marketplace-sdk.php'),
            ], 'mpe-config');

            // publish the form validation requests
            $this->publishes([
                __DIR__.'/../src/Http/Requests' => app_path('Http/Requests'),
            ], 'mpe-requests');

            // publish the authentication views
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/marketplace-sdk'),
            ], 'mpe-views');

            // Publishing view components.
            $this->publishes([
                __DIR__.'/../src/View/Components' => app_path('View/Components'),
            ], 'mpe-view-components');

            // registering package commands
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'marketplace-sdk');

        // bind the service to an alias
        $this->app->bind('mpe-authentication', function () {
            return new AuthenticationService();
        });

        // bind the service to an alias
        $this->app->bind('mpe-registration', function () {
            return new RegistrationService();
        });

        // bind the service to an alias
        $this->app->bind('mpe-user', function () {
            return new UserService();
        });
    }
}
