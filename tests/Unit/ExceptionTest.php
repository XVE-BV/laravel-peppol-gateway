<?php

declare(strict_types=1);

use Xve\LaravelPeppol\Exceptions\AuthenticationException;
use Xve\LaravelPeppol\Exceptions\ConnectionException;
use Xve\LaravelPeppol\Exceptions\InvoiceException;
use Xve\LaravelPeppol\Exceptions\PeppolGatewayException;
use Xve\LaravelPeppol\Exceptions\ValidationException;

describe('PeppolGatewayException', function (): void {
    it('is base exception class', function (): void {
        $exception = new PeppolGatewayException('Test');

        expect($exception)->toBeInstanceOf(Exception::class);
    });
});

describe('AuthenticationException', function (): void {
    it('extends base exception', function (): void {
        $exception = AuthenticationException::invalidCredentials();

        expect($exception)->toBeInstanceOf(PeppolGatewayException::class);
    });

    it('creates invalid credentials exception', function (): void {
        $exception = AuthenticationException::invalidCredentials();

        expect($exception->getMessage())->toContain('Invalid API credentials');
    });

    it('creates missing credentials exception', function (): void {
        $exception = AuthenticationException::missingCredentials();

        expect($exception->getMessage())->toContain('Missing API credentials');
    });
});

describe('ConnectionException', function (): void {
    it('extends base exception', function (): void {
        $exception = ConnectionException::timeout();

        expect($exception)->toBeInstanceOf(PeppolGatewayException::class);
    });

    it('creates timeout exception', function (): void {
        $exception = ConnectionException::timeout();

        expect($exception->getMessage())->toContain('timed out');
    });

    it('creates unreachable exception', function (): void {
        $exception = ConnectionException::unreachable();

        expect($exception->getMessage())->toContain('Could not connect');
    });

    it('creates missing base url exception', function (): void {
        $exception = ConnectionException::missingBaseUrl();

        expect($exception->getMessage())->toContain('Missing API base URL');
    });
});

describe('ValidationException', function (): void {
    it('extends base exception', function (): void {
        $exception = ValidationException::fromResponse([]);

        expect($exception)->toBeInstanceOf(PeppolGatewayException::class);
    });

    it('creates from response with errors', function (): void {
        $errors = [
            'type' => ['The type field is required'],
            'total' => ['The total must be a number'],
        ];

        $exception = ValidationException::fromResponse($errors);

        expect($exception->errors())->toBe($errors)
            ->and($exception->getMessage())->toContain('Validation failed');
    });

    it('returns empty errors array when created with empty response', function (): void {
        $exception = ValidationException::fromResponse([]);

        expect($exception->errors())->toBe([]);
    });
});

describe('InvoiceException', function (): void {
    it('extends base exception', function (): void {
        $exception = InvoiceException::notFound('123');

        expect($exception)->toBeInstanceOf(PeppolGatewayException::class);
    });

    it('creates not found exception with id', function (): void {
        $exception = InvoiceException::notFound('abc-123');

        expect($exception->getMessage())->toBe("Invoice with ID 'abc-123' was not found.");
    });

    it('creates send failed exception with reason', function (): void {
        $exception = InvoiceException::sendFailed('Server error');

        expect($exception->getMessage())->toBe('Failed to send invoice: Server error');
    });
});
