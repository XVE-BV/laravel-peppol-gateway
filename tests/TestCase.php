<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Tests;

use Dotenv\Dotenv;
use Orchestra\Testbench\TestCase as Orchestra;
use Xve\LaravelPeppol\LaravelPeppolServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelPeppolServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $this->loadEnvFile();

        $app['config']->set('database.default', 'testing');
        $app['config']->set('peppol-gateway.base_url', env('PEPPOL_GATEWAY_URL', 'https://test-gateway.example.com'));
        $app['config']->set('peppol-gateway.client_id', env('PEPPOL_GATEWAY_CLIENT_ID', 'test-client-id'));
        $app['config']->set('peppol-gateway.client_secret', env('PEPPOL_GATEWAY_CLIENT_SECRET', 'test-client-secret'));
    }

    protected function loadEnvFile(): void
    {
        if (file_exists(__DIR__.'/../.env')) {
            Dotenv::createImmutable(__DIR__.'/..')->safeLoad();
        }
    }
}
