# Laravel Peppol Gateway - Implementation Plan

Client package for the Peppol Gateway API (Flowin integration).

## Package Structure

```
src/
├── Actions/                    # Core API operations
├── Exceptions/                 # Custom exceptions
├── Support/                    # Helper utilities
├── Models/
│   └── Concerns/               # Traits and interfaces
└── LaravelPeppolServiceProvider.php
```

## Implementation Checklist

### Config (`config/peppol-gateway.php`)
- [ ] API connection settings (base_url, timeout)
- [ ] Authentication credentials (client_id, client_secret)
- [ ] Swappable action classes
- [ ] Model class overrides

### Support Classes (`src/Support/`)
- [ ] `Config` helper class
  - [ ] `getActionClass()` - resolve configurable actions
  - [ ] Validation of configuration values
  - [ ] Fail-fast on invalid config

### Actions (`src/Actions/`)
- [ ] `HealthCheckAction` - `GET /api/system/health`
- [ ] `LookupParticipantAction` - `POST /api/peppol/lookup`
- [ ] `SendInvoiceAction` - `POST /api/invoices/json`
- [ ] `SendCreditNoteAction` - `POST /api/credit-notes/json`
- [ ] `GetInvoiceStatusAction` - `GET /api/invoices/{id}`

Each action:
- [ ] Single `execute()` method
- [ ] Type-hinted parameters and return types
- [ ] Uses Config helper for HTTP client
- [ ] Returns DTO or throws exception

### Exceptions (`src/Exceptions/`)
- [ ] `PeppolGatewayException` (base)
- [ ] `AuthenticationException`
  - [ ] `::invalidCredentials()`
  - [ ] `::missingCredentials()`
- [ ] `ConnectionException`
  - [ ] `::timeout()`
  - [ ] `::unreachable()`
- [ ] `ValidationException`
  - [ ] `::fromResponse(array $errors)`
- [ ] `InvoiceException`
  - [ ] `::notFound(string $id)`
  - [ ] `::sendFailed(string $reason)`

### DTOs (`src/Support/`)
- [ ] `HealthStatus` - health check response
- [ ] `Participant` - lookup response
- [ ] `InvoiceResult` - send invoice response
- [ ] `InvoiceStatus` - status check response

### Traits & Interfaces (`src/Models/Concerns/`)
- [ ] `HasPeppolId` interface
  - [ ] `getPeppolParticipantId(): ?string`
  - [ ] `setPeppolParticipantId(string $id): void`
- [ ] `InteractsWithPeppol` trait
  - [ ] Default implementation of interface

### Service Provider
- [ ] Register config file
- [ ] Bind actions to container
- [ ] Register HTTP client singleton

### Authentication
- [ ] `X-Api-Client-Id` header (UUID)
- [ ] `Authorization: Bearer {secret}` header

### Testing
- [ ] `TestCase` with proper setup
- [ ] Mock HTTP responses
- [ ] Test each action
- [ ] Architecture tests (no dd/dump/ray)

## Out of Scope
- Logic of saving when last was fetched
- Logic when to refetch per customer/client
- Mapping BTW fields (done in PGA)
- Saving history
- Logic to fetch status from PGA

## API Endpoints Reference

| Method | Endpoint | Action |
|--------|----------|--------|
| GET | `/api/system/health` | HealthCheckAction |
| POST | `/api/peppol/lookup` | LookupParticipantAction |
| POST | `/api/invoices/json` | SendInvoiceAction |
| POST | `/api/credit-notes/json` | SendCreditNoteAction |
| GET | `/api/invoices/{id}` | GetInvoiceStatusAction |
