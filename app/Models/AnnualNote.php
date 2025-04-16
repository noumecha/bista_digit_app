<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnualNote extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "classe_id",
        "matiere_id",
        "annee_scolaire_id",
        "eval1_note",
        "eval2_note",
        "eval3_note",
        "eval4_note",
        "eval5_note",
        "eval6_note",
        "note",
        "rang",
        "mgc",
        "min_note",
        "max_note",
        "appreciation",
    ];

    /**
     * an annual note correspond to a student
     */
    public function eleve(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * an annual note correspond to a subject
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    /**
     * an annual note correspond to a classe
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * an annual note correspond to a schoolYear
     */
    public function annee_scolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }
}
