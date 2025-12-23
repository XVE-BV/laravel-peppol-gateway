<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Actions;

use Xve\LaravelPeppol\Events\CreditNoteStatusRetrieved;
use Xve\LaravelPeppol\Exceptions\InvoiceException;
use Xve\LaravelPeppol\Services\PeppolGatewayService;
use Xve\LaravelPeppol\Support\InvoiceStatus;

class GetCreditNoteStatusAction
{
    public function __construct(
        protected PeppolGatewayService $service,
    ) {}

    public function execute(string $id): InvoiceStatus
    {
        $response = $this->service->getCreditNoteStatus($id);

        if (($response['_status'] ?? null) === 404) {
            throw InvoiceException::notFound($id);
        }

        $status = InvoiceStatus::fromResponse($response);

        CreditNoteStatusRetrieved::dispatch($id, $status);

        return $status;
    }
}
