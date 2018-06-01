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

        $this->assertEquals($referenceClientConfig, $clientConfig);
    }

    public function testGetUnregisteredClientThrowsException()
    {
        $clientName = 'unregistered'

        $this->expectException(ClientNotRegisteredException::class);
        $this->expectExceptionMessage();

        $config = require($this->getFixture('config.php'));

        $registry = new GuzzleClientRegistry($config['clients'], $config['default']);

        $client = $registry->getClient($clientName);

        $this->assertInstanceOf(ClientInterface::class, $client);
    }

}
