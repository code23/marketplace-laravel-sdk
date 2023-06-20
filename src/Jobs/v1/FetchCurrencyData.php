<?php

namespace Code23\MarketplaceLaravelSDK\Jobs\v1;

use Code23\MarketplaceLaravelSDK\Facades\v1\MPECurrencies;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class FetchCurrencyData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(
        $params = [
            'is_enabled' => true,
        ],
        $oauth = null)
    {
        try {
            // get the categories from API
            $response = MPECurrencies::list($params, $oauth);

            $currencyData = $response->map(function ($currency) {
                return [
                    'id'         => $currency['id'],
                    'code'       => $currency['code'],
                    'symbol'     => $currency['symbol'],
                    'label'      => $currency['label'],
                    'is_default' => $currency['is_default'],
                ];
            });

            // Save the data to a JSON file
            $filePath = storage_path('app/currencies.json');
            File::put($filePath, json_encode($currencyData));

            // return success
            return true;

        } catch (Exception $e) {
            Log::error($e);

            // return failure
            return false;
        }
    }
}
