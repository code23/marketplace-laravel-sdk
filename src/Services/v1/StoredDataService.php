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
        // try for cached data
        if($cached = $this->retrieveCache($string)) return $cached;

        // or get from file (…storage/app/filename.json) and return as array
        if($file = $this->retrieveFile($string . '.json')) {
            return $file ? json_decode($file, true) : null;
        }

        // or return null
        return null;
    }

    private function retrieveCache(string $key) {
        // return cached data if available, as array
        if(Cache::has($key)) return Cache::get($key)->toArray();

        Log::error($key . ' not found in cache');
        return false;
    }

    private function retrieveFile(string $filename) {
        if(!$file = Storage::get($filename)) {
            Log::error($filename . ' not found in storage');
            return false;
        }
        // return file contents as array
        return $file;
    }
}
