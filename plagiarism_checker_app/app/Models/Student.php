<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use SoftDeletes;

    public const PREFIX_STUDENT_CODE = 'STU';

    protected $fillable = ['user_id', 'student_code', 'enrollment_date'];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function (Student $student): void {
            $student->student_code = uniqid(self::PREFIX_STUDENT_CODE);
        });

        static::created(static function (Student $student): void {
            $student->updateStudentCodeAttribute();
            $student->saveQuietly();
        });
    }



    # ==============================================================================
    # Relationships
    # ==============================================================================
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }



    # ==============================================================================
    # Attributes
    # ==============================================================================
    public function updateStudentCodeAttribute(): void
    {
        if ($this->user && $this->user->id) {
            $this->student_code = self::PREFIX_STUDENT_CODE . str_pad($this->user->id, 5, '0', STR_PAD_LEFT);
        }
    }
}
