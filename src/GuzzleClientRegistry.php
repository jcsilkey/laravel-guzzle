<?php
declare(strict_types=1);

namespace JCS\LaravelGuzzle;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;

class GuzzleClientRegistry
{
    protected $clients;

    protected $default;

    public function __construct(array $clientsConfiguration, string $default = 'default')
    {
        $this->clients = $clientsConfiguration;

        $this->default = $default;
    }

    public function getClient(?string $clientName = null) : ClientInterface
    {
        $clientName = $clientName ?: $this->default;

        if (isset($this->clients[$clientName]) && $this->clients[$clientName] instanceof Client) {
            return $this->clients[$clientName];
        } elseif (!isset($this->clients[$clientName])) {
            $client = new Client([]);

            $this->clients[$clientName] = $client;
        } else {
            $config = $this->createHandlerStack($this->clients[$clientName]);

            $client = new Client($config);

            $this->clients[$clientName] = $client;
        }

        return $client;
    }

    /**
     * Parses the client configuration and creates a handler stack to pass to the Client
     *
     * @param array $clientConfig the client configuration array
     *
     * @return array the client conguration array with the handler stack set
     */
    protected function createHandlerStack(array $clientConfig = [])
    {
        if (isset($clientConfig['handler'])) {
            $handler = new $clientConfig['handler'];
        } else {
            $handler = \GuzzleHttp\choose_handler();
        }

        if (isset($clientConfig['middleware']) && is_array($clientConfig['middleware'])) {
            $clientConfig['handler'] = new HandlerStack($handler);

            foreach($clientConfig['middleware'] as $middleware) {
                $clientConfig['handler']->push($this->makeMiddleware($middleware['callable']), $middleware['name']);
            }

            unset($clientConfig['middleware']);
        } else {
            $clientConfig['handler'] = HandlerStack::create($handler);
        }

        return $clientConfig;
    }

    /**
     * Takes a string and returns a Guzzle middleware function
     *
     * @param string $callable the middleware function
     *
     * @return callable the middleware function
     */
    protected function makeMiddleware(string $callable)
    {
        return call_user_func($callable);
    }

}
