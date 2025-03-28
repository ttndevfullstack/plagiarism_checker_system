<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subject) {
            if (empty($subject->code)) {
                $subject->code = static::generateSubjectCode($subject->name);
            }
        });
    }

    private static function generateSubjectCode(string $name): string
    {
        $code = collect(explode(' ', $name))
            ->map(fn ($word) => Str::upper(Str::substr($word, 0, 1)))
            ->join('');

        $baseCode = $code;
        $counter = 1;
        
        while (static::where('code', $code)->exists()) {
            $code = $baseCode . $counter++;
        }

        return $code;
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'subject_id');
    }
}
