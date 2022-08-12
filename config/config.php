<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'app' => [
        'url' => env('APP_URL'),
    ],

    'api' => [
        'base_path' => env('MPE_API_BASE_PATH', 'mpe.test'),
        'password_keys' => [
            'id'        => env('MPE_PASSWORD_KEY'),
            'secret'    => env('MPE_PASSWORD_SECRET'),
        ],
        'client_credential_keys' => [
            'id'        => env('MPE_CLIENT_CREDENTIAL_KEY'),
            'secret'    => env('MPE_CLIENT_CREDENTIAL_SECRET'),
        ],
        'version' => env('MPE_VERSION', 'v1'),
    ],

    'http' => [
        'origin' => env('MPE_ORIGIN'),
    ],

    'package' => [
        'name' => 'Marketplace Laravel SDK',
    ],

    'passwords' => [
        'rules' => 'required|confirmed|min:8|regex:/[a-z]/|regex:/[A-Z]/',
    ],

    's3' => [
        'signed_storage_url_expires_after' => env('SIGNED_STORAGE_URL_EXPIRES_AFTER', 15),
    ],

    'products' => [
        'recently_viewed_max' => env('RECENTLY_VIEWED_PRODUCTS_MAX', 4),
    ],

    'categories' => [
        'retrieval_rate' => env('CATEGORY_RETRIEVAL_RATE', 10),
    ],
];
