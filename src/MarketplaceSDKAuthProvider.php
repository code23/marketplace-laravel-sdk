<?php

namespace Code23\MarketplaceSDK;

use Illuminate\Support\Facades\Auth as BaseAuth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Code23\MarketplaceSDK\Services\Auth\UserProviderService;

class MarketplaceSDKAuthProvider extends ServiceProvider
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
