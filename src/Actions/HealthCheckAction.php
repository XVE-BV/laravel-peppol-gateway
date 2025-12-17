<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Actions;

use Xve\LaravelPeppol\Events\HealthChecked;
use Xve\LaravelPeppol\Exceptions\ConnectionException;
use Xve\LaravelPeppol\Support\Config;
use Xve\LaravelPeppol\Support\HealthStatus;

class HealthCheckAction
{
    public function execute(): HealthStatus
    {
        try {
            $response = Config::httpClient()->get('/api/system/health');

            $status = HealthStatus::fromResponse($response->json());

            HealthChecked::dispatch($status);

            return $status;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw ConnectionException::unreachable();
        }
    }
}
