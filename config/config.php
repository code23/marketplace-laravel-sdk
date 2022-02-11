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
        'keys' => [
            'id'        => env('MPE_API_KEY'),
            'secret'    => env('MPE_API_SECRET'),
        ],
        'pac_keys' => [
            'id'        => env('MPE_PAC_KEY'),
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
    ]
];
