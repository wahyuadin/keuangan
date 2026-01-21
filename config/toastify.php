<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Toastify CDN links
    |--------------------------------------------------------------------------
    |
    | Here you may specify the CDN links for the toastify library.
    |
    */

    'cdn' => [
        'js' => 'https://unpkg.com/toastify-js@1.12.0/src/toastify.js',
        'css' => 'https://unpkg.com/toastify-js@1.12.0/src/toastify.css',
    ],

    'toastifiers' => [
        'toast' => [
            'style' => [
                'color' => '#fff',
                'background' => '#182433',
            ],
        ],
        'error' => [
            'duration' => 1500,
            'style' => [
                'color' => '#fff',
                'background' => '#d63939',
                'borderRadius' => '0.5rem',
                'boxShadow' => '0 0 10px rgba(214, 57, 57, 0.5)',
            ],
            'close' => true,
        ],
        'success' => [
            'close' => false,
            'stopOnFocus' => true,
            'duration' => 2000,
            'style' => [
                'color' => '#fff',
                'background' => 'linear-gradient(to right, #00b09b, #96c93d)',
                'borderRadius' => '0.5rem',
            ],
            'close' => true,
        ],
        'info' => [
            'style' => [
                'color' => '#fff',
                'background' => '#4299e1',
            ],
        ],
        'warning' => [
            'style' => [
                'color' => '#fff',
                'background' => '#f76707',
            ],
        ],
        'custom' => [
            'close' => true,
            'stopOnFocus' => true,
            'position' => 'center',
            'duration' => 10000,
            'style' => [
                'background' => '#4299e1',
                'color' => '#fff',
                'borderRadius' => '0.5rem',
            ],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Toastify Default Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default options for the toastify library.
    |
    */

    'defaults' => [
        'gravity' => 'toastify-top',
        'position' => 'right',
    ],
];
