<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrimestreNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'classe_id',
        'matiere_id',
        'trimestre_id',
        'eval1_note',
        'eval2_note',
        'trim_note',
        'rang',
        'class_avg',
        'min_note',
        'max_note',
        'annee_scolaire_id',
        'appreciation'
    ];

    /**
     * a trimestre note correspond to a student
     */
    public function eleve(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * a trimestre note correspond to a subject
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    /**
     * a trimestre note correspond to a classe
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * a trimestre note correspond to a trimestre
     */
    public function trimestre(): BelongsTo
    {
        return $this->belongsTo(Trimestre::class, 'trimestre_id');
    }

    /**
     * a trimestre note correspond to a schoolYear
     */
    public function annee_scolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }
}
