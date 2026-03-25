<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'absenceable_id',
        'absenceable_type',
        'absent_date',
        'reason',
        'is_justified',
        'justified_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'absent_date' => 'date',
            'is_justified' => 'boolean',
        ];
    }

    public function absenceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function justifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'justified_by');
    }
}
