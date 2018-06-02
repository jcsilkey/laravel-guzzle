# Laravel Guzzle - Laravel Guzzle HTTP Client Manager

This package provides a mechanism to configure one or more Guzzle clients for use in an
application.

## Installation

Install with:

`composer require jcsilkey/laravel-guzzle`

With auto package discovery, the ServiceProvider is automatically registered.
No facade is or ever will be provided because facades are evil.

Publish the configuration:

`php artisan vendor:publish --tag="config"`

## Usage

This package registers 2 services:

### `GuzzleHttp\ClientInterface`
Resolves to the default client defined in the package configuration.

### `JCS\LaravelGuzzle\GuzzleClientRegistry`
Resolves to the client registry, which holds all clients. `GuzzleClientRegistry@getClient()` will return the default client. Pass a client name to get a specific client, `GuzzleClientRegistry@getClient($clientName)`.

## Configuration

The default configuration is minimal:

```
return [
    'default' => env('GUZZLE_DEFAULT_CLIENT', null),
    'global' => [],
    'clients' => []
    ]
];
```

### `default`
The name of the default client. If this value is `null`, then a client named 'default' will be created using the settings defined in `global`. If a name is given, then it must be a valid client defined in `clients`.

### `global`
Default configuration options to be used for all clients. Any valid Guzzle configuration option can be used.

### `clients`
An array of named clients, along with configuration options for each. For example:

```
    'clients' => [
        'example_api' => [
            'base_uri' => 'https://api.example.com'
        ],
        'example_api2' => [
            'base_uri' => 'https://api2.example.com'
        ],
    ]
```
Client options are merged with the global options defined above (shallow merge, top level keys only).

### Configuring Handlers and Middlewares
To define a custom Handler and Guzzle Middleware stack, use the normal guzzle `handler` option to specify a handler class and a special `middleware` option that is an array of middlewares to be added to your client. Each middleware definition is itself an array consisting of a `callable` and the `name` to register for the middleware. For example:

```
    `handler` => 'GuzzleHttp\Handler\CurlHandler',
    'middleware' => [
        [
            'callable' => 'GuzzleHttp\Middleware::httpErrors',
            'name' => 'http_errors'
        ],
    ]
```

If no `handler` is specified, one will be automatically selected for you based on your PHP configuration.

If no `middleware` are defined, your Guzzle client will be created using the default 
