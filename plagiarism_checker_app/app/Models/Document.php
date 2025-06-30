<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'exam_id',
        'class_id',
        'subject_id',
        'uploaded_by',
        'media_id',
        'batch_id',
        'status',
        'original_name',
        'description',
        'metadata'
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


    # ==============================================================================
    # Local query scopes
    # ==============================================================================
    public function scopeUploadedByCurrentUser(Builder $query): Builder
    {
        return $query->where('uploaded_by', auth()->id());
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

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

    public function batch(): BelongsTo
    {
        return $this->belongsTo(DocumentBatch::class, 'batch_id');
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
