<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
