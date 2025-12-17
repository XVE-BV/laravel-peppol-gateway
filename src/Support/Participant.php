<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Support;

class Participant
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $participantId,
        public readonly bool $capable,
        public readonly array $supportedDocumentFormats = [],
    ) {}

    public static function fromResponse(array $response): self
    {
        $data = $response['data'] ?? $response;
        $attributes = $data['attributes'] ?? $data;

        $participantId = $attributes['customerReference'] ?? '';
        $supportedFormats = $attributes['supportedDocumentFormats'] ?? [];

        return new self(
            id: $data['id'] ?? null,
            participantId: $participantId,
            capable: ! empty($participantId),
            supportedDocumentFormats: $supportedFormats,
        );
    }
}
