<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Club extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'club_name',
        'contenu',
        'president_id',
        'club_image'
    ];

    /**
     *
     */
    public function president(): BelongsTo
    {
        return $this->belongsTo(User::class, 'president_id');
    }

    /**
     *
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('post_id')->withTimestamps();
    }

    /**
     *
     */
    public function postes(): HasMany
    {
        return $this->hasMany(ClubPost::class);
    }

    /**
     *
     */
    public function actualites(): MorphMany
    {
        return $this->morphMany(Actualite::class, 'actualiteable'); // if reusing article
    }

}
