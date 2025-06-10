<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Devoir extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'titre_devoir',
        'description_devoir',
        'user_id',
        'matiere_id',
        'annee_scolaire_id',
        'classe_id',
        'dateDeDebut',
        'dateDeFin',
        'statut'
    ];

    /**
     * Un devoir correspond à une année scolaire
     */
    public function anneeScolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }

    /**
     * Un devoir est creer par un enseignant
     */
    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Un devoir correspond à une matiere
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    /**
     * Un devoir correspond à une classe
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * Un devoir appartient à un résultat
     */
    public function results() {
        return $this->hasMany(DevoirResult::class);
    }

    /**
     * Un devoir possède plusieurs questions
     */
    public function questions():HasMany
    {
        return $this->hasMany(Question::class);
    }
}
