<?php
declare(strict_types=1);

namespace JCS\LaravelGuzzle;

use GuzzleHttp\ClientInterface;

class GuzzleServiceProviderTest extends TestCase
{
    public function testMakeDefaultClient()
    {
        /*
        $this->app->make('config')->set(
            'guzzle.clients.default',
            [
                'base_uri' => 'http://127.0.0.1',
            ]
        );
         */

        $client = $this->app->make(ClientInterface::class);

        $this->assertInstanceOf(ClientInterface::class, $client);
    }
}
