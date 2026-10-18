<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Tickets/Index', [
            'tickets' => Ticket::query()
                ->latest()
                ->get(['id', 'subject', 'category', 'status', 'created_at']),
        ]);
    }

    public function show(Ticket $ticket): Response
    {
        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket->only('id', 'subject', 'body', 'category', 'status', 'created_at'),
        ]);
    }
}
