<?php

declare(strict_types=1);

use Xve\LaravelPeppol\Actions\LookupParticipantAction;
use Xve\LaravelPeppol\Support\Participant;

it('looks up participant from real API', function () {
    $action = app(LookupParticipantAction::class);
    $result = $action->execute('0458116944');

    expect($result)->toBeInstanceOf(Participant::class)
        ->and($result->participantId)->not->toBeEmpty()
        ->and($result->capable)->toBeTrue()
        ->and($result->supportedDocumentFormats)->not->toBeEmpty();
})->group('integration');
