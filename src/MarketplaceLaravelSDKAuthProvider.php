<?php

namespace Code23\MarketplaceLaravelSDK;

use Illuminate\Support\Facades\Auth as BaseAuth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Code23\MarketplaceLaravelSDK\Services\Auth\UserProviderService;

class MarketplaceLaravelSDKAuthProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->provider();
    }

    /**
     * @return void
     */
    protected function provider(): void
    {
        BaseAuth::provider('marketplace-sdk-custom', static function (): UserProviderService {
            return new UserProviderService();
        });
    }
}
