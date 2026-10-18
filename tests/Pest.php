<?php

use App\Models\Tenant;
use App\Support\CurrentTenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/**
 * Run the callback with the given tenant as the current tenant.
 *
 * @template T
 *
 * @param  callable(): T  $callback
 * @return T
 */
function asTenant(Tenant $tenant, callable $callback): mixed
{
    return app(CurrentTenant::class)->run($tenant, $callback);
}
