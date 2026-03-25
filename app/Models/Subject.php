<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'coefficient',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'coefficient' => 'decimal:1',
            'is_active' => 'boolean',
        ];
    }

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'classroom_subject')
            ->withPivot('user_id')
            ->withTimestamps();
    }

    public function gradePeriods(): HasMany
    {
        return $this->hasMany(GradePeriod::class);
    }

    public function timetableSlots(): HasMany
    {
        return $this->hasMany(TimetableSlot::class);
    }
}
