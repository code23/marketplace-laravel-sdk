<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StoredDataService extends Service
{
    private function retrieve(string $filename) {
        if(!$file = Storage::get($filename)) {
            Log::error($filename . ' not found in storage');
            return false;
        }
        // return file contents as array
        return $file;
    }

    public function attributes() {
        $file = $this->retrieve('attributes.json');
        // return file contents as array (true)
        return $file ? json_decode($file, true) : null;
    }

    public function categories() {
        $file = $this->retrieve('categories.json');
        // return file contents as array (true)
        return $file ? json_decode($file, true) : null;
    }

    public function currencies() {
        $file = $this->retrieve('currencies.json');
        // return file contents as array (true)
        return $file ? json_decode($file, true) : null;
    }

    public function specifications() {
        $file = $this->retrieve('specifications.json');
        // return file contents as array (true)
        return $file ? json_decode($file, true) : null;
    }

    // TODO
    // public function vendors() {
    //     $file = $this->retrieve('vendors.json');
    //     // return file contents as array (true)
    //     return $file ? json_decode($file, true) : null;
    // }
}
