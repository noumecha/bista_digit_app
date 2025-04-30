<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubPost extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'club_id',
        'club_post_name'
    ];

    /**
     * a poste club poste belong to a club
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }
}
