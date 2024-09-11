<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matiere extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'libelleMatiere',
        'codeMatiere',
    ];

    /**
     *
     */
    public function enseignants(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
