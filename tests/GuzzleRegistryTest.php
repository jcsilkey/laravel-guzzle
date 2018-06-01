<?php
declare(strict_types=1);

namespace JCS\LaravelGuzzle;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class GuzzleRegistryTest extends TestCase
{
    public function testGetDefaultClient()
    {
        $config = require($this->getFixture('config.php'));

        $registry = new GuzzleClientRegistry($config['clients'], $config['default']);

        $client = $registry->getClient();

        $this->assertInstanceOf(ClientInterface::class, $client);
    }

    public function testGetClientWithDefaultGuzzleConfig()
    {
        $referenceClient = new Client([]);

        $referenceClientConfig = $referenceClient->getConfig();

        $config = require($this->getFixture('config.php'));

        $registry = new GuzzleClientRegistry($config['clients'], $config['default']);

        $client = $registry->getClient('simple');

        $clientConfig = $client->getConfig();

        //print_r($clientConfig);
        //echo PHP_EOL.(string)$clientConfig['handler'].PHP_EOL;

        $this->assertEquals($referenceClientConfig, $clientConfig);
    }

    public function testGetUnregisteredClientThrowsException()
    {
        $clientName = 'unregistered';

        $this->expectException(ClientNotRegisteredException::class);

        $this->expectExceptionMessage(sprintf("The client '%s' is not registered", $clientName));

        $config = require($this->getFixture('config.php'));

        $registry = new GuzzleClientRegistry($config['clients'], $config['default']);

        $client = $registry->getClient($clientName);
    }

    public function testGetClientReturnsSameInstance()
    {
        $config = require($this->getFixture('config.php'));

        $registry = new GuzzleClientRegistry($config['clients'], $config['default']);

        $client = $registry->getClient('simple');

        $client2 = $registry->getClient('simple');

        $this->assertEquals($client, $client2);
    }

    public function testGetClientWithCustomHandler()
    {
        $config = require($this->getFixture('config.php'));

        $registry = new GuzzleClientRegistry($config['clients'], $config['default']);

        $client = $registry->getClient('handler_test');

        $this->assertInstanceOf(ClientInterface::class, $client);
    }

    public function testGetClientWithCustomMiddleware()
    {
        $config = require($this->getFixture('config.php'));

        $registry = new GuzzleClientRegistry($config['clients'], $config['default']);

        $client = $registry->getClient('middleware_test');

        $this->assertInstanceOf(ClientInterface::class, $client);
    }

    public function testGetClientWithCustomHandlerAndMiddleware()
    {
        $config = require($this->getFixture('config.php'));

        $registry = new GuzzleClientRegistry($config['clients'], $config['default']);

        $client = $registry->getClient('handler_and_middleware_test');

        $this->assertInstanceOf(ClientInterface::class, $client);
    }
}
