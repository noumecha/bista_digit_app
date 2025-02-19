<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discipline extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'classe_id',
        'annee_scolaire_id',
        'mois',
        'heures_absence',
        'heures_justifiees',
        'total_absences',
        'avertissement',
        'decision'
    ];

    /**
     * a discipline affair belongTo an eleve
     */
    public function eleve():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * a discipline affair belongTo an a specific class
     */
    public function classe():BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * a discipline affair belongsTo a school Year
     */
    public function anneeScolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }
}
