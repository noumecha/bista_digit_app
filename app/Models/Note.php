<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'appreciation',
        'user_id',
        'matiere_id',
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
     * a note correspond to a student
     */
    public function eleve(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * a note belongs to a school year
     */
    public function annee_scolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }
    /**
     * a note correspond to a subject
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    /**
     * a note correspond to a specific evaluation
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    /**
     * a note is create by a specific remplissage configuration
     */
    public function remplissage(): BelongsTo
    {
        return $this->belongsTo(Remplissage::class, 'remplissage_id');
    }

    /**
     * a note correspond to a specific classe
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }
    /**
     * a note has many notes histories
     */
    public function histories(): HasMany
    {
        return $this->hasMany(NoteHistory::class);
    }
}
