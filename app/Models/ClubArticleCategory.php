<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubArticleCategory extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'club_id',
        'club_article_category_name'
    ];

    /**
     *
     */
    public function club() : BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }
}
