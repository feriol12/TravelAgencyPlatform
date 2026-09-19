# Modèle de données logique — V0.1

## Status
VALIDATED

## Technical conventions

- Laravel BIGINT IDs.
- Business states stored as VARCHAR + PHP Backed Enums.
- Money: DECIMAL(15,2) + ISO currency.
- Email unique; phone not unique.
- Selective soft deletes.
- No normal deletion of requests, projects, payments, receipts, document submissions or audit history.
- Explicit FK delete policy: CASCADE / RESTRICT / SET NULL.
- Transactions and locking for concurrency-sensitive operations.

## Key uniqueness rules

- `users.email`
- `travel_requests.reference`
- `travel_projects.reference`
- `travel_projects.travel_request_id`
- `project_workflows.travel_project_id`
- `stored_files(disk,path)`
- `document_submissions(document_requirement_id,version_number)`
- `financial_agreements.travel_project_id`
- `receipts.reference`
- `receipts.payment_id`
- `legal_document_versions(document_type,version)`
- `user_consents(user_id,legal_document_version_id)`
- `reference_counters(prefix,year)`

## Concurrency-sensitive operations

- account application decision;
- travel request final decision;
- project creation from request;
- document version generation;
- payment cancellation;
- reference generation.

Use transactions and row locking such as `lockForUpdate()` where appropriate.

## Derived values

Do not manually edit:
- workflow progress;
- document progress;
- total paid;
- remaining amount;
- financial status.
