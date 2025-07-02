<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlagiarismHistory extends Model
{
    protected $fillable = [
        'document_id',
        'subject_id',
        'class_id',
        'exam_id',
        'originality_score',
        'similarity_score',
        'source_matched',
        'words_analyzed',
        'encoded_file',
        'results',
        'uploaded_by',
    ];

    protected $casts = [
        'results' => 'array',
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

    public function document(): BelongsTo 
    {
        return $this->belongsTo(Document::class);
    }
    
    public function class(): BelongsTo 
    {
        return $this->belongsTo(ClassRoom::class);
    }
    
    public function subject(): BelongsTo 
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function exam(): BelongsTo 
    {
        return $this->belongsTo(Exam::class);
    }
}
