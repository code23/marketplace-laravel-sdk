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

    'tags' => [
        'retrieval_rate' => env('TAG_RETRIEVAL_RATE', 10),
    ],

    'user' => [
        'email_verified_route_name' => env('EMAIL_VERIFIED_ROUTE_NAME', 'email-verified'),
    ],

    'cache' => [

        'attributes' => [
            'key' => env('ATTRIBUTES_CACHE_KEY', 'attributes'),
            'minutes' => env('ATTRIBUTES_CACHE_MINUTES', 10),
        ],

        'categories' => [
            'key' => env('CATEGORIES_CACHE_KEY', 'categories'),
            'minutes' => env('CATEGORIES_CACHE_MINUTES', 10),
        ],

        'currencies' => [
            'key' => env('CURRENCIES_CACHE_KEY', 'currencies'),
            'minutes' => env('CURRENCIES_CACHE_MINUTES', 10),
        ],

        'specifications' => [
            'key' => env('SPECIFICATIONS_CACHE_KEY', 'specifications'),
            'minutes' => env('SPECIFICATIONS_CACHE_MINUTES', 10),
        ],

        'vendors' => [
            'key' => env('VENDORS_CACHE_KEY', 'vendors'),
            'minutes' => env('VENDORS_CACHE_MINUTES', 10),
        ],

    ],
];
