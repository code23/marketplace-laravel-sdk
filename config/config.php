<?php

/*
 * You can place your custom package configuration in here.
 */
return [

    'api' => [
        'base_path' => env('MPE_API_BASE_PATH', 'mpe.test'),
        'keys'  => [
            'id'        => env('MPE_API_KEY'),
            'secret'    => env('MPE_API_SECRET'),
        ],
        'version'   => env('MPE_VERSION', 'v1'),
    ],

];
