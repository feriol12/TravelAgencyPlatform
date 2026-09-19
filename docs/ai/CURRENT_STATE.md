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
`php artisan test`: 3 tests, 5 assertions passing.
Two original scaffold tests plus a real MySQL `SELECT DATABASE()` isolation test.
PHPUnit forces the local travel_agency_test connection; credentials remain in .env.
TestCase rejects cached or unsafe configuration before test migration traits run.
Verified with inherited development DB_DATABASE and DB_URL values: tests pass
against the test database. Cached development configuration: rejected as expected,
then cleared. Current tests perform no writes; test database has no tables yet.

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

- Generated backend agent instructions propose installing Boost; not performed
  because this task explicitly prohibits unapproved dependencies.
- Backend setup and verification commands: backend/README.md.
- Next: independent review, testing, human gate, then authorized merge to develop.
- Frontend foundation and business development remain separate future work.
