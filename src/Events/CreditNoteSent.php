<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Xve\LaravelPeppol\Support\InvoiceResult;

class CreditNoteSent
{
    use Dispatchable;

    public function __construct(
        public readonly array $data,
        public readonly InvoiceResult $result,
    ) {}
}
