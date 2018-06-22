<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Events and listeners
    |--------------------------------------------------------------------------
    |
    | List of supported event types with listeners configuration.
    |
    */
    'events' => [
        'query' => [
            'enabled' => env('QUERY_LOG_QUERIES', true),
            'channels' => ['stack'],
            'level' => 'debug',
        ],

        'transaction' => [
            'enabled' => env('QUERY_LOG_TRANSACTIONS', true),
            'channels' => ['stack'],
            'level' => 'debug',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default query formatter.
    |--------------------------------------------------------------------------
    |
    | Used to format query before logging.
    |
    */
    'formatter' => \ByZer0\QueryLog\Formatters\SubstituteBindingsFormatter::class,
];
