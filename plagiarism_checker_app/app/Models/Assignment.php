<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'class_id',
        'teacher_id',
        'assignment_date',
    ];

    protected $casts = [
        'assignment_date' => 'date',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
