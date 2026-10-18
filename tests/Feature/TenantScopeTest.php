<?php

use App\Models\HelpArticle;
use App\Models\Tenant;
use App\Models\Ticket;
use App\Models\User;
use App\Support\CurrentTenant;

beforeEach(function () {
    $this->acme = Tenant::factory()->create();
    $this->globex = Tenant::factory()->create();

    asTenant($this->acme, fn () => Ticket::factory()->count(3)->create());
    asTenant($this->globex, fn () => Ticket::factory()->count(2)->create());
    asTenant($this->acme, fn () => HelpArticle::factory()->count(2)->create());
    asTenant($this->globex, fn () => HelpArticle::factory()->count(4)->create());
});

it('only returns records for the current tenant', function () {
    asTenant($this->acme, function () {
        expect(Ticket::count())->toBe(3)
            ->and(Ticket::pluck('tenant_id')->unique()->all())->toBe([$this->acme->id])
            ->and(HelpArticle::count())->toBe(2);
    });

    asTenant($this->globex, function () {
        expect(Ticket::count())->toBe(2)
            ->and(HelpArticle::count())->toBe(4);
    });
});

it('returns nothing when no tenant is set', function () {
    expect(app(CurrentTenant::class)->check())->toBeFalse()
        ->and(Ticket::count())->toBe(0)
        ->and(HelpArticle::count())->toBe(0);
});

it('cannot find another tenant\'s record by id', function () {
    $globexTicket = asTenant($this->globex, fn () => Ticket::first());

    asTenant($this->acme, function () use ($globexTicket) {
        expect(Ticket::find($globexTicket->id))->toBeNull();
    });
});

it('assigns the current tenant when creating a record', function () {
    $ticket = asTenant($this->acme, fn () => Ticket::create([
        'subject' => 'Printer on fire',
        'body' => 'It is on fire.',
        'status' => 'open',
    ]));

    expect($ticket->tenant_id)->toBe($this->acme->id);
});

it('refuses to create a record without a tenant', function () {
    Ticket::factory()->create();
})->throws(LogicException::class, 'without a current tenant');

it('refuses to create a record for a different tenant', function () {
    asTenant($this->acme, fn () => Ticket::factory()->create(['tenant_id' => $this->globex->id]));
})->throws(LogicException::class, 'for a different tenant');

it('only lists the logged-in user\'s tenant tickets', function () {
    $user = User::factory()->for($this->acme)->create();

    $this->actingAs($user)
        ->get('/tickets')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Tickets/Index')
            ->has('tickets', 3)
        );
});

it('returns 404 for another tenant\'s ticket', function () {
    $user = User::factory()->for($this->acme)->create();
    $acmeTicket = asTenant($this->acme, fn () => Ticket::first());
    $globexTicket = asTenant($this->globex, fn () => Ticket::first());

    $this->actingAs($user)->get("/tickets/{$acmeTicket->id}")->assertOk();
    $this->actingAs($user)->get("/tickets/{$globexTicket->id}")->assertNotFound();
});
