# Laravel Peppol Gateway

[![Tests](https://img.shields.io/github/actions/workflow/status/XVE-BV/laravel-peppol-gateway/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/XVE-BV/laravel-peppol-gateway/actions/workflows/tests.yml?query=branch%3Amain)

Laravel client package for the Peppol Gateway API. Uses the Action Pattern for swappable, testable operations.

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

Actions can be swapped via the `actions` key in `config/peppol-gateway.php`.

## Configuration

Publish the config file (optional):

```bash
php artisan vendor:publish --tag="peppol-gateway-config"
```

## Stubs

Publish the migration stubs (optional):

```bash
php artisan vendor:publish --tag="peppol-gateway-stubs"
```

This will publish the following stubs to `stubs/peppol-gateway/`:

- `add_peppol_fields_to_table.php.stub` - Migration for adding Peppol participant fields to a table
- `add_peppol_tracking_to_table.php.stub` - Migration for adding Peppol document tracking fields to a table

Use these stubs as templates for your own migrations. Replace `{{ table }}` and `{{ after_column }}` placeholders with your values.

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
