<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoosterMatiere extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'matiere_id',
        'annee_scolaire_id'
    ];

    /**
     *
     */
    public function annee_scolaire(): BelongsTo {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }

    /**
     *
     */
    public function matiere():BelongsTo {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    /**
     * a boostermatier must be in a boosternote
     */
    public function boosterNotes() {
        return $this->hasOne(BoosterNote::class, $this->id);
    }
}
