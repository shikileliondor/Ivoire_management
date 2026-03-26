<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'photo',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ── Relations ─────────────────────────────────────────────────────────

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'classroom_subject', 'user_id', 'classroom_id')
                    ->withPivot('subject_id')
                    ->withTimestamps();
    }

    public function gradesGiven(): HasMany
    {
        return $this->hasMany(Grade::class, 'graded_by');
    }

    public function paymentsReceived(): HasMany
    {
        return $this->hasMany(Payment::class, 'received_by');
    }

    public function timetableSlots(): HasMany
    {
        return $this->hasMany(TimetableSlot::class);
    }

    public function absences(): MorphMany
    {
        return $this->morphMany(Absence::class, 'absenceable');
    }

    public function absencesJustified(): HasMany
    {
        return $this->hasMany(Absence::class, 'justified_by');
    }
}