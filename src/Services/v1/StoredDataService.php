<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StoredDataService extends Service
{
    public function categories() {
        if(!$file = Storage::get('categories.json')) {
            Log::error('Categories file not found in storage');
            return false;
        }
        // true to return an array instead of an object
        return json_decode($file, true);
    }

    public function attributes() {
        if(!$file = Storage::get('attributes.json')) {
            Log::error('Attributes file not found in storage');
            return false;
        }
        // true to return an array instead of an object
        return json_decode($file, true);
    }
}
