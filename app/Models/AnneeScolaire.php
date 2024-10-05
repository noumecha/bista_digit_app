<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnneeScolaire extends Model
{
    use HasFactory;
    /**
     * @var array
     */
    protected $fillable = [
        'libelleAnneeScolaire',
    ];

    /**
     *
     */
    public function trimestres(): HasMany {
        return $this->hasMany(Trimestre::class);
    }

    /**
     *
     */
    public function users() {
        return $this->belongsToMany(User::class);
    }
}
