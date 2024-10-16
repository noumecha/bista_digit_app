<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = ['libelleEvaluation','trimestre_id'];

    /**
     *
     */
    public function trimestre(): BelongsTo
    {
        return $this->belongsTo(Trimestre::class, 'trimestre_id');
    }

    /**
     *
     */
    public function remplissages(): HasMany
    {
        return $this->hasMany(Remplissage::class);
    }


    /**
     *
     */
    public function notes(): HasMany {
        return $this->hasMany(Note::class);
    }
}
