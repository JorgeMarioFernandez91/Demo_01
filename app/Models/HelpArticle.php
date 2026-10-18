<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Database\Factories\HelpArticleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpArticle extends Model
{
    /** @use HasFactory<HelpArticleFactory> */
    use BelongsToTenant, HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'body',
    ];
}
