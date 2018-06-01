<?php
declare(strict_types=1);

namespace JCS\LaravelGuzzle;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [GuzzleServiceProvider::class];
    }

    protected function getFixture(string $fixture)
    {
        return __DIR__ . '/fixtures/' . $fixture;
    }
}
