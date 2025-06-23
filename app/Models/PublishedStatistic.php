<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublishedStatistic extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'annee_scolaire_id',
        'type',
        'trimestre_id',
        'classe_id',
        'obc_rank',
        'data',
        'is_published',
        'user_id'
    ];

    protected $casts = [
        'data' => 'array',
        'published_at' => 'datetime'
    ];

    /**
     *
     */
    public function anneescolaire()
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }

    /**
     *
     */
    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'trimestre_id');
    }

    /**
     *
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     *
     */
    public function publisher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}