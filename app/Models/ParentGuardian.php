<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ParentGuardian extends Model
{
    use HasFactory;

    protected $table = 'parents_guardians';

    protected $fillable = [
        'last_name',
        'first_name',
        'gender',
        'birth_date',
        'nationality',
        'phone',
        'phone_2',
        'email',
        'whatsapp',
        'relationship',
        'profession',
        'employer',
        'workplace_phone',
        'address',
        'city',
        'district',
        'is_alive',
        'is_legal_guardian',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_alive' => 'boolean',
            'is_legal_guardian' => 'boolean',
        ];
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_parent', 'parent_id', 'student_id')
            ->withPivot([
                'is_primary_contact',
                'is_financial_responsible',
                'is_pickup_authorized',
            ])
            ->withTimestamps();
    }
}
