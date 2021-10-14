<?php

namespace Code23\MarketplaceLaravelSDK\Http\Livewire\Traits;

use Illuminate\Support\MessageBag;

trait HasErrorHandler
{
    /**
     * Used to update a component's error bag with messages from an SDK validator
     */
    public function updateErrorBag(MessageBag $messageBag)
    {
        // get the error bag
        $errors = $this->getErrorBag();

        // loop over SDK's messages and add to component's error bag
        foreach ($messageBag->getMessages() as $key => $error) {
            foreach ($error as $message) {
                $errors->add($key, $message);
            }
        }
    }
}
