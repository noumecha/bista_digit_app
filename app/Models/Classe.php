<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Classe extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['libClasse','effectifClasse','cycleClasse'];

    /**
     * @var array
     */
    protected $casts = [];

    /**
     *
     */
    public function enseignantMatiere(): BelongsToMany
    {
        return $this->belongsToMany(EnseignantMatiereModel::class, 'enseignement');
    }

}
