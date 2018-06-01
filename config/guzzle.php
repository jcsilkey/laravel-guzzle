<?php

return [
    'default' => env('GUZZLE_DEFAULT_CLIENT', 'default'),

    'clients' => [
        'default' => [
            'handler' => 'GuzzleHttp\Handler\CurlHandler',
            'middleware' => [
                [
                    'callable' => 'GuzzleHttp\Middleware::httpErrors',
                    'name' => 'http_errors'
                ],
            ],
        ],
    ]
];
