<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoteRemplissageTrace extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'classe_id',
        'matiere_id',
        'evaluation_id',
        'remplissage_id',
        'annee_scolaire_id',
        'nb_notes_remplies'
    ];

    /**
     * a note trace correspond to a student
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * a note trace belongs to a school year
     */
    public function annee_scolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }
    /**
     * a note trace correspond to a subject
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    /**
     * a note trace correspond to a specific evaluation
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    /**
     * a note trace is create by a specific remplissage configuration
     */
    public function remplissage(): BelongsTo
    {
        return $this->belongsTo(Remplissage::class, 'remplissage_id');
    }

    /**
     * a note trace correspond to a specific classe
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }
}
