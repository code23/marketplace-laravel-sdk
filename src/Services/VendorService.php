<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Code23\MarketplaceLaravelSDK\Rules\UniqueUserEmailInTeam;
use Code23\MarketplaceLaravelSDK\Rules\UniqueVendorStoreName;
use Exception;

class VendorService extends Service
{
    /**
     * Follow a vendor
     *
     * @param Int $id
     *      Vendor ID to follow.
     *
     * @return Collection
     *      Update list of followed vendors.
     */
    public function follow(Int $id)
    {
        // call to api
        $response = $this->http()->patch($this->getPath() . '/vendors/' . $id . '/follow');

        // vendor not found
        if ($response->status() == 404) return $response;

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered while attempting to follow the vendor.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Retrieve a vendor by id
     *
     * @param Int $id
     *      Vendor id to retrieve.
     *
     * @param String $with
     *      Vendor relationships (comma separated) to include
     */
    public function get(Int $id, String $with = null)
    {
        // retrieve vendor
        $response = $this->http()->get($this->getPath() . '/vendors/' . $id, [
            'with' => $with,
            'is_active' => true,
        ]);

        // vendor not found
        if ($response->status() == 404) return $response;

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the vendor retrieval.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return the vendor
        return $response['data'];
    }

    /**
     * Retrieve a vendor by slug
     *
     * @param String $slug
     *      Vendor slug to retrieve.
     *
     * @param String $with
     *      Vendor relationships (comma separated) to include
     */
    public function getBySlug(String $slug, String $with = null)
    {
        // retrieve vendor
        $response = $this->http()->get($this->getPath() . '/vendors/slug/' . $slug, [
            'with' => $with,
            'is_active' => true,
        ]);

        // vendor not found
        if ($response->status() == 404) return $response;

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the vendor retrieval.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return the vendor
        return $response['data'];
    }

    /**
     * Retrieve all vendors
     * @param Int $with
     *      Vendor relationships (comma separated) to include
     * @param String $sort
     *     Sort vendors by field
     *
     * @return Collection of vendors
     */
    public function list($with = null, String $sort = null)
    {
        // retrieve vendors
        $response = $this->http()->get($this->getPath() . '/vendors', [
            'with' => $with,
            'sort' => $sort,
            'is_active' => true,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the vendors retrieval.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return the vendor
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Save a vendor
     */
    public function save(array $data)
    {
        $rules = [
            'first_name'        => 'required',
            'last_name'         => 'required',
            'phone'             => 'required',
            'email'             => ['required', 'email', new UniqueUserEmailInTeam],
            'password'          => config('marketplace-laravel-sdk.passwords.rules'),
            'store_name'        => ['required', new UniqueVendorStoreName],
            'line1'             => 'required',
            'city'              => 'required',
            'postcode'          => 'required',
            'country_id'        => 'required',
            'terms'             => 'required',
            'images.*.type'     => 'sometimes|in:image/jpeg,image/png',
            'images.*.size'     => 'sometimes|max:3145728',
            'images.*.response' => 'sometimes|array'
        ];

        $messages = [
            'password.regex'                => 'Password must include at least one upper & lowercase letter.',
            'images.*.type.in'              => 'Image must be either jpeg or png format.',
            'images.*.size.max'             => 'Image filesize too large - max 3MB.',
            // 'images.*.response.required'    => 'Payload must contain a response from the storage provider.'
        ];

        // use our validation method in Service.php
        $validated = $this->validator($data, $rules, $messages);

        // if validation passes
        if ($validated === true) {
            // send data
            $response = $this->http()->post($this->getPath() . '/vendors/register', [
                'first_name'             => $data['first_name'],
                'last_name'             => $data['last_name'],
                'email'                 => $data['email'],
                'phone'                 => $data['phone'],
                'password'              => $data['password'],
                'password_confirmation'  => $data['password_confirmation'],
                'terms'                 => $data['terms'] ? true : false,
                'store_name'            => $data['store_name'],
                'company_name'          => $data['company_name'] ?? null,
                'country_id'            => $data['country_id'],
                'vat'                   => isset($data['vat']) ? $data['vat'] : null,
                'meta'                  => isset($data['meta']) ? $data['meta'] : null,
                'address'               => [
                    'company'           => isset($data['company']) ? $data['company'] : null,
                    'line1'             => $data['line1'],
                    'line2'             => isset($data['line2']) ? $data['line2'] : null,
                    'city'              => $data['city'],
                    'county'            => $data['county'],
                    'postcode'          => $data['postcode'],
                ],
                'logo'                  => isset($data['logo']) ? $data['logo'] : null,
                'images'                => $data['images'],
                'commission_group_id'   => $data['commission_group_id'] ?? null,
            ]);

            // api call failed
            if ($response->failed()) throw new Exception('A problem was encountered during the request to create a new user & vendor.', 422);

            // any other error
            if ($response['error']) throw new Exception($response['message'], $response['code']);

            return true;
        } else {
            // perform laravel validation failed behaviour
            return $validated;
        }
    }

    /**
     * Check vendor name is unique
     *
     * @param String $name
     *      The name to check for uniqueness within the tenant
     *
     * @return boolean
     */
    public function storeNameIsUnique($name)
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/vendors/is-store-name-unique', [
            'store_name' => $name,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the request to check vendor name is unique.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // true/false
        return $response;
    }

    /**
     * Unfollow a vendor
     *
     * @param Int $id
     *      Vendor ID to unfollow.
     *
     * @return Collection
     *      Updated list of followed vendors.
     */
    public function unfollow(Int $id)
    {
        // call to api
        $response = $this->http()->patch($this->getPath() . '/vendors/' . $id . '/unfollow');

        // vendor not found
        if ($response->status() == 404) throw new Exception('Vendor not found.', 404);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered while attempting to unfollow the vendor.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return updated followed list as collection
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Retrieve all vendors that are within their radius from a given postcode
     */
    public function listByPostcode($postcode, $with = null, $sort = null)
    {
        // remove any spaces from the postcode
        $postcode = str_replace(' ', '', $postcode);

        // call to api
        $response = $this->http()->get($this->getPath() . '/vendors/postcode/' . $postcode, [
            'with' => $with,
            'sort' => $sort,
            'is_active' => true,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the request to retrieve vendors within a radius.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return the vendors
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }
}
