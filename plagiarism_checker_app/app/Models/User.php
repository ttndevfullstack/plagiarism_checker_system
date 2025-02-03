<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasName;

class User extends Authenticatable implements HasName
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

        static::updating(static function (User $user): void {
            $user->updateFullNameAttribute();
            $user->updateProfileTimestampAttribute();
        });
    }

    # ==============================================================================
    # Relationships
    # ==============================================================================



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
        if (! $this->isDirty(['name', 'email', 'full_name'])) {
            return;
        }

        $this->profile_updated = now();
    }



    # ==============================================================================
    # Methods
    # ==============================================================================
    public function isAdmin(): bool
    {
        return $this->is_admin || $this->hasRole(self::ADMIN_ROLE);
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
