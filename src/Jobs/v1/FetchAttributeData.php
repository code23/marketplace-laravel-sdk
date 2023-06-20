<?php

namespace Code23\MarketplaceLaravelSDK\Jobs\v1;

use Code23\MarketplaceLaravelSDK\Facades\v1\MPEAttributes;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class FetchAttributeData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(
        $params = [
            'with' => 'values',
            'is_active' => 1,
        ],
        $oauth = null)
    {
        try {
            // get the categories from API
            $response = MPEAttributes::list($params, $oauth);

            // Save the data to a JSON file
            $filePath = storage_path('app/attributes.json');
            File::put($filePath, json_encode($response));

            // save data to cache
            Cache::put('attributes', $response);

            // return success
            return true;

        } catch (Exception $e) {
            Log::error($e);

            // return failure
            return false;
        }
    }
}
