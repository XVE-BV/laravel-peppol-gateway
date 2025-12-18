<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Contracts;

interface HasPeppolDocument
{
    /**
     * Check if this document can be sent to Peppol.
     */
    public function canSendToPeppol(): bool;

    /**
     * Check if this document was already sent to Peppol.
     */
    public function wasSentToPeppol(): bool;

    /**
     * Get the Peppol UUID assigned after sending.
     */
    public function getPeppolUuid(): ?string;

    /**
     * Get the current Peppol status.
     */
    public function getPeppolStatus(): ?string;

    /**
     * Build the data array for sending to the Peppol Gateway.
     *
     * @return array<string, mixed>
     */
    public function toPeppolArray(): array;
}
