<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelleEvaluation',
        'type',
        'trimestre_id',
        'dateDeDebut',
        'dateDeFin',
        'statut',
    ];

    /**
     * An evaluation belongs to trimestre
     */
    public function trimestre(): BelongsTo
    {
        return $this->belongsTo(Trimestre::class, 'trimestre_id');
    }

    /**
     * an evaluation belongs to a filling not period
     */
    public function remplissages(): HasMany
    {
        return $this->hasMany(Remplissage::class);
    }


    /**
     * an evaluation have manys notes
     */
    public function notes(): HasMany {
        return $this->hasMany(Note::class);
    }

    /**
     * an evaluation have manys disciplines stats
     */
    public function disciplines(): HasMany {
        return $this->hasMany(Discipline::class);
    }
}
