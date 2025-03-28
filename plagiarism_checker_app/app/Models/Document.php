<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\DocumentStatus;
use Awcodes\Curator\Models\Media;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'class_id',
        'subject_id',
        'uploaded_by',
        'media_id',
        'status',
        'progress',
        'file_size',
        'original_name',
        'description',
        'metadata'
    ];

    protected $casts = [
        'progress' => 'integer',
        'file_size' => 'integer',
        'metadata' => 'array',
        'status' => DocumentStatus::class,
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(DocumentBatch::class);
    }

    public function batchFiles()
    {
        return $this->hasManyThrough(
            Media::class,
            DocumentBatch::class,
            'document_id', // Foreign key on document_batches table...
            'id', // Foreign key on media table...
            'id', // Local key on documents table...
            'media_id' // Local key on document_batches table...
        );
    }

    public function isProcessing(): bool
    {
        return $this->status === DocumentStatus::PROCESSING;
    }

    public function isCompleted(): bool
    {
        return $this->status === DocumentStatus::COMPLETED;
    }
}
