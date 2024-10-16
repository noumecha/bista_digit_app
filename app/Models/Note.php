<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['note','appreciation','user_id','matiere_id','evaluation_id','remplissage_id','classe_id'];

    /**
     *
     */
    public function eleve(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     *
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    /**
     *
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    /**
     *
     */
    public function remplissage(): BelongsTo
    {
        return $this->belongsTo(Remplissage::class, 'remplissage_id');
    }

    /**
     *
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }
}
