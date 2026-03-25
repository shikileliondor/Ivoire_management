<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'matricule',
        'last_name',
        'first_name',
        'birth_date',
        'birth_place',
        'birth_country',
        'birth_certificate_number',
        'birth_certificate_date',
        'birth_certificate_place',
        'gender',
        'nationality',
        'address',
        'city',
        'district',
        'country',
        'blood_type',
        'has_disability',
        'disability_description',
        'chronic_illness',
        'allergies',
        'medical_notes',
        'family_situation',
        'siblings_count',
        'rank_in_siblings',
        'previous_school',
        'previous_school_city',
        'previous_class',
        'previous_year',
        'admission_type',
        'has_transfer_certificate',
        'photo',
        'is_active',
    ];

    protected $appends = [
        'full_name',
        'age',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'birth_certificate_date' => 'date',
            'has_disability' => 'boolean',
            'has_transfer_certificate' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
    }

    public function studentDocuments(): HasMany
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function studentVaccinations(): HasMany
    {
        return $this->hasMany(StudentVaccination::class);
    }

    public function studentAcademicHistories(): HasMany
    {
        return $this->hasMany(StudentAcademicHistory::class);
    }

    public function parentGuardians(): BelongsToMany
    {
        return $this->belongsToMany(ParentGuardian::class, 'student_parent', 'student_id', 'parent_id')
            ->withPivot([
                'is_primary_contact',
                'is_financial_responsible',
                'is_pickup_authorized',
            ])
            ->withTimestamps();
    }

    public function absences(): MorphMany
    {
        return $this->morphMany(Absence::class, 'absenceable');
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim("{$this->last_name} {$this->first_name}")
        );
    }

    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->birth_date ? Carbon::parse($this->birth_date)->age : null
        );
    }
}
