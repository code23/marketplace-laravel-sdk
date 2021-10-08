<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;
use Illuminate\Http\Request;

class ReferenceValuesService extends Service
{
    /**
     * Lookup a reference value by category
     */
    public function byCategory($category)
    {
        try {

            // call to api
            $response = $this->http()->get($this->getPath() . '/reference-values/lookup', [
                'category' => $category,
            ]);

            // dd($response);

            // failed
            if ($response->failed()) throw new Exception('A problem was encountered during the reference value lookup.', 422);

            // process error
            if ($response['error']) throw new Exception($response['message'], $response['code']);

            return $response;

        } catch (Exception $e) {

            return $e->getMessage();

        }
    }
}
