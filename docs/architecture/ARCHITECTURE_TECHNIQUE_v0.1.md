# Architecture technique & organisation — V0.1

## Status
VALIDATED

## Core architecture

Vue 3 SPA -> Laravel JSON API -> MySQL / Private Storage / Queue / Scheduler / Email / Audit

Repository:
- `backend/`
- `frontend/`
- `docs/`
- `AGENTS.md`
- `README.md`

Backend responsibilities:
authentication, authorization, business rules, API, validation, private files,
notifications, audit, jobs.

Frontend:
Vue 3 + Vite + JavaScript + Vue Router + Pinia + Axios.

API:
`/api/v1`

Authentication:
Laravel Sanctum.

Authorization:
Laravel Policies/Gates + fine-grained permissions.

Database:
MySQL.

## Key validated architectural decisions

1. Monorepo `backend/`, `frontend/`, `docs/`.
2. Vue 3 SPA + Laravel API.
3. Sanctum for first-party SPA auth.
4. MySQL.
5. Shared `users` table for clients and staff.
6. RBAC + fine-grained permissions.
7. Versioned API `/api/v1`.
8. Private files outside uncontrolled public storage.
9. `DocumentRequirement -> DocumentSubmission`.
10. Events + Jobs for notifications/emails.
11. Scheduler for reminders/deadlines.
12. Pinia only for truly shared state.
13. Centralized frontend API services.
14. Public / Client / Admin layouts.
15. Git main + develop + branches per card.
16. Human owner + AI implementer + AI reviewer per important card.
17. `AGENTS.md` as common agent entry point.
18. Provider-specific agent files remain lightweight.
19. Definition of Ready + Definition of Done mandatory.
20. Human gate for production, architecture, security and destructive actions.

## Architectural principles

- Laravel is the business source of truth.
- Vue does not own critical security logic.
- Google Forms is replaceable.
- Private files are served only after authorization.
- Account / Request / Project remain separate.
- Documents / Workflow / Payments remain independent.
- Sensitive actions are auditable.
- Secondary work may be asynchronous.
- AI agents use common documentation.
- No major architecture decision may be changed silently.
