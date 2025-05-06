<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialite extends Model
{
     /**
     * @var array
     */
    protected $fillable = [
        'specialite_title',
        'contenu',
        'specialite_image',
        'sliders'
    ];

    protected $casts = [
        'sliders' => 'array'
    ];
}
