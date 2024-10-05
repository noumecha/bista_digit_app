<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trimestre extends Model
{
    use HasFactory;

    protected $fillable = ['libelleTrimestre','annee_scolaire_id'];

    /**
     *
     */
    public function evaluations(): HasMany {
        return $this->hasMany(Evaluation::class);
    }

    /**
     *
     */
    public function anneeScolaire() : BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }
}
