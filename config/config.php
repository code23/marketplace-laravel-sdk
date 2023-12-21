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
        'bucket' => env('AWS_BUCKET'),
        'region' => env('AWS_DEFAULT_REGION'),
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'url'    => env('AWS_URL'),
        'lamda_function_version' => env('AWS_LAMBDA_FUNCTION_VERSION'),
        'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        'endpoint' => env('AWS_ENDPOINT'),
        'version' => env('AWS_VERSION'),
        'signature_version' => env('AWS_SIGNATURE_VERSION'),
        'token' => env('AWS_SESSION_TOKEN'),
    ],

    'products' => [
        'recently_viewed_max' => env('RECENTLY_VIEWED_PRODUCTS_MAX', 4),
    ],

    'categories' => [
        'retrieval_rate' => env('CATEGORY_RETRIEVAL_RATE', 10), // deprecated
    ],

    'tags' => [
        'retrieval_rate' => env('TAG_RETRIEVAL_RATE', 10), // deprecated
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

        'charities' => [
            'key' => env('CHARITIES_CACHE_KEY', 'categories'),
            'minutes' => env('CHARITIES_CACHE_MINUTES', 10),
        ],

        'currencies' => [
            'key' => env('CURRENCIES_CACHE_KEY', 'currencies'),
            'minutes' => env('CURRENCIES_CACHE_MINUTES', 10),
        ],

        'modules' => [
            'key' => env('MODULES_CACHE_KEY', 'modules'),
            'minutes' => env('MODULES_CACHE_MINUTES', 10),
        ],

        'populated_categories' => [
            'key' => env('POPULATED_CATEGORIES_CACHE_KEY', 'populated_categories'),
            'minutes' => env('POPULATED_CATEGORIES_CACHE_MINUTES', 10),
        ],

        'specifications' => [
            'key' => env('SPECIFICATIONS_CACHE_KEY', 'specifications'),
            'minutes' => env('SPECIFICATIONS_CACHE_MINUTES', 10),
        ],

        'tags' => [
            'key' => env('TAGS_CACHE_KEY', 'tags'),
            'minutes' => env('TAGS_CACHE_MINUTES', 10),
        ],

        'vendors' => [
            'key' => env('VENDORS_CACHE_KEY', 'vendors'),
            'minutes' => env('VENDORS_CACHE_MINUTES', 10),
        ],

    ],
];
