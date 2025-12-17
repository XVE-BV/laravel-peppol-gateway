<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Xve\LaravelPeppol\Support\InvoiceStatus;

class InvoiceStatusRetrieved
{
    use Dispatchable;

    public function __construct(
        public readonly string $identifier,
        public readonly InvoiceStatus $status,
    ) {}
}
