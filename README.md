# Helpdesk (multi-tenant demo)

Laravel 12 + Inertia + Vue 3 + SQLite. Tenants, users, tickets and help articles, with every tenant-owned query scoped by a global scope.

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite && php artisan migrate:fresh --seed
composer run dev   # open http://localhost:8000/login and pick a tenant
```

Tests and lint: `vendor/bin/pest`, `vendor/bin/pint --test`. See [CLAUDE.md](CLAUDE.md) for conventions and tenant rules.
