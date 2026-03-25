<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'classroom_id',
        'academic_year_id',
        'enrollment_date',
        'status',
    ];

    protected $appends = [
        'total_paid',
        'remaining_balance',
    ];

    protected function casts(): array
    {
        return [
            'enrollment_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    protected function totalPaid(): Attribute
    {
        return Attribute::make(
            get: fn () => (float) $this->payments()->sum('amount')
        );
    }

    protected function remainingBalance(): Attribute
    {
        return Attribute::make(get: function () {
            $levelId = $this->classroom?->level_id;

            if (! $levelId || ! $this->academic_year_id) {
                return null;
            }

            $feeStructure = FeeStructure::query()
                ->where('academic_year_id', $this->academic_year_id)
                ->where('level_id', $levelId)
                ->first();

            if (! $feeStructure) {
                return null;
            }

            return (float) $feeStructure->total_year_fee - (float) $this->total_paid;
        });
    }
}
