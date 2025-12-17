<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Xve\LaravelPeppol\Support\HealthStatus;

class HealthChecked
{
    use Dispatchable;

    public function __construct(
        public readonly HealthStatus $status,
    ) {}
}
