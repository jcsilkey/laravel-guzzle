<?php

return [
    'default' => env('GUZZLE_DEFAULT_CLIENT', null),

    'defaults' => [],
    /**
     * Define configuration for clients
     */
    'clients' => [
        'default' => [
            /**
             * Choose a specific handler class to use
             */
            //'handler' => 'GuzzleHttp\Handler\CurlHandler',
            /**
             * Define a cusomt middleware stack
             *
             * callable: the middleware function to call
             * name: the name to register the middleware as
             */
            /*
            'middleware' => [
                [
                    'callable' => 'GuzzleHttp\Middleware::httpErrors',
                    'name' => 'http_errors'
                ],
            ],
             */
        ],
    ]
];
