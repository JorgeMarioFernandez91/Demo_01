<?php

namespace App\Http\Middleware;

use App\Support\CurrentTenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function __construct(private CurrentTenant $currentTenant) {}

    /**
     * Set the current tenant from the authenticated user. Runs before route
     * model binding so bound models are resolved through the tenant scope.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->currentTenant->set($request->user()?->tenant);

        return $next($request);
    }
}
