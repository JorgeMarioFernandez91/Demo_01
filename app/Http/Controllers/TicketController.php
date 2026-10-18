<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Tickets/Index', [
            'tickets' => Ticket::query()
                ->latest()
                ->latest('id')
                ->get(['id', 'subject', 'body', 'category', 'status', 'created_at'])
                ->map(fn (Ticket $ticket) => [
                    ...$ticket->only('id', 'subject', 'category', 'status', 'created_at'),
                    'excerpt' => Str::limit($ticket->body, 120),
                ]),
        ]);
    }

    public function show(Ticket $ticket): Response
    {
        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket->only('id', 'subject', 'body', 'category', 'status', 'created_at', 'updated_at'),
        ]);
    }
}
