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
        }

        if (!($this->clients[$clientName] instanceof ClientInterface)) {
            $this->clients[$clientName] = new Client($this->getConfigForClient($clientName));
        }

        return $this->clients[$clientName];
    }

    /**
     * Get the configuration for a client.
     *
     * Does a shallow merge of the default configuration options and client configuration
     *
     * @param string $clientName The name of the client to get configuration options for
     *
     * @return array The client configuration
     */
    protected function getConfigForClient(string $clientName) : array
    {
        $config = array_merge($this->defaultConfiguration, $this->clients[$clientName]);

        $handler = isset($config['handler']) ? $config['handler'] : null;

        $middlewares = isset($config['middleware']) && is_array($config['middleware']) ?
            $config['middleware'] :
            null;

        $config['handler'] = $this->createHandlerStack($handler, $middlewares);

        return $config;
    }

    /**
     * Create the client HandlerStack
     *
     * @param string|null $handler Class name of handler to use, or null to choose best fit
     * @param array|null $middleware an array of middleware to use, or null to use the default set
     *
     * @return GuzzleHttp\HandlerStack the client handler stack
     */
    protected function createHandlerStack(?string $handler, ?array $middlewares) : HandlerStack
    {
        if (is_null($handler)) {
            $handler = \GuzzleHttp\choose_handler();
        } else {
            $handler = new $handler;
        }

        if (is_null($middlewares)) {
            return HandlerStack::create($handler);
        }

        $handlerStack = new HandlerStack($handler);

        foreach($middlewares as $middleware) {
            $handlerStack->push($this->makeMiddleware($middleware['callable']), $middleware['name']);
        }

        return $handlerStack;
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
