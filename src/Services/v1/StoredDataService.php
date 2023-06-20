<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StoredDataService extends Service
{
    public function attributes() {
        if(!$file = Storage::get('attributes.json')) {
            Log::error('attributes.json not found in storage');
            return false;
        }
        // return file contents as array
        return json_decode($file, true);
    }

    public function categories() {
        if(!$file = Storage::get('categories.json')) {
            Log::error('categories.json not found in storage');
            return false;
        }
        // return file contents as array
        return json_decode($file, true);
    }

    public function currencies() {
        if(!$file = Storage::get('currencies.json')) {
            Log::error('currencies.json not found in storage');
            return false;
        }
        // return file contents as array
        return json_decode($file, true);
    }

    public function specifications() {
        if(!$file = Storage::get('specifications.json')) {
            Log::error('specifications.json not found in storage');
            return false;
        }
        // return file contents as array
        return json_decode($file, true);
    }
}
