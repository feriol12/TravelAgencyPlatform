# CURRENT_STATE.md

## Project

Travel Agency Platform — single-agency travel-procedure management platform.

## Phase

Backend foundation implemented on `feature/backend-foundation`.
Independent review, tester verdict and human gate remain pending. No merge.
Functional design, architecture, data model and AI governance remain approved.

## Stable branches

Verified local baseline:
- main: fa8aafc — chore: initialize travel agency project baseline
- develop: fa8aafc — chore: initialize travel agency project baseline
- foundation base: develop / fa8aafc

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

## Current implementation

BACKEND:
Initialized. Scaffold welcome route and health endpoint; no business implementation.
Local start: `cd backend`, then `php artisan serve --host=127.0.0.1 --port=8000`.

FRONTEND:
Not initialized; frontend/.gitkeep only. Not changed by this task.

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

QUALITY:
`composer validate --strict`: valid.
`composer audit`: no security vulnerability advisories found.
`php vendor/bin/pint --test`: passes.
No dependency upgrades or additions.

DEPLOYMENT:
Not configured or performed.

## Active task

Human-provided backend foundation specification (this implementation session).
Branch: feature/backend-foundation.
Role: AI Implementer. Independent reviewer/tester not yet assigned in repository.
Implementation does not constitute approval or completion of the human gate.

## Notes and next milestone

- Backend agent instructions now reference root governance and prohibit automatic
  dependency installation without explicit task/human approval.
- Backend setup and verification commands: backend/README.md.
- Next: independent review, testing, human gate, then authorized merge to develop.
- Frontend foundation and business development remain separate future work.
