<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Passwordless "log in as tenant" for local development only.
 */
class DemoLoginController extends Controller
{
    public function create(): Response
    {
        $this->ensureLocal();

        return Inertia::render('Login', [
            'tenants' => Tenant::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Tenant $tenant): RedirectResponse
    {
        $this->ensureLocal();

        Auth::login($tenant->users()->firstOrFail());
        request()->session()->regenerate();

        return redirect()->route('tickets.index');
    }

    public function destroy(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function ensureLocal(): void
    {
        abort_unless(app()->environment('local', 'testing'), 404);
    }
}
