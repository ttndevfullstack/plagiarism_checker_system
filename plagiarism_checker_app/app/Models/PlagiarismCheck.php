<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlagiarismCheck extends Model
{
    protected $fillable = [
        'user_id',
        'document_id',
        'text_content',
        'similarity_score',
        'confidence_score',
        'matches',
        'metadata'
    ];

    protected $casts = [
        'matches' => 'array',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function document(): BelongsTo 
    {
        return $this->belongsTo(Document::class);
    }
}
