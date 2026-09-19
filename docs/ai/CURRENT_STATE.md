# CURRENT_STATE.md

## Project

Travel Agency Platform — single-agency travel-procedure management platform.

## Phase

Repository, backend foundation and frontend foundation are complete and merged into `develop`.

Validated foundations:
- repository baseline: DONE;
- backend Laravel/MySQL foundation: DONE;
- frontend Vue SPA foundation: DONE;
- two-developer collaboration protocol: VALIDATED.

No business module is implemented yet.

Functional design, architecture, data model and AI governance remain approved.

## Stable branches

Current verified remote state:

- `main`: `2e28452b813f887e534533eba282389cc69909e7`
- `develop`: `5d698ed086c7d82f2db232a1cdad9e2a5866b0e3`

Important history:

- baseline: `fa8aafc` — repository initialization;
- backend foundation merge into develop: `5e8570b`;
- frontend foundation implementation: `9e40e24`;
- frontend foundation merge into develop: `5d698ed`.

`main` currently contains the backend-foundation state through PR #2.
Frontend foundation is merged into `develop` but not into `main`.

## Approved stack

- Backend: Laravel JSON API, MySQL, Sanctum, Policies / Gates, Events / Jobs,
  Scheduler, private storage.
- Frontend: Vue 3, Vite, JavaScript, Vue Router, Pinia, Axios.
- API versioning: `/api/v1`.
- Single agency; no multi-tenancy.

## Verified local environment

Backend:
- PHP 8.3.30;
- Composer 2.10.2;
- Laravel Framework 13.32.0;
- MySQL Community Server 8.4.3;
- development database convention: `travel_agency_dev`;
- isolated test database convention:
  `travel_agency_test` or `travel_agency_test_<suffix>`.

Frontend:
- Node 24.19.0;
- npm 11.17.0;
- Vue 3.5.43;
- Vite 8.3.0;
- @vitejs/plugin-vue 6.0.9;
- Vue Router 4.6.4;
- Pinia 3.0.4;
- Axios 1.20.0;
- JavaScript only.

## Current implementation

### Backend

Laravel foundation is initialized and validated.

Current backend contains:
- Laravel scaffold;
- MySQL configuration;
- isolated test-database guard;
- initial framework migrations;
- no business-domain migrations yet;
- no business API yet;
- no Sanctum SPA configuration yet;
- no RBAC implementation yet.

Backend foundation quality was validated through:
- `php artisan test`;
- `composer validate --strict`;
- `composer audit`;
- `php vendor/bin/pint --test`;
- independent review;
- independent testing;
- Human Gate.

### Frontend

Vue SPA foundation is initialized and merged into `develop`.

Current frontend contains:
- Vue 3 + Vite;
- Vue Router;
- Pinia;
- centralized Axios service;
- `PublicLayout`;
- `ClientLayout`;
- `AdminLayout`;
- placeholder routes:
  - `/`;
  - `/client`;
  - `/admin`;
- `VITE_API_URL` environment configuration;
- no TypeScript;
- no authentication flow;
- no business store;
- no final product UI.

Frontend foundation quality was validated through:
- `npm install`;
- `npm run build`;
- `npm audit` with zero vulnerabilities;
- real Chrome headless runtime verification;
- direct refresh on `/`, `/client` and `/admin`;
- no runtime or console errors;
- no unexpected API calls;
- independent review;
- independent testing;
- Human Gate.

## Database migrations

Only Laravel scaffold migrations currently exist.

Approved domain entities are not implemented yet.

Future implementation follows the validated phases:

A. Identity  
B. Requests  
C. Projects  
D. Documents  
E. Finance  
F. Transversal

## API / Authentication

Approved architecture:
- Vue SPA;
- Laravel Sanctum;
- cookie-based first-party SPA authentication;
- API prefix `/api/v1`.

Current state:
- Sanctum SPA foundation not implemented yet;
- CORS/stateful-domain configuration for the SPA not implemented yet;
- login/logout/me API not implemented yet;
- RBAC not implemented yet.

These are planned business-foundation cards, not missing work from the completed repository foundations.

## Team collaboration

Human developers:

### DEV A

- Name: Fériol
- GitHub: `@feriol12`
- repository permission: ADMIN

Reserved local conventions:
- test database: `travel_agency_test_feriol`;
- backend port when separation is needed: `8000`;
- frontend port when separation is needed: `5173`.

### DEV B

- Name: Dimas Othentique
- GitHub: `@othentiquedimas-code`
- repository permission: WRITE
- Trello project access confirmed by the human team.

Reserved local conventions:
- test database: `travel_agency_test_dimas`;
- backend port when separation is needed: `8001`;
- frontend port when separation is needed: `5174`.

Collaboration rules:

- one active card = one human owner = one branch;
- each branch starts from a synchronized `develop`;
- use separate clones/worktrees;
- never share the same test database;
- feature PRs target `develop`;
- do not push feature implementation directly to `main` or `develop`;
- implementation, review and testing remain separate gates;
- Human Gate is required before important merges;
- use `docs/ai/HANDOFF.md` when unfinished work changes owner;
- GitHub + Trello + repository documentation are the shared project memory;
- private AI chat history is not a source of truth.

## Trello / Work management

The MVP backlog has been prepared in Trello.

Workflow:

`READY -> IN PROGRESS -> AI REVIEW -> TESTING -> HUMAN GATE -> MERGE -> DONE`

`BLOCKED` may be used at any stage.

Current planning state:
- foundation cards 00.0, 00.1 and 00.2: DONE;
- collaboration card 00.3: collaboration rules validated;
- business and transversal cards are maintained in BACKLOG until dependencies are satisfied.

No card must move to IN PROGRESS only because it exists in the backlog.
Its dependencies, owner, branch and base must first be verified.

## Planned first parallel wave

No business implementation has started yet.

Intended first parallel allocation:

### DEV A — Fériol

Card:
`01.0 — Sanctum SPA & API authentication foundation`

Planned branch:
`feature/sanctum-spa-foundation`

### DEV B — Dimas

Card:
`00.4 — Atomic reference generator (REQ / PRJ / REC)`

Planned branch:
`feature/reference-counter`

These cards were selected because their implementation areas are largely independent.

Before either card starts:
- synchronize local `develop` with `origin/develop`;
- verify the expected base SHA;
- use a separate clone/worktree;
- confirm the card is READY;
- read the mandatory repository documentation.

## Agent onboarding

Every AI agent or personal GPT assisting either developer must treat repository documentation as authoritative project memory.

Mandatory entry sequence:

1. `AGENTS.md`
2. `docs/ai/PROJECT_CONTEXT.md`
3. `docs/ai/CURRENT_STATE.md`
4. `docs/ai/SECURITY_RULES.md`
5. active Trello card
6. relevant functional/architecture/data documentation

For reviews:
- also read `docs/ai/REVIEW_PROTOCOL.md`.

For transfer of unfinished work:
- read and update `docs/ai/HANDOFF.md`.

An AI must not rely on private conversation history to infer a team decision that is absent from shared project sources.

## Quality status

Backend foundation:
PASS.

Frontend foundation:
PASS.

Known unresolved foundation blocker:
None.

Business implementation:
Not started.

Deployment:
Not configured or performed.

## Immediate next milestone

1. Sync this state document into `develop`.
2. Dimas clones/synchronizes the repository and performs GPT onboarding.
3. Verify collaboration card operational checks.
4. Move the first compatible cards to READY.
5. Start the first parallel development wave only after owners, branches and base SHAs are confirmed.
