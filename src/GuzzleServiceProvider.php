<?php
declare(strict_types=1);

namespace JCS\LaravelGuzzle;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class GuzzleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // boot...
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/guzzle.php', 'guzzle'
        );

        $this->app->singleton(GuzzleClientRegistry::class, function($app) {
            $config = $app->make('config');

            $default = $config->get('guzzle.default', 'default');

            $clients = $config->get('guzzle.clients', []);

            return new GuzzleClientRegistry($clients, $default);
        });

        $this->app->bind(ClientInterface::class, function($app) {
            $factory = $app->make(GuzzleClientRegistry::class);

            return $factory->getClient();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ClientInterface::class,
            GuzzleClientRegistry::class
        ];
    }
}
