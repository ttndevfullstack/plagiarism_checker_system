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
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

    const ADMIN_ROLE = 'admin';

    const TEACHER_ROLE = 'teacher';

    const STUDENT_ROLE = 'student';

    protected $guarded = ['id'];

    protected $hidden = ['password', 'remember_token'];

    protected $fillable = ['name', 'email', 'password'];

    protected $casts = [
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'email_verified_at' => 'datetime',
        'last_login_timestamp' => 'datetime',
        'profile_updated' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function (User $user) {
            $this->updateFullNameAttribute();
            $this->updateProfileTimestamp();
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
        return "{$this->first_name} {$this->last_name}";
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

    public function updateFullNameAttribute()
    {
        if ($this->isDirty(['first_name', 'last_name'])) {
            $this->full_name = "{$this->first_name} {$this->last_name}";
        }
    }

    public function updateProfileTimestamp()
    {
        if ($this->isDirty(['name', 'email', 'full_name'])) {
            $this->profile_updated_at = now();
        }
    }
}
