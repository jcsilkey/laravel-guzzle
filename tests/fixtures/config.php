<?php

return [
    'default' => 'simple',
    'clients' => [
        'simple' => [],
        'handler_test' => [
            'handler' => 'GuzzleHttp\Handler\CurlHandler',
        ],
        'middleware_test' => [
            'middleware' => [
                [
                    'callable' => 'GuzzleHttp\Middleware::httpErrors',
                    'name' => 'http_errors'
                ],
            ],
        ],
        'handler_and_middleware_test' => [
            'handler' => 'GuzzleHttp\Handler\CurlHandler',
            'middleware' => [
                [
                    'callable' => 'GuzzleHttp\Middleware::httpErrors',
                    'name' => 'http_errors'
                ],
            ],
        ],
    ],
];
