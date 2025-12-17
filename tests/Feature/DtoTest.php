<?php

declare(strict_types=1);

use Xve\LaravelPeppol\Support\HealthStatus;
use Xve\LaravelPeppol\Support\InvoiceResult;
use Xve\LaravelPeppol\Support\InvoiceStatus;
use Xve\LaravelPeppol\Support\Participant;

describe('HealthStatus', function () {
    it('creates from response array', function () {
        $data = [
            'ok' => true,
            'status' => 200,
            'base_url' => 'https://api.example.com',
            'mtls_configured' => true,
        ];

        $health = HealthStatus::fromResponse($data);

        expect($health->ok)->toBeTrue()
            ->and($health->status)->toBe(200)
            ->and($health->baseUrl)->toBe('https://api.example.com')
            ->and($health->mtlsConfigured)->toBeTrue()
            ->and($health->error)->toBeNull();
    });

    it('handles error response', function () {
        $data = [
            'ok' => false,
            'status' => 502,
            'error' => 'Connection failed',
        ];

        $health = HealthStatus::fromResponse($data);

        expect($health->ok)->toBeFalse()
            ->and($health->status)->toBe(502)
            ->and($health->error)->toBe('Connection failed');
    });

    it('handles empty response', function () {
        $health = HealthStatus::fromResponse([]);

        expect($health->ok)->toBeFalse()
            ->and($health->status)->toBe(0);
    });
});

describe('Participant', function () {
    it('creates from response array', function () {
        $data = [
            'data' => [
                'id' => '8ea99b6a-c891-4f48-964e-208b49a19c93',
                'type' => 'peppolCustomerSearch',
                'attributes' => [
                    'customerReference' => '0208:0805374964',
                    'supportedDocumentFormats' => [
                        [
                            'rootNamespace' => 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2',
                            'localName' => 'Invoice',
                        ],
                    ],
                ],
            ],
        ];

        $participant = Participant::fromResponse($data);

        expect($participant->id)->toBe('8ea99b6a-c891-4f48-964e-208b49a19c93')
            ->and($participant->participantId)->toBe('0208:0805374964')
            ->and($participant->capable)->toBeTrue()
            ->and($participant->supportedDocumentFormats)->toHaveCount(1);
    });

    it('handles not capable participant', function () {
        $data = [
            'data' => [
                'id' => '8ea99b6a-c891-4f48-964e-208b49a19c93',
                'type' => 'peppolCustomerSearch',
                'attributes' => [
                    'customerReference' => '',
                    'supportedDocumentFormats' => [],
                ],
            ],
        ];

        $participant = Participant::fromResponse($data);

        expect($participant->capable)->toBeFalse()
            ->and($participant->supportedDocumentFormats)->toBe([]);
    });
});

describe('InvoiceResult', function () {
    it('creates from response array', function () {
        $data = [
            'status' => 'queued',
            'uuid' => '550e8400-e29b-41d4-a716-446655440000',
        ];

        $result = InvoiceResult::fromResponse($data);

        expect($result->status)->toBe('queued')
            ->and($result->uuid)->toBe('550e8400-e29b-41d4-a716-446655440000');
    });
});

describe('InvoiceStatus', function () {
    it('creates from response array with invoice wrapper', function () {
        $data = [
            'invoice' => [
                'id' => 1,
                'uuid' => '550e8400-e29b-41d4-a716-446655440000',
                'type' => 'invoice',
                'status' => 'delivered',
                'buyer_vat' => 'BE0123456789',
                'buyer_reference' => 'INV-001',
                'flowin_id' => 'FLOWIN-123',
                'total' => '121.00',
                'currency' => 'EUR',
                'created_at' => '2025-01-15T10:00:00Z',
                'updated_at' => '2025-01-15T12:00:00Z',
            ],
        ];

        $status = InvoiceStatus::fromResponse($data);

        expect($status->id)->toBe(1)
            ->and($status->uuid)->toBe('550e8400-e29b-41d4-a716-446655440000')
            ->and($status->type)->toBe('invoice')
            ->and($status->status)->toBe('delivered')
            ->and($status->buyerVat)->toBe('BE0123456789')
            ->and($status->flowinId)->toBe('FLOWIN-123')
            ->and($status->total)->toBe('121.00')
            ->and($status->currency)->toBe('EUR');
    });

    it('creates from flat response array', function () {
        $data = [
            'id' => 1,
            'uuid' => '550e8400-e29b-41d4-a716-446655440000',
            'type' => 'credit_note',
            'status' => 'rejected',
        ];

        $status = InvoiceStatus::fromResponse($data);

        expect($status->type)->toBe('credit_note')
            ->and($status->status)->toBe('rejected');
    });
});
