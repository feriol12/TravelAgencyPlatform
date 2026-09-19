# REVIEW_PROTOCOL.md

## Purpose

A review is an independent verification of a specific implementation, not a summary.

Mandatory inputs:

- card
- branch
- base commit
- commit reviewed

Reviewer must read:
- `AGENTS.md`
- `PROJECT_CONTEXT.md`
- `CURRENT_STATE.md`
- `SECURITY_RULES.md`
- active card
- relevant functional/architecture docs

## Verdicts

- PASS
- PASS WITH NOTES
- CHANGES REQUIRED
- BLOCKED

## Severity

### CRITICAL
Blocks the card. Examples:
- cross-client private data access;
- auth bypass;
- privilege escalation;
- secret committed;
- destructive production risk;
- financial-history corruption;
- duplicate projects under concurrency.

### MAJOR
Significant correctness, architecture, security, or test issue that should be fixed before merge.

### MINOR
Low-risk improvement; may allow PASS WITH NOTES.

## Mandatory review areas

### Scope
- card objective satisfied;
- acceptance criteria satisfied;
- no unexplained out-of-scope changes.

### Backend
- server-side validation;
- authentication;
- permission;
- resource ownership;
- state transitions;
- transaction boundaries;
- concurrency;
- safe serialization;
- error handling;
- audit.

### Database
- correct types/nullability;
- unique constraints;
- FK;
- delete policy;
- indexes;
- rollback feasibility;
- migration classification: ADDITIVE / ALTERING / DESTRUCTIVE.

### Security
- no horizontal access;
- private files protected;
- no secret leakage;
- CSRF/CORS preserved;
- uploads validated;
- internal-only data protected.

### Domain-specific
Travel Request:
- valid lifecycle;
- accepted request not later rejected;
- one project max;
- concurrency-safe acceptance.

Travel Project:
- starts DRAFT;
- valid transitions;
- no automatic closure tied to documents/payments/workflow.

Documents:
- Requirement != Submission != StoredFile;
- old versions preserved;
- upload authorization;
- version concurrency considered.

Finance:
- DECIMAL, not FLOAT;
- cancellation keeps history;
- totals exclude CANCELLED;
- permissions and audit.

### Frontend
- loading/error/empty states;
- permission-aware UI;
- responsive;
- basic accessibility;
- API calls centralized;
- no critical business rule duplicated only in Vue.

### Tests
- meaningful negative tests;
- unauthorized/ownership rejection;
- invalid transitions;
- concurrency where required;
- security regressions.

## Finding format

Severity:
Location:
Problem:
Impact:
Expected behavior:
Recommended correction:

## Review report template

CARD:
BRANCH:
BASE:
COMMIT REVIEWED:

SCOPE REVIEWED:

VERDICT:
PASS | PASS WITH NOTES | CHANGES REQUIRED | BLOCKED

CRITICAL:
- None

MAJOR:
- None

MINOR:
- None

SPECIFICATION GAPS:
- None

OUT-OF-SCOPE CHANGES:
- None

SECURITY:
-

DATABASE / MIGRATIONS:
-

BACKEND:
-

FRONTEND:
-

TEST COVERAGE:
-

DOCUMENTATION:
-

COMMANDS / TESTS RUN BY REVIEWER:
-

RECOMMENDED NEXT ACTION:
-
