<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentVaccination extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'vaccine_name',
        'dose_number',
        'vaccination_date',
        'next_due_date',
        'administered_by',
    ];

    protected function casts(): array
    {
        return [
            'vaccination_date' => 'date',
            'next_due_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
