# Code Examples

Detailed examples for each action with request and response data.

## Health Check

Verify API connectivity and service status.

```php
use Xve\LaravelPeppol\Actions\HealthCheckAction;
use Xve\LaravelPeppol\Support\Config;

$action = Config::getAction('health_check', HealthCheckAction::class);
$health = $action->execute();
```

**Response (`HealthStatus`):**

```php
$health->ok;              // bool: true
$health->status;          // int: 200
$health->baseUrl;         // string|null: "https://api.flowin.io"
$health->mtlsConfigured;  // bool|null: true
$health->error;           // string|null: null
```

## Lookup Participant

Check if a VAT number is registered on the Peppol network.

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `vat` | string | Yes | VAT number (with or without country prefix) |
| `country` | string | No | Country code hint (e.g. 'BE') |
| `forceRefresh` | bool | No | Bypass cache (default: false) |

```php
use Xve\LaravelPeppol\Actions\LookupParticipantAction;
use Xve\LaravelPeppol\Support\Config;

$action = Config::getAction('lookup_participant', LookupParticipantAction::class);

// Basic lookup
$participant = $action->execute('BE0123456789');

// With country hint
$participant = $action->execute('0123456789', 'BE');

// Force refresh (bypass cache)
$participant = $action->execute('BE0123456789', null, true);
```

**Response (`Participant`):**

```php
$participant->participantId;  // string: "9925:BE0123456789"
$participant->capable;        // bool: true
$participant->country;        // string|null: "BE"
$participant->name;           // string|null: "Acme Corporation"
```

## Send Invoice

Send an invoice to the Peppol network.

**Required:** `type`, `total`, `currency`

**Optional:** `id`, `issue_date`, `due_date`, `buyer_vat`, `buyer_peppol_id`, `buyer_reference`, `order_reference`, `lines`, `attachments`, and more.

```php
use Xve\LaravelPeppol\Actions\SendInvoiceAction;
use Xve\LaravelPeppol\Support\Config;

$action = Config::getAction('send_invoice', SendInvoiceAction::class);
$result = $action->execute([
    'type' => 'invoice',           // required
    'total' => 121.00,             // required
    'currency' => 'EUR',           // required
    'id' => 'INV-2025-001',
    'issue_date' => '2025-01-15',
    'due_date' => '2025-02-15',
    'buyer_vat' => 'BE0123456789',
    'lines' => [
        [
            'description' => 'Consulting services',
            'quantity' => 10,
            'unit_price' => 100.00,
            'vat_rate' => 21.00,
        ],
    ],
]);
```

**Response (`InvoiceResult`):**

```php
$result->status;  // string: "queued"
$result->uuid;    // string: "550e8400-e29b-41d4-a716-446655440000"
```

## Send Credit Note

Send a credit note to the Peppol network. Uses the same schema as invoices.

**Required:** `type`, `total`, `currency`

```php
use Xve\LaravelPeppol\Actions\SendCreditNoteAction;
use Xve\LaravelPeppol\Support\Config;

$action = Config::getAction('send_credit_note', SendCreditNoteAction::class);
$result = $action->execute([
    'type' => 'credit_note',             // required
    'total' => -121.00,                  // required
    'currency' => 'EUR',                 // required
    'id' => 'CN-2025-001',
    'issue_date' => '2025-01-20',
    'buyer_vat' => 'BE0123456789',
    'order_reference' => 'INV-2025-001', // link to original invoice
    'lines' => [
        [
            'description' => 'Consulting services - Refund',
            'quantity' => -1,
            'unit_price' => 100.00,
            'vat_rate' => 21.00,
        ],
    ],
]);
```

**Response (`InvoiceResult`):**

```php
$result->status;  // string: "queued"
$result->uuid;    // string: "660e8400-e29b-41d4-a716-446655440001"
```

## Get Invoice Status

Check the delivery status of a sent invoice or credit note.

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | string | Yes | Invoice UUID or numeric ID |

```php
use Xve\LaravelPeppol\Actions\GetInvoiceStatusAction;
use Xve\LaravelPeppol\Support\Config;

$action = Config::getAction('get_invoice_status', GetInvoiceStatusAction::class);

// By UUID
$status = $action->execute('550e8400-e29b-41d4-a716-446655440000');

// By numeric ID
$status = $action->execute('12345');
```

**Response (`InvoiceStatus`):**

```php
$status->id;         // int|null: 12345
$status->uuid;       // string|null: "550e8400-e29b-41d4-a716-446655440000"
$status->type;       // string|null: "invoice"
$status->status;     // string: "delivered"
$status->buyerVat;   // string|null: "BE0123456789"
$status->flowinId;   // string|null: "FLOWIN-ABC-123"
$status->total;      // string|null: "121.00"
$status->currency;   // string|null: "EUR"
```

**Possible status values:**

| Status | Description |
|--------|-------------|
| `draft` | Invoice created but not yet submitted |
| `validated` | Invoice validated and ready for submission |
| `submitted` | Invoice submitted to Peppol network |
| `delivered` | Invoice successfully delivered to recipient |
| `rejected` | Invoice rejected by recipient |
| `failed` | Invoice delivery failed |
| `expired` | Invoice expired before delivery |

## Error Handling

All actions throw specific exceptions:

```php
use Xve\LaravelPeppol\Actions\SendInvoiceAction;
use Xve\LaravelPeppol\Exceptions\AuthenticationException;
use Xve\LaravelPeppol\Exceptions\ConnectionException;
use Xve\LaravelPeppol\Exceptions\InvoiceException;
use Xve\LaravelPeppol\Exceptions\ValidationException;
use Xve\LaravelPeppol\Support\Config;

$action = Config::getAction('send_invoice', SendInvoiceAction::class);

try {
    $result = $action->execute($data);
} catch (AuthenticationException $e) {
    // Invalid or missing API credentials (401)
} catch (ValidationException $e) {
    // Invalid request data (422)
    $errors = $e->errors(); // ['field' => ['Error message']]
} catch (ConnectionException $e) {
    // Network timeout or unreachable API
} catch (InvoiceException $e) {
    // Invoice not found (404) or send failed
}
```

## Listening to Events

```php
use Xve\LaravelPeppol\Events\InvoiceSent;
use Xve\LaravelPeppol\Events\InvoiceStatusRetrieved;

// In EventServiceProvider or using Event::listen()
Event::listen(InvoiceSent::class, function (InvoiceSent $event) {
    Log::info('Invoice sent', [
        'uuid' => $event->result->uuid,
        'buyer_vat' => $event->data['buyer_vat'],
    ]);
});

Event::listen(InvoiceStatusRetrieved::class, function (InvoiceStatusRetrieved $event) {
    if ($event->status->status === 'delivered') {
        // Mark invoice as delivered in your system
    }
});
```
