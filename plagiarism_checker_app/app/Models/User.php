<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements HasName, FilamentUser
{
    use HasRoles;
    use HasFactory;
    use HasApiTokens;
    use Notifiable;

    public const ADMIN_ROLE = 'admin';

    public const TEACHER_ROLE = 'teacher';

    public const STUDENT_ROLE = 'student';

    protected $guarded = ['id'];

    protected $hidden = ['password', 'remember_token'];

    protected $fillable = ['first_name', 'last_name', 'full_name', 'email', 'dob', 'phone', 'address', 'avatar', 'password'];

    protected $casts = [
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'dob' => 'date',
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'profile_updated_at' => 'datetime', 
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function (User $user): void {
            $user->updateFullNameAttribute();
        });

        static::updating(static function (User $user): void {
            $user->updateFullNameAttribute();
            $user->updateProfileTimestampAttribute();
        });
    }

    
    public function scopeClassrooms(): Builder
    {
        $query = ClassRoom::query();

        if ($this->isTeacher()) {
            $teacherId = $this->teacher?->id;

            return $query->where(function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId)
                ->orWhereHas('assignments', function ($subQuery) use ($teacherId) {
                    $subQuery->where('teacher_id', $teacherId);
                });
            });
        }

        if ($this->isStudent()) {
            $studentId = $this->student?->id;

            return $query->whereHas('enrollments', function ($subQuery) use ($studentId) {
                $subQuery->where('student_id', $studentId);
            });
        }

        return $query;
    }

    public function scopeExams(): Builder
    {
        $query = Exam::query();

        if ($this->isTeacher()) {
            $teacherId = $this->teacher?->id;

            return $query->where(function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId)
                ->orWhereHas('class.assignments', function ($subQuery) use ($teacherId) {
                    $subQuery->where('teacher_id', $teacherId);
                });
            });
        }

        if ($this->isStudent()) {
            $studentId = $this->student?->id;

            return $query->whereHas('class.enrollments', function ($subQuery) use ($studentId) {
                $subQuery->where('student_id', $studentId);
            });
        }

        return $query;
    }



    # ==============================================================================
    # Relationships
    # ==============================================================================
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }
    
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }


    # ==============================================================================
    # Attributes
    # ==============================================================================
    public function getFilamentName(): string
    {
        return $this->last_name;
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function updateFullNameAttribute(): void
    {
        if (! $this->isDirty(['first_name', 'last_name'])) {
            return;
        }

        $this->full_name = "{$this->first_name} {$this->last_name}";
    }

    public function updateProfileTimestampAttribute(): void
    {
        if (! $this->isDirty(['first_name', 'last_name', 'full_name', 'email', 'dob', 'phone', 'address', 'avatar'])) {
            return;
        }

        $this->profile_updated_at = now();
    }



    # ==============================================================================
    # Methods
    # ==============================================================================
    /**
     * Check if the user can access a specific panel for filament.
     *
     * @param Panel $panel
     * @return boolean
     */
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(self::ADMIN_ROLE);
    }

    public function isTeacher(): bool
    {
        return $this->hasRole(self::TEACHER_ROLE);
    }

    public function isStudent(): bool
    {
        return $this->hasRole(self::STUDENT_ROLE);
    }
}
