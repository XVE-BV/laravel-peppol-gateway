# Changelog

All notable changes to `laravel-peppol-gateway` will be documented in this file.

## v1.0.0 - Unreleased

### Added

- Health check action to verify API connectivity
- Participant lookup action to check Peppol network registration
- Send invoice action (JSON format)
- Send credit note action (JSON format)
- Get invoice status action
- DTOs for API responses (HealthStatus, Participant, InvoiceResult, InvoiceStatus)
- Custom exceptions with factory methods (AuthenticationException, ConnectionException, ValidationException, InvoiceException)
- Config helper class with HTTP client factory
- HasPeppolId trait for models
- InteractsWithPeppol interface
- Support for Laravel 10, 11, and 12
