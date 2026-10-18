# CLAUDE.md

Multi-tenant helpdesk demo: tenants, users, tickets and help articles.

## Stack

- Laravel 12 (PHP 8.2), SQLite (`database/database.sqlite`)
- Vue 3 via Inertia (v3), Vite, Tailwind 4
- Pest for tests, Pint (Laravel preset) for code style
- CI: `.github/workflows/ci.yml` runs Pint and Pest on every push

## Commands

```bash
composer install && npm install
php artisan migrate:fresh --seed   # 2 tenants, 16 help articles, 20 tickets
composer run dev                   # app + Vite; log in at /login (local only)

vendor/bin/pest                    # run tests
vendor/bin/pint --test             # check style (CI runs this)
vendor/bin/pint                    # fix style
```

Run `vendor/bin/pint --test` and `vendor/bin/pest` before you consider a change done.

## Tenant rules (non-negotiable)

- **Every query on tenant data is tenant-scoped.** Tenant-owned models use the
  `App\Models\Concerns\BelongsToTenant` trait, which adds `TenantScope` and sets
  `tenant_id` from `App\Support\CurrentTenant` on create.
- **Never mix tenant data.** No query, response, job, cache key or log may combine
  or expose records from more than one tenant.
- New tenant-owned tables need a `tenant_id` foreign key, and their models need `BelongsToTenant`.
- Don't set `tenant_id` from request input. It comes from `CurrentTenant`.
- Don't use `withoutGlobalScope(TenantScope::class)`, `withoutGlobalScopes()` or raw
  `DB::table()` on tenant tables without an explicit justification in the PR.
- With no tenant set, scoped queries return nothing and creates throw. Keep it fail-closed.
- `CurrentTenant` is set by `IdentifyTenant` middleware from the logged-in user, *before*
  route model binding. Outside HTTP (seeders, jobs, commands) use
  `app(CurrentTenant::class)->run($tenant, fn () => ...)`.
- `User` is intentionally not scoped: it is the source of tenant identity.
- Any change touching tenant data needs a test that proves tenant B can't see tenant A's data.

## Working style

- Keep changes small and focused: one concern per PR, no drive-by refactors.
- Follow existing Laravel conventions and the surrounding code style.
- Add or update Pest tests with every behavior change.
