<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Exceptions;

class InvalidActionClass extends PeppolGatewayException
{
    public static function make(string $actionName, string $expectedClass, string $actualClass): self
    {
        return new self(sprintf("Action '%s' must be an instance of %s, but %s was configured.", $actionName, $expectedClass, $actualClass));
    }
}
