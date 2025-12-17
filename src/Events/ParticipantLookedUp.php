<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Xve\LaravelPeppol\Support\Participant;

class ParticipantLookedUp
{
    use Dispatchable;

    public function __construct(
        public readonly string $identifier,
        public readonly Participant $participant,
    ) {}
}
