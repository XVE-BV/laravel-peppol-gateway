# Laravel Peppol Gateway

[![Latest Version on Packagist](https://img.shields.io/packagist/v/xve/laravel-peppol-gateway.svg?style=flat-square)](https://packagist.org/packages/xve/laravel-peppol-gateway)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/xve/laravel-peppol-gateway/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/xve/laravel-peppol-gateway/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/xve/laravel-peppol-gateway.svg?style=flat-square)](https://packagist.org/packages/xve/laravel-peppol-gateway)

Laravel client package for the Peppol Gateway API. Uses the [Action Pattern](docs/action-pattern.md) for swappable, testable operations. See [Examples](docs/examples.md) for detailed usage.

## Installation

```bash
composer require xve/laravel-peppol-gateway
```

Add to your `.env`:

```env
PEPPOL_GATEWAY_URL=https://your-gateway-url.com
PEPPOL_GATEWAY_CLIENT_ID=your-client-id
PEPPOL_GATEWAY_CLIENT_SECRET=your-client-secret
```

## Usage

```php
use Xve\LaravelPeppol\Actions\SendInvoiceAction;
use Xve\LaravelPeppol\Support\Config;

$action = Config::getAction('send_invoice', SendInvoiceAction::class);
$result = $action->execute([
    'type' => 'invoice',
    'id' => 'INV-2025-001',
    'buyer_vat' => 'BE0123456789',
    'total' => 121.00,
    'currency' => 'EUR',
    // ...
]);

$result->status;  // "queued"
$result->uuid;    // "550e8400-e29b-41d4-a716-446655440000"
```

### Available Actions

| Action | Description |
|--------|-------------|
| `HealthCheckAction` | Verify API connectivity |
| `LookupParticipantAction` | Check if VAT is Peppol-capable |
| `SendInvoiceAction` | Send invoice (JSON) |
| `SendCreditNoteAction` | Send credit note (JSON) |
| `GetInvoiceStatusAction` | Check invoice/credit note status |

## Events

All actions dispatch events after successful execution:

| Action | Event |
|--------|-------|
| `HealthCheckAction` | `HealthChecked` |
| `LookupParticipantAction` | `ParticipantLookedUp` |
| `SendInvoiceAction` | `InvoiceSent` |
| `SendCreditNoteAction` | `CreditNoteSent` |
| `GetInvoiceStatusAction` | `InvoiceStatusRetrieved` |

## Customization

Actions can be swapped via config. See [Action Pattern](docs/action-pattern.md) for details.

```php
// config/peppol-gateway.php
'actions' => [
    'send_invoice' => \App\Actions\CustomSendInvoiceAction::class,
],
```

## Configuration

Publish the config file (optional):

```bash
php artisan vendor:publish --tag="peppol-gateway-config"
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
