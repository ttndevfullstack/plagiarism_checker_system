<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'class_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'uploaded_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
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

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'exam_id');
    }
}
