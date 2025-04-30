<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Epreuve extends Model
{
    use HasFactory;

    /**
     * @mixed array
     */
    protected $fillable = [
        'libelleEpreuve',
        'anneeEpreuve',
        'fichier',
        'matiere_id',
        'classe_id',
        'type_epreuve_id',
        'user_id'
    ];

    /**
     *
     */
    public function type() : BelongsTo
    {
        return $this->belongsTo(TypeEpreuve::class, 'type_epreuve_id');
    }

    /**
     *
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     *
     */
    public function matiere() : BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    /**
     *
     */
    public function classe() : BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

}
