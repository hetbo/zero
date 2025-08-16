<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Pagination
    |--------------------------------------------------------------------------
    |
    | This value is the default number of items returned per page when
    | paginating carrots.
    |
    */
    'pagination' => [
        'per_page' => 15,
        'max_per_page' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure caching for carrot operations.
    |
    */
    'cache' => [
        'enabled' => env('ZERO_CACHE_ENABLED', false),
        'ttl' => env('ZERO_CACHE_TTL', 3600), // 1 hour
        'prefix' => 'zero:',
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    |
    | Default validation rules for carrot operations.
    |
    */
    'validation' => [
        'name' => [
            'max_length' => 255,
            'min_length' => 1,
        ],
        'length' => [
            'min' => 1,
            'max' => 999999,
        ],
        'role' => [
            'max_length' => 255,
        ],
    ],
];