# CURRENT_STATE.md

## Project

Travel Agency Platform — single-agency travel-procedure management platform.

## Phase

Backend foundation validated and merged into develop at 5e8570b (human gate approved).
Frontend foundation implemented on `feature/frontend-foundation`, not merged.
Frontend independent review, tester verdict and human gate remain pending.
Functional design, architecture, data model and AI governance remain approved.

## Stable branches

Verified local baseline:
- main (local): fa8aafc; origin/main: 2e28452 (not modified by this task).
- develop and origin/develop: 5e8570bb50fe30b3b5aed93eb77b767e8e84d910
  at the start of the frontend card; merge: complete backend foundation.
- frontend foundation base: develop / 5e8570b.

## Approved stack

- Backend: Laravel JSON API, MySQL, Sanctum, Policies / Gates, Events / Jobs,
  Scheduler, private storage.
- Frontend: Vue 3, Vite, JavaScript, Vue Router, Pinia, Axios.
- API versioning: `/api/v1`.
- Single agency; no multi-tenancy.

## Verified local environment

- PHP 8.3.30; PDO and pdo_mysql available.
- Composer 2.10.2.
- Laravel Framework 13.32.0; existing scaffold retained.
- MySQL Community Server 8.4.3 at 127.0.0.1:3306.
- Development database: travel_agency_dev (utf8mb4_unicode_ci).
- Test database: travel_agency_test (utf8mb4_unicode_ci).
- Development MySQL connection works; three initial scaffold migrations applied.
- SQLite scaffold database removed after verifying no application rows.
- Local secrets remain in ignored backend/.env; .env.example has no real secrets.

Frontend:
- Node 24.19.0; npm 11.17.0 (npm.cmd on Windows PowerShell).
- Vue 3.5.43; Vite 8.3.0; @vitejs/plugin-vue 6.0.9.
- Vue Router 4.6.4; Pinia 3.0.4; Axios 1.20.0.
- Versions resolved in frontend/package-lock.json; JavaScript application only.

## Current implementation

BACKEND:
Initialized. Scaffold welcome route and health endpoint; no business implementation.
Local start: `cd backend`, then `php artisan serve --host=127.0.0.1 --port=8000`.

FRONTEND:
Vue SPA foundation initialized. PublicLayout, ClientLayout and AdminLayout each
render a lazy placeholder view through Vue Router at /, /client and /admin.
Pinia initialized without business stores. Axios centralized under services/api.js,
with VITE_API_URL, credentials and XSRF support; no automatic API/CSRF request.
These placeholder routes are unsecured; no authentication, business UI or final design.
Local env example: VITE_API_URL=http://localhost:8000 (public browser configuration).
node_modules, dist and local .env are ignored. Backend unchanged by this card.

DATABASE MIGRATIONS:
Only initial scaffold users/password reset/sessions, cache and jobs migrations.
Approved domain model is not implemented yet.

API / AUTHENTICATION:
Business API, Sanctum SPA, CORS configuration and RBAC not implemented.

TEST SUITE:
`php artisan test`: 3 tests, 7 assertions passing.
Two original scaffold tests plus a real MySQL `SELECT DATABASE()` isolation test.
PHPUnit defaults to 127.0.0.1:3306/travel_agency_test; host, port and database can
be overridden by worktree/CI process variables. Credentials remain in .env.
Allowed database names: travel_agency_test or travel_agency_test_<suffix>
(non-empty ASCII letters, digits or underscores). DB_URL is neutralized.
TestCase rejects cached or unsafe configuration before test migration traits run.
Inherited development DB_DATABASE is rejected by the guard before test migrations.
Alternative travel_agency_test_worktree2 configuration reaches MySQL, which reports
unknown database (not provisioned locally); no alternate database was created.
Cached configuration remains forbidden. Current tests perform no writes;
the default test database has no tables yet.

BACKEND QUALITY (verified during backend foundation):
`composer validate --strict`: valid.
`composer audit`: no security vulnerability advisories found.
`php vendor/bin/pint --test`: passes.
No dependency upgrades or additions.

FRONTEND QUALITY:
`npm install`: passed after retrying a transient network ECONNRESET.
`npm run build`: passed; `npm audit`: 0 vulnerabilities.
Lint: not configured in current foundation; no test framework installed.
`npm run dev`: started at http://localhost:5173; all three URLs returned HTTP 200
with the SPA entry point. Temporary server stopped after verification.
Actual Vue layouts, lazy views and router rendered successfully in a temporary
Node SSR smoke check using memory history; Pinia and Axios configuration checked.
Browser DOM/runtime verification remains pending: no connected browser available.
SSR/history adaptation existed only in the temporary verification harness, not app code.

DEPLOYMENT:
Not configured or performed.

## Active task

Human-provided frontend foundation specification (this implementation session).
Branch: feature/frontend-foundation.
Role: AI Implementer. Independent reviewer/tester not yet assigned in repository.
Implementation does not constitute approval or completion of the human gate.

## Notes and next milestone

- Backend agent instructions now reference root governance and prohibit automatic
  dependency installation without explicit task/human approval.
- Backend setup and verification commands: backend/README.md.
- Frontend setup and verification commands: frontend/README.md.
- Next: frontend browser verification, independent review, testing, human gate,
  then authorized merge to develop. Business development remains future work.
