<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Actions;

use Xve\LaravelPeppol\Exceptions\InvoiceException;
use Xve\LaravelPeppol\Services\PeppolGatewayService;

class GetInvoiceXmlByNumberAction
{
    public function __construct(
        protected PeppolGatewayService $service,
    ) {}

    /**
     * Fetch the Peppol UBL XML for an invoice by its number.
     *
     * @param string $invoiceNumber The invoice number
     * @return string The raw XML content
     * @throws InvoiceException When the invoice is not found or fetch fails
     */
    public function execute(string $invoiceNumber): string
    {
        return $this->service->getInvoiceXmlByNumber($invoiceNumber);
    }
}
