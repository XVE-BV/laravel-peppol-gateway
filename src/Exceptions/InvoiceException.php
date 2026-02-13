<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Exceptions;

class InvoiceException extends PeppolGatewayException
{
    public static function notFound(string $id): self
    {
        return new self(sprintf("Invoice with ID '%s' was not found.", $id));
    }

    public static function sendFailed(string $reason): self
    {
        return new self('Failed to send invoice: '.$reason);
    }
}
