<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StoredDataService extends Service
{
    /**
     * The main method to retrieve data from the cache or storage
     * @param string $string The name of the file / cache key to retrieve
     *
     * Files are stored in storage/app/filename.json by use of the fetch commands in Console,
     * called from frontend's app/Console/Kernel.php schedule() method.
     */
    public function retrieve($string) {
        // return cached data if available, as array
        if(Cache::has($string)) return Cache::get($string)->toArray();

        Log::error($string . ' not found in cache');

        // or get from file (…storage/app/filename.json) and return as array
        if($file = Storage::get($string . '.json')) return json_decode($file, true);

        Log::error($string . '.json not found in storage');

        // or return null
        return null;
    }
}
