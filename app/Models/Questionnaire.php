<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Questionnaire extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    public function symptom(): BelongsTo
    {
        return $this->belongsTo(Symptom::class);
    }
}
