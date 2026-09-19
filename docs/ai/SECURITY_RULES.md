# SECURITY_RULES.md

## Non-negotiable rules

Security is enforced by Laravel, database integrity, authorization, validation, audit and tests.

Frontend restrictions are UX, not security.

## Authentication

Approved architecture:
Vue 3 SPA <-> Laravel Sanctum <-> secure cookie-based session.

Do not replace Sanctum without an approved architecture change.

Do not disable CSRF or broaden CORS just to bypass an error.

Suspended/closed users must lose active access.

Shared staff accounts are forbidden.

## Authorization

Sensitive access may require:

authentication + permission + resource scope/ownership + valid business state.

Protect against horizontal access/IDOR for:
- clients;
- beneficiaries;
- requests;
- projects;
- documents;
- stored files;
- payments;
- receipts;
- notifications.

Nested resource IDs must be verified as belonging together.

## State transitions

Do not expose arbitrary status mutation.

Use controlled business actions for:
- account decisions;
- request decisions;
- project transitions;
- document validation;
- payment cancellation.

## Inputs

Treat browser input, Google Forms, Postman, query parameters, uploaded files
and external-service data as untrusted.

Validate server-side.

## Files

Private travel files must not be publicly exposed.

Secure flow:
authenticated request -> authorize -> resolve business resource -> resolve StoredFile -> return securely.

Do not expose storage paths in APIs.

Original filename is metadata only; generate server-side storage names.

Allowed initial formats:
PDF, JPG, JPEG, PNG.

Initial max:
10 MB, configurable.

Validate:
- extension;
- MIME;
- size;
- basic integrity;
- authorization;
- expected business context.

Do not overwrite old document submissions.

## Finance

Use DECIMAL(15,2), never FLOAT/DOUBLE.

Historical valid payment correction:
old payment -> CANCELLED
new payment -> created.

Do not expose standard delete endpoints for recorded payments.

Receipts and evidence are private.

## Audit

Raw AuditEvent is internal.
Client history uses ProjectTimelineEvent.

Do not allow normal business CRUD to edit/delete audit history.

Never log:
- passwords;
- tokens;
- secret keys;
- full private file content.

## Secrets

Never commit real `.env`, credentials, SMTP passwords, API tokens or APP_KEY.

`.env.example` contains only safe placeholders.

Reports must not print secrets or session cookies.

## Personal data

Prefer fictitious/anonymized data for tests and AI prompts.

Avoid sending real passports, bank statements, private financial data or confidential correspondence to AI agents unless explicitly necessary and approved.

## Environments

Use:
- LOCAL
- TEST
- PRODUCTION

Automated tests must never target production.

`migrate:fresh`, `db:wipe`, DROP/TRUNCATE are forbidden in production.

## Production

Without explicit human approval, an AI agent must not:
- deploy production;
- execute destructive migrations;
- delete production data/files;
- rotate secrets;
- change DNS;
- change global storage permissions;
- restore backups onto production.

## SQL / XSS / commands

Use parameterized Eloquent/query builder.

Never concatenate untrusted values into raw SQL or shell commands.

Avoid unsafe `v-html` with untrusted content.

## Rate limiting

Protect sensitive public/auth endpoints:
- login;
- reset password;
- signup;
- resend verification;
- public contact.

## Email

Do not attach private travel documents automatically.

Email failure must not rollback successful business operations.

## Queues / Scheduler

Design queued jobs and reminders to tolerate retries and avoid uncontrolled duplicate side effects.

## Tests

Critical negative security tests should include:
- Client A denied Client B resource;
- unauthorized staff permission denied;
- suspended user denied;
- internal-only file denied to client;
- receipt/document download protected.

## Escalation

If uncertain, report:

SECURITY CONCERN

Context:
Risk:
Current behavior:
Options:
Recommended safe option:

Never weaken a protection silently.
