<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'slider_title',
        'slider_text',
        'slider_image'
    ];

    /**
     *
     */
}
