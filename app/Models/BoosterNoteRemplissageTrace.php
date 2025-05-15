<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoosterNoteRemplissageTrace extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'booster_teacher_id',
        'classe_id',
        'booster_matiere_id',
        'evaluation_id',
        'remplissage_id',
        'annee_scolaire_id',
        'nb_notes_remplies'
    ];

    /**
     * a booster note trace correspond to a student
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(BoosterTeacher::class, 'booster_teacher_id');
    }

    /**
     * a booster note trace belongs to a school year
     */
    public function annee_scolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }
    /**
     * a booster note trace correspond to a subject
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(BoosterMatiere::class, 'booster_matiere_id');
    }

    /**
     * a booster note trace correspond to a specific evaluation
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    /**
     * a booster note trace is create by a specific remplissage configuration
     */
    public function remplissage(): BelongsTo
    {
        return $this->belongsTo(Remplissage::class, 'remplissage_id');
    }

    /**
     * a booster note trace correspond to a specific classe
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }
}
