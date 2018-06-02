<?php
declare(strict_types=1);

namespace JCS\LaravelGuzzle;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;

class GuzzleClientRegistry
{
    /**
     * Collection of clients/client configurations
     *
     * @var (GuzzleHttp\ClientInterface|array)[]
     */
    protected $clients;

    /**
     * Default configuration for all clients
     *
     * @var array
     */
    protected $defaultConfiguration;

    /**
     * Name of the default client
     *
     * @var string|null
     */
    protected $defaultClientName;

    /**
     * Constructor
     *
     * @param array $clientsConfiguration Configuration options for clients
     * @param array $defaultConfiguration Default configuration options for all clients
     * @param string|null $defaultClientName The name of the default client
     */
    public function __construct(
        array $clientsConfiguration,
        array $defaultConfiguration = [],
        ?string $defaultClientName = null
    ) {
        $this->clients = $clientsConfiguration;

        $this->defaultConfiguration = $defaultConfiguration;

        if (is_null($defaultClientName)) {
            $defaultClientName = 'default';

            $this->clients[$defaultClientName] = [];
        }

        $this->defaultClientName = $defaultClientName;
    }

    /**
     * Get a client from the registry
     *
     * If no client name is passed, then the default client will be returned.
     * The same client will be returned on subsequent requests.
     *
     * @param string|null $clientName The name of the client to get
     *
     * @return GuzzleHttp\ClientInterface The client requested
     * @throws ClientNotRegisteredException If the requested client is not registered
     */
    public function getClient(?string $clientName = null) : ClientInterface
    {
        $clientName = $clientName ?: $this->defaultClientName;

        if (!isset($this->clients[$clientName])) {
            throw new ClientNotRegisteredException($clientName);
        } elseif ($this->clients[$clientName] instanceof ClientInterface) {
            return $this->clients[$clientName];
        } else {
            $config = $this->getConfigForClient($clientName);

            $client = new Client($config);

            $this->clients[$clientName] = $client;
        }

        return $client;
    }

    protected function getConfigForClient(string $clientName)
    {
        $config = array_merge($this->defaultConfiguration, $this->clients[$clientName]);

        return $this->createHandlerStack($config);
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
