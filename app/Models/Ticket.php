<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use BelongsToTenant, HasFactory;

    public const STATUSES = ['open', 'pending', 'closed'];

    public const CATEGORIES = ['billing', 'account', 'bug', 'feature-request'];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'subject',
        'body',
        'category',
        'status',
    ];
}
