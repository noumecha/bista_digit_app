<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Actualite extends Model
{
    use HasFactory;

    /**
     * @mixed Array
     */
    protected $fillable = ['titre','image','contenu'];

    /**
     *
     */
    public function categorie() : BelongsTo
    {
        return $this->belongsTo(CategorieActualite::class);
    }
}
