<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassRoom extends Model
{
    use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'subject_id',
        'teacher_id',
        'name',
        'room_number',
        'academic_year',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'room_number' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function (ClassRoom $classRoom): void {
            $classRoom->updateRoomNumberAttribute();
        });
    }



    # ==============================================================================
    # Local query scopes
    # ==============================================================================
    public function scopeAssignedCurrentUser(Builder $query): Builder
    {
        return $query->where('teacher_id', auth()->user()->teacher->id);
    }


    
    # ==============================================================================
    # Relationships
    # ==============================================================================
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'class_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'class_id');
    }



    # ==============================================================================
    # Attributes
    # ==============================================================================
    public function updateRoomNumberAttribute(): void
    {
        $randomNumber = mt_rand(100000, 999999);

        while (self::where('room_number', $randomNumber)->exists()) {
            $randomNumber = mt_rand(100000, 999999);
        }

        $this->room_number = $randomNumber;
    }
}
