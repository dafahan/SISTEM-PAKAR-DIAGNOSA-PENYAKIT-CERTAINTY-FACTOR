<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalculationDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }
}
