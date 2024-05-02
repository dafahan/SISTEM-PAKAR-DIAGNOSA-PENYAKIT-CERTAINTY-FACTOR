<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalculationDetail extends Model
{
    protected $guarded = ['id'];

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }
}
