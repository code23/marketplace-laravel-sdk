<?php

namespace Code23\MarketplaceLaravelSDK\Jobs\v1;

use Code23\MarketplaceLaravelSDK\Facades\v1\MPECategories;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class FetchCategoryData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(
        $params = [
            'with' => 'images,active_children_categories.images',
            'is_null' => 'top_id',
            'is_active' => true,
        ],
        $oauth = null)
    {
        try {
            // get the categories from API
            $response = MPECategories::list($params, $oauth);

            // Save the data to a JSON file
            $filePath = storage_path('app/categories.json');
            File::put($filePath, json_encode($response));

            // save data to cache
            Cache::put('categories', $response);

            // return success
            return true;

        } catch (Exception $e) {
            Log::error($e);

            // return failure
            return false;
        }
    }
}
