<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;
use Illuminate\Http\Request;

class VendorService extends Service
{
    /**
     * Save a vendor!
     *
     * Front-end fields to include in request (not all are validated):
     * first_name
     * surname
     * telephone
     * email
     * password
     * password_confirmation
     * vendor_name
     * vendor_description
     * vendor_summary
     * address_line_1
     * address_line_2
     * address_city
     * address_postcode
     * country
     * image_1
     * image_2
     * image_3
     * image_4
     */
    public function save(Request $request)
    {
        $rules = [
            'first_name'         => 'required',
            'last_name'          => 'required',
            'phone'              => 'required',
            'email'              => 'required|email',
            'password'           => 'required|confirmed|min:8|regex:/[a-z]/|regex:/[A-Z]/',
            'store_name'         => 'required',
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
                        'line2'             => $request->line2,
                        'city'              => $request->city,
                        'county'            => $request->county,
                        'postcode'          => $request->postcode,
                    ],
                    'logo'                  => isset($request->logo) ? $request->logo : null,
                    'imagery'               => isset($request->imagery) ? $request->imagery : null,
                ]);

                // for testing
                // dd($response);

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
}
