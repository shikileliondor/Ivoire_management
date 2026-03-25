<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'grade_period_id',
        'score',
        'is_absent',
        'comment',
        'graded_by',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'is_absent' => 'boolean',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function gradePeriod(): BelongsTo
    {
        return $this->belongsTo(GradePeriod::class);
    }

    public function gradedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
