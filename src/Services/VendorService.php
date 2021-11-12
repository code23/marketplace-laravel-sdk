<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Code23\MarketplaceLaravelSDK\Facades\MPEImages;
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
     * Retrieve a vendor by slug
     *
     * @param String $slug
     *      Vendor slug to retrieve.
     */
    public function getBySlug(String $slug)
    {
        // retrieve vendor
        $response = $this->http()->get($this->getPath() . '/vendors/slug/' . $slug );

        // vendor not found
        if ($response->status() == 404) return $response;

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the vendor retrieval.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return the vendor
        return $response;
    }

    /**
     * Retrieve all vendors
     *
     * @return Collection of vendors
     */
    public function list()
    {
        // retrieve vendors
        $response = $this->http()->get($this->getPath() . '/vendors');

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the vendors retrieval.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return the vendor
        return collect($response['data']);
    }

    /**
     * Save a vendor
     */
    public function save(Array $data)
    {
        $rules = [
            'first_name'         => 'required',
            'last_name'          => 'required',
            'phone'              => 'required',
            'email'              => ['required', 'email', new UniqueUserEmailInTeam],
            'password'           => config('marketplace-laravel-sdk.passwords.rules'),
            'store_name'         => ['required', new UniqueVendorStoreName],
            'summary'            => 'required',
            'line1'              => 'required',
            'city'               => 'required',
            'county'             => 'required',
            'postcode'           => 'required',
            'country_id'         => 'required',
            'terms'              => 'required',
            'image_1'            => 'required|file|max:3072|mimes:jpeg,png',
            'image_2'            => 'sometimes|nullable|file|max:3072|mimes:jpeg,png',
            'image_3'            => 'sometimes|nullable|file|max:3072|mimes:jpeg,png',
            'image_4'            => 'sometimes|nullable|file|max:3072|mimes:jpeg,png',
        ];

        $messages = [
            'password.regex' => 'Password must include at least one upper & lowercase letter.',
            'image_1.mimes'  => 'Image 1 must be either jpeg or png format.',
            'image_1.max'    => 'Image 1 filesize too large - max 3MB.',
            'image_2.mimes'  => 'Image 2 must be either jpeg or png format.',
            'image_2.max'    => 'Image 2 filesize too large - max 3MB.',
            'image_3.mimes'  => 'Image 3 must be either jpeg or png format.',
            'image_3.max'    => 'Image 3 filesize too large - max 3MB.',
            'image_4.mimes'  => 'Image 4 must be either jpeg or png format.',
            'image_4.max'    => 'Image 4 filesize too large - max 3MB.',
        ];

        // use our validation method in Service.php
        $validated = $this->validator($data, $rules, $messages);

        // if validation passes
        if($validated === true) {

            try {

                // create imagery array
                $imagery = [];

                // set index
                $i = 1;

                // loop over number of images
                while ($i <= 4) {

                    // set field name
                    $image = 'image_' . $i;

                    // if field present in data
                    if ($data[$image]) {
                        // add data uri with mime type & base64 encoded image to array
                        $imagery[] = ['file' => MPEImages::prepareImageObject($data[$image])];
                    }

                    // increment index counter
                    $i++;
                }

                // send data
                $response = $this->http()->post($this->getPath() . '/vendors/register', [
                    'first_name'            => $data['first_name'],
                    'last_name'             => $data['last_name'],
                    'email'                 => $data['email'],
                    'phone'                 => $data['phone'],
                    'password'              => $data['password'],
                    'password_confirmation' => $data['password_confirmation'],
                    'terms'                 => $data['terms'] ? true : false,
                    'store_name'            => $data['store_name'],
                    'country_id'            => $data['country_id'],
                    'vat'                   => isset($data['vat']) ? $data['vat'] : null,
                    'meta'                  => [
                        'website'           => isset($data['website']) ? $data['website'] : null,
                        'facebook'          => isset($data['facebook']) ? $data['facebook'] : null,
                        'instagram'         => isset($data['instagram']) ? $data['instagram'] : null,
                        'description'       => isset($data['description']) ? $data['description'] : null,
                        'summary'           => isset($data['summary']) ? $data['summary'] : null,
                        'sku_quantity'      => isset($data['sku_quantity']) ? $data['sku_quantity'] : null,
                        'referee'           => isset($data['referee']) ? $data['referee'] : null,
                    ],
                    'address'               => [
                        'company'           => isset($data['company']) ? $data['company'] : null,
                        'line1'             => $data['line1'],
                        'line2'             => isset($data['line2']) ? $data['line2'] : null,
                        'city'              => $data['city'],
                        'county'            => $data['county'],
                        'postcode'          => $data['postcode'],
                    ],
                    'logo'                  => isset($data['logo']) ? $data['logo'] : null,
                    'imagery'               => $imagery,
                ]);

                // api call failed
                if ($response->failed()) throw new Exception('A problem was encountered during the request to create a new user & vendor.', 422);

                // any other error
                if ($response['error']) throw new Exception($response['message'], $response['code']);

                return true;

            } catch (Exception $e) {

                // return exception
                return $e;

            }

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
}
