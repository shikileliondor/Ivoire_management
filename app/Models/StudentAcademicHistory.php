<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAcademicHistory extends Model
{
    use HasFactory;

    protected $table = 'student_academic_history';

    protected $fillable = [
        'student_id',
        'academic_year',
        'school_name',
        'class_name',
        'result',
        'average',
        'rank',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'average' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
