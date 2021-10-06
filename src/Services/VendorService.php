<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Code23\MarketplaceLaravelSDK\Rules\UniqueVendorStoreName;
use Exception;
use Illuminate\Http\Request;

class VendorService extends Service
{
    /**
     * Save a vendor
     */
    public function save(Request $request)
    {
        $rules = [
            'first_name'         => 'required',
            'last_name'          => 'required',
            'phone'              => 'required',
            'email'              => 'required|email',
            'password'           => 'required|confirmed|min:8|regex:/[a-z]/|regex:/[A-Z]/',
            'store_name'         => ['required', new UniqueVendorStoreName],
            'description'        => 'required',
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
            'image_1.max'    => 'Image 1 filesizse too large - max 3MB.',
            'image_2.mimes'  => 'Image 2 must be either jpeg or png format.',
            'image_2.max'    => 'Image 2 filesizse too large - max 3MB.',
            'image_3.mimes'  => 'Image 3 must be either jpeg or png format.',
            'image_3.max'    => 'Image 3 filesizse too large - max 3MB.',
            'image_4.mimes'  => 'Image 4 must be either jpeg or png format.',
            'image_4.max'    => 'Image 4 filesizse too large - max 3MB.',
        ];

        // use our validation method in Service
        $validated = $this->validator($request, $rules, $messages);

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

                    // if field present in request
                    if (isset($request->$image)) {
                        // add data uri with mime type & base64 encoded image to array
                        $imagery[] = ['file' => 'data:' . $request->file($image)->getMimeType() . ';base64,' . base64_encode(file_get_contents($request->file($image)))];
                    }

                    // increment index counter
                    $i++;
                }

                // send request
                $response = $this->http()->post($this->getPath() . '/tenant/vendors/register', [
                    'first_name'            => $request->first_name,
                    'last_name'             => $request->last_name,
                    'email'                 => $request->email,
                    'phone'                 => $request->phone,
                    'password'              => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                    'terms'                 => $request->terms ? true : false,
                    'store_name'            => $request->store_name,
                    'country_id'            => $request->country_id,
                    'vat'                   => isset($request->vat) ? $request->vat : null,
                    'meta'                  => [
                        'website'           => isset($request->website) ? $request->website : null,
                        'facebook'          => isset($request->facebook) ? $request->facebook : null,
                        'instagram'         => isset($request->instagram) ? $request->instagram : null,
                        'description'       => isset($request->description) ? $request->description : null,
                        'summary'           => isset($request->summary) ? $request->summary : null,
                        'sku_quantity'      => isset($request->sku_quantity) ? $request->sku_quantity : null,
                        'referee'           => isset($request->referee) ? $request->referee : null,
                    ],
                    'address'               => [
                        'company'           => isset($request->company) ? $request->company : null,
                        'line1'             => $request->line1,
                        'line2'             => isset($request->line2) ? $request->line2 : null,
                        'city'              => $request->city,
                        'county'            => $request->county,
                        'postcode'          => $request->postcode,
                    ],
                    'logo'                  => isset($request->logo) ? $request->logo : null,
                    'imagery'               => $imagery,
                ]);

                // failed
                if ($response->failed()) throw new Exception('A problem was encountered during the request to create a new vendor.', 422);

                // process error
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
     * @return boolean
     */
    public function storeNameIsUnique($name)
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/tenant/vendors/is-store-name-unique', [
            'store_name' => $name,
        ]);

        // failed
        if ($response->failed()) throw new Exception('A problem was encountered during the request to check vendor name unique.', 422);

        // process error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }
}
