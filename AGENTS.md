# AGENTS.md

## Mandatory entry point

Every AI agent working on this repository MUST read this file first.

Then read:

- `docs/ai/PROJECT_CONTEXT.md`
- `docs/ai/CURRENT_STATE.md`
- `docs/ai/SECURITY_RULES.md`
- active Trello/card specification
- relevant functional and architecture documentation

## Source-of-truth priority

1. Explicit human validated decision
2. Approved functional specification
3. Approved architecture documentation
4. Approved data model / data dictionary
5. `AGENTS.md`
6. Active Trello/task specification
7. Feature-specific technical documentation
8. Existing code
9. Agent assumption

Never silently override a higher-priority source.

## Core product

This is a single-agency travel-procedure management platform.

Core flow:

`Account -> Travel Request -> Travel Project`

Around the project:

- Workflow
- Documents
- Financial tracking
- Notifications
- Client timeline
- Audit

The MVP is NOT a flight/hotel booking engine, marketplace, price comparator,
multi-agency SaaS, online payment platform, or complete accounting system.

## Approved architecture

- Vue 3 SPA + Vite
- Laravel JSON API
- Laravel Sanctum
- MySQL
- Private file storage
- Events / Jobs
- Scheduler
- API versioning `/api/v1`

Laravel is the source of truth for business rules and authorization.

## Security

Frontend visibility is not security.

Every sensitive operation requires server-side validation and authorization.

Never:
- commit secrets;
- expose private files through uncontrolled public URLs;
- disable CSRF/CORS to bypass an error;
- trust user-provided ownership;
- use real client data as test fixtures when fictitious data is sufficient;
- perform destructive production operations without explicit human approval.

## Core domain rules

### Travel Request
`INITIATED -> SUBMITTED -> UNDER_REVIEW -> ACCEPTED | REJECTED`

Cancellation may occur before acceptance.

An accepted request cannot later become rejected.

### Travel Project
`DRAFT -> ACTIVE <-> ON_HOLD -> COMPLETED -> ARCHIVED`

`DRAFT | ACTIVE | ON_HOLD -> CANCELLED`

Accepted request creates at most one project, initially `DRAFT`.

### Documents
`DocumentRequirement -> DocumentSubmission -> StoredFile`

Never overwrite an old document version.

### Finance
`TravelProject -> FinancialAgreement -> Payments`

`Payment -> Receipt`

Correction = cancel old payment + create new payment.

## Agent roles

Important cards define:

- Human Owner
- AI Implementer
- AI Reviewer
- AI Tester

Implementer does not approve its own work.

Reviewer normally does not modify implementation code.

Tester does not invent missing business rules.

## Workflow

`READY -> IN PROGRESS -> AI REVIEW -> TESTING -> HUMAN GATE -> MERGE -> DONE`

`BLOCKED` may be used at any stage.

Humans orchestrate transitions between agents unless explicit automation exists.

## Git

Long-lived branches:

- `main`
- `develop`

Working branches:

- `feature/*`
- `fix/*`
- `refactor/*`
- `docs/*`

Important features should normally merge to `develop` with `--no-ff`.

## Parallel work

Prefer Git Worktree, separate branches, isolated databases, and separate ports.

Recommended WIP: 1–2 active implementation cards per human developer.

## Escalation labels

When a rule is missing or a change is needed, use:

- `SPECIFICATION GAP`
- `ARCHITECTURE CHANGE REQUEST`
- `DEPENDENCY REQUEST`
- `OUT-OF-SCOPE FINDING`
- `SECURITY CONCERN`
- `BLOCKED`

Do not silently create a permanent rule.

## Definition of Done

A significant card is Done only after:

- implementation complete;
- tests added/updated;
- relevant tests pass;
- permissions/security verified;
- independent review complete;
- review findings resolved;
- tester verdict acceptable;
- human gate approved;
- merge complete;
- `CURRENT_STATE.md` updated when required.
