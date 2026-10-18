<?php

namespace App\Models\Concerns;

use App\Models\Scopes\TenantScope;
use App\Models\Tenant;
use App\Support\CurrentTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            $tenantId = app(CurrentTenant::class)->id();

            if ($tenantId === null) {
                throw new LogicException('Cannot create '.class_basename($model).' without a current tenant.');
            }

            if ($model->tenant_id !== null && (int) $model->tenant_id !== $tenantId) {
                throw new LogicException('Cannot create '.class_basename($model).' for a different tenant.');
            }

            $model->tenant_id = $tenantId;
        });
    }

    /**
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
