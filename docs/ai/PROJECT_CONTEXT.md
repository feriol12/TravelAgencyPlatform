# PROJECT_CONTEXT.md

## Product

Web platform for a single travel agency to manage and track client travel procedures.

The platform manages:

- account applications;
- client accounts;
- travel requests;
- accepted travel projects;
- workflows;
- documents and versions;
- externally received payments;
- receipts;
- notifications;
- client timeline;
- internal audit.

It is not a booking engine, marketplace, price comparator, multi-agency SaaS,
online payment platform, full accounting system, or internal chat system.

## Core business chain

`Account -> Travel Request -> Travel Project`

A client has one account, but may have many requests and projects.

## Actors

- Visitor
- Client
- Director/Admin
- Secretary
- Assistant

Roles and permissions are separate. The system uses RBAC plus fine-grained permissions.

## Account model

Current access states:

- PENDING
- ACTIVE
- REJECTED
- SUSPENDED
- CLOSED

Application decisions are preserved separately through `AccountApplication`.

Email verification is independent from account status.

`User` = identity/authentication.
`ClientProfile` = client business profile.
`Beneficiary` = actual person concerned by a travel procedure.

## Travel requests

States:

- INITIATED
- SUBMITTED
- UNDER_REVIEW
- ACCEPTED
- REJECTED
- CANCELLED

Google Forms is a replaceable external intake channel, not the source of truth.
Use `ExternalRequestSubmission` to isolate external form submissions.

## Travel projects

States:

- DRAFT
- ACTIVE
- ON_HOLD
- COMPLETED
- CANCELLED
- ARCHIVED

Accepted requests create exactly one main project in `DRAFT`.

`DRAFT` is not automatically client-visible.

## Workflow

`TravelProject -> ProjectWorkflow -> WorkflowStep`

Step states:

- PENDING
- IN_PROGRESS
- COMPLETED
- BLOCKED
- SKIPPED

Multiple steps may be `IN_PROGRESS`.

Steps may be `CLIENT_VISIBLE` or `INTERNAL_ONLY`.

Progress is derived, not manually edited.

## Documents

`DocumentRequirement -> DocumentSubmission -> StoredFile`

Initial allowed formats:
PDF, JPG, JPEG, PNG

Initial max size:
10 MB, configurable.

Old versions are never overwritten.

Private files are served only after authentication + authorization.

## Finance

`TravelProject -> FinancialAgreement -> Payments`
`Payment -> Receipt`

No online payment in MVP.

Payments are recorded after receipt outside the platform.

Money uses `DECIMAL(15,2)` + ISO currency. Default currency: XOF.

Payment states:
- VALID
- CANCELLED

Correction = cancel old transaction + create new transaction.

Derived values:
- total paid
- remaining amount
- financial status

## Notifications

MVP channels:
- IN_APP
- EMAIL

Email failure does not roll back business operations.

## Audit

Internal history: `AuditEvent`
Client-facing history: `ProjectTimelineEvent`

Never expose raw audit entries to clients.

## Authentication / API

- Laravel Sanctum
- Vue 3 SPA
- `/api/v1`
- MySQL
- private storage
- Jobs/Queue
- Scheduler

## Frontend

- Vue 3
- Vite
- JavaScript
- Vue Router
- Pinia
- Axios

Layouts:
- PublicLayout
- ClientLayout
- AdminLayout

Centralize API calls under `services/`.

## Git / AI workflow

Long-lived:
- main
- develop

Working:
- feature/*
- fix/*
- refactor/*
- docs/*

Important tasks use:
- Human Owner
- AI Implementer
- AI Reviewer
- AI Tester

Workflow:
Implementation -> Review -> Testing -> Human Gate -> Merge

For exact current repository state, read `CURRENT_STATE.md`.
