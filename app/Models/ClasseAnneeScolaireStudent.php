<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClasseAnneeScolaireStudent extends Model
{
    /**
     * helps to allow mass assignation
     * @var array
     */
    protected $fillable = [
        'classe_id',
        'user_id',
        'annee_scolaire_id'
    ];

    /**
     * classe
     */
    public function classe():BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * year
     */
    public function year(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }

    /**
     * student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
