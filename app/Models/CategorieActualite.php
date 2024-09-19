<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieActualite extends Model
{
    use HasFactory;

    /**
     * @mixed Array
     */
    protected $fillable = ['libelleCategorie'];

    /**
     *
     */
    public function actualites(): HasMany {
        return $this->hasMany(Actualite::class);
    }
}
