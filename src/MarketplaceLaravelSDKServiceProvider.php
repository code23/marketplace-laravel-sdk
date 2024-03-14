<?php

namespace Code23\MarketplaceLaravelSDK;

use App\Models\User;

use Code23\MarketplaceLaravelSDK\View\Components\Layout;
use Code23\MarketplaceLaravelSDK\Console\InstallCommand;
use Code23\MarketplaceLaravelSDK\Console\MPECacheUpdate;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Spatie\SlackAlerts\Facades\SlackAlert;

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
                MPECacheUpdate::class,
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

        /**
         * failed jobs slack notification
         */
        Queue::failing(function (JobFailed $event) {
            if(config('boilerplate.slack.webhook_url')) {
                SlackAlert::message('*' . config('app.url') . '* ' . $event->job->resolveName() . ' failed: ' . $event->exception->getMessage());
            }
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'marketplace-laravel-sdk');
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
