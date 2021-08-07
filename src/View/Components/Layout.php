<?php

namespace Code23\MarketplaceLaravelSDK\View\Components;

use Illuminate\View\Component;

class Layout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('marketplace-laravel-sdk::layouts.guest');
    }
}
