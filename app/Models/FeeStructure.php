<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'level_id',
        'registration_fee',
        'trimester_1_fee',
        'trimester_2_fee',
        'trimester_3_fee',
    ];

    protected $appends = [
        'total_year_fee',
    ];

    protected function casts(): array
    {
        return [
            'registration_fee' => 'decimal:0',
            'trimester_1_fee' => 'decimal:0',
            'trimester_2_fee' => 'decimal:0',
            'trimester_3_fee' => 'decimal:0',
        ];
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    protected function totalYearFee(): Attribute
    {
        return Attribute::make(
            get: fn () => (float) $this->registration_fee
                + (float) $this->trimester_1_fee
                + (float) $this->trimester_2_fee
                + (float) $this->trimester_3_fee
        );
    }
}
