<?php

declare(strict_types=1);

namespace Xve\LaravelPeppol\Contracts;

interface HasPeppolParticipant
{
    /**
     * Get the effective Peppol ID (custom or fetched).
     */
    public function getPeppolId(): ?string;

    /**
     * Check if this model has a Peppol ID.
     */
    public function hasPeppolId(): bool;

    /**
     * Check if Peppol is enabled for this model.
     */
    public function usesPeppol(): bool;

    /**
     * Check if a custom Peppol ID is set.
     */
    public function hasCustomPeppolId(): bool;
}
