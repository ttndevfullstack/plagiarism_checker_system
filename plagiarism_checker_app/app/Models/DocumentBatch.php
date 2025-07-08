<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Awcodes\Curator\Models\Media;

class DocumentBatch extends Model
{
    protected $fillable = [
        'media_id',
        'media_path_id',
        'status',
        'metadata',
        'uploaded_by',
    ];

    protected $casts = [
        'metadata' => 'array',
        'status' => DocumentStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if (empty($document->uploaded_by) && auth()->check()) {
                $document->uploaded_by = auth()->id();
            }
        });
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
    
    public function mediaPath(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_path_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'batch_id');
    }

    public function isPending(): bool
    {
        return $this->status === DocumentStatus::PENDING;
    }

    public function isProcessing(): bool
    {
        return $this->status === DocumentStatus::PROCESSING;
    }

    public function isCompleted(): bool
    {
        return $this->status === DocumentStatus::COMPLETED;
    }
    
    public function isFailed(): bool
    {
        return $this->status === DocumentStatus::FAILED;
    }
}
