<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Actions;

use Xve\LaravelPeppol\Events\InvoiceStatusRetrieved;
use Xve\LaravelPeppol\Exceptions\AuthenticationException;
use Xve\LaravelPeppol\Exceptions\ConnectionException;
use Xve\LaravelPeppol\Exceptions\InvoiceException;
use Xve\LaravelPeppol\Support\Config;
use Xve\LaravelPeppol\Support\InvoiceStatus;

class GetInvoiceStatusAction
{
    public function execute(string $id): InvoiceStatus
    {
        try {
            $response = Config::httpClient()->get("/api/invoices/{$id}");

            if ($response->status() === 401) {
                throw AuthenticationException::invalidCredentials();
            }

            if ($response->status() === 404) {
                throw InvoiceException::notFound($id);
            }

            $status = InvoiceStatus::fromResponse($response->json());

            InvoiceStatusRetrieved::dispatch($id, $status);

            return $status;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw ConnectionException::unreachable();
        }
    }
}
