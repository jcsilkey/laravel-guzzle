<?php
declare(strict_types=1);

namespace JCS\LaravelGuzzle;

use GuzzleHttp\ClientInterface;

class GuzzleServiceProviderTest extends TestCase
{
    public function testProvides()
    {
        $provider = $this->app->getProvider(GuzzleServiceProvider::class);

        $this->assertContains(ClientInterface::class, $provider->provides());
        $this->assertContains(GuzzleClientRegistry::class, $provider->provides());
    }

    public function testMakeDefaultClient()
    {
        $config = require($this->getFixture('config.php'));

        $this->app->make('config')->set(
            'guzzle',
            $config
        );

        $client = $this->app->make(ClientInterface::class);

        $this->assertInstanceOf(ClientInterface::class, $client);
    }
}
