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
    protected $fillable = ['titre','image','contenu','categorie_actualites_id','user_id'];

    /**
     *
     */
    public function categorieActualite() : BelongsTo
    {
        return $this->belongsTo(CategorieActualite::class, 'categorie_actualites_id');
    }

    /**
     *
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
