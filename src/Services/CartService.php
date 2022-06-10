<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class CartService extends Service
{
    /**
     * Add a product to the cart
     *
     * @param Int $productId
     * @param Int $quantity
     * @param Int|null $variantId
     * @param Array|null $attributes
     * @return Collection
     */
    public function add(Int $productId, Int $quantity = 1, Int $variantId = null, Array $attributes = null)
    {
        // add to cart
        $response = $this->http()->patch($this->getPath() . '/cart/add/' . $productId, [
            'quantity' => $quantity,
            'variant_id' => $variantId ?? [],
            'attributes' => $attributes ?? [],
        ]);

        // dump($response);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to add to the cart.' . $response['message'], 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    public function applyCoupon($code, $groupId)
    {
        // send request
        $response = $this->http()->patch($this->getPath() . '/cart/apply-coupon/' . $code, [
            'cart_group_id'  => $groupId,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to apply the coupon.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    public function applyPromotion($id, $groupId)
    {
        // send request
        $response = $this->http()->patch($this->getPath() . '/cart/apply-promotion/' . $id, [
            'cart_group_id'  => $groupId,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to apply the promotion.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get promotions available on platform
     *
     * @param String $with - optional relationships to include, comma separated string
     *
     * @return collection
     */
    public function getPromotions(String $with = null)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/promotions', [
            'with' => $with,
            'valid_from' => now() . ',<=',
            'expiry' => now() . ',>=',
            'active' => 1,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to get the promotions.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    public function updateGiftOptions($groupId, $isGift, $message = null)
    {
        $data = [
            'cart_group_id' => $groupId,
            'is_gift' => $isGift,
        ];

        $message ? $data['gift_message'] = $message : null;

        // send request
        $response = $this->http()->patch($this->getPath() . '/cart/is-gift/', $data);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to update gift options.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Remove a product from the cart
     *
     * @param Int $productId
     * @param Int|null $variantId
     * @return Collection
     */
    public function remove(Int $productId, Int $variantId = null)
    {
        // send request
        $response = $this->http()->patch($this->getPath() . '/cart/remove/' . $productId, [
            'variant_id'  => $variantId,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to remove from the cart.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Retrieve cart for authenticated user
     *
     * @param string|null $with - comma separated relationship names to include
     * @return collection
     */
    public function get(string $with = null)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/cart');

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the cart.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();

    }

    /**
     * Retrieve cart by Id
     *
     * @param Int $id
     */
    public function getById(Int $id)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/cart/' . $id);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the cart.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();

    }

    /**
     * Update item quantity
     *
     * @param Int $id
     */
    public function updateQuantity(Int $productId, Int $variantId, Int $quantity)
    {
        // send request
        $response = $this->http()->patch($this->getPath() . '/cart/update-quantity/' . $productId, [
            'variant_id' => $variantId,
            'quantity' => $quantity,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to update cart product quantity.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();

    }

}
