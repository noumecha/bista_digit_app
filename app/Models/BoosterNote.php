<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoosterNote extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'note',
        'appreciation',
        'booster_student_id',
        'booster_matiere_id',
        'evaluation_id',
        'remplissage_id',
        'classe_id',
        'range',
        'gcma',
        'min_value',
        'max_value',
        'annee_scolaire_id'
    ];

    /**
     * a booster note correspond to a student
     */
    public function boosterStudent(): BelongsTo
    {
        return $this->belongsTo(BoosterStudent::class, 'booster_student_id');
    }

    /**
     * a booster note belongs to a school year
     */
    public function annee_scolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }
    /**
     * a booster note correspond to a subject
     */
    public function boosterMatiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'booster_matiere_id');
    }

    /**
     * a booster note correspond to a specific evaluation
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    /**
     * a booster note is create by a specific remplissage configuration
     */
    public function remplissage(): BelongsTo
    {
        return $this->belongsTo(Remplissage::class, 'remplissage_id');
    }

    /**
     * a booster note correspond to a specific classe
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * a booster note has many notes histories
     */
    public function histories(): HasMany
    {
        return $this->hasMany(NoteHistory::class);
    }
}
