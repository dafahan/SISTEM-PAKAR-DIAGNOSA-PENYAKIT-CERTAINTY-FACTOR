<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calculation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }

    public function details() : HasMany {
        return $this->hasMany(CalculationDetail::class);
    }

    public function questionnaires() : HasMany {
        return $this->hasMany(Questionnaire::class);
    }
}
