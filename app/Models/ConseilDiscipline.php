<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConseilDiscipline extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        "user_id",
        "annee_scolaire_id",
        "evaluation_id",
        "date_conseil",
        "mois",
        "motif",
        "decision",
    ];

    /**
     * an discipline advice belongs to a student
     */
    public function eleve():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * an discipline advice belongs to an a specific school year
     */
    public function anneeScolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }
}
