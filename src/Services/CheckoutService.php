<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class CheckoutService extends Service
{
    // public function addToCart(int $productId, string $variantCode, int $quantity)
    // {
    //     // api call
    //     $response = $this->http()->patch($this->getPath() . '/cart/add/' . $productId, [
    //         'variant'  => $variantCode,
    //         'quantity' => $quantity,
    //     ]);

    //     // call failed
    //     if ($response->failed()) throw new Exception('Error while adding product to cart', 422);

    //     // any other error
    //     if ($response['error']) throw new Exception($response['message'], $response['code']);

    //     return $response;
    // }
}
