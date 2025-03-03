<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppConfiguration extends Model
{
    use HasFactory;
    /**
     * @var array
     */
    protected $fillable = [
        'school_name',
        'school_motor',
        'school_postal_box',
        'school_logo',
        'description',
        'school_town',
        'school_location',
        'contact_phone_1',
        'contact_phone_2',
        'school_email',
    ];
}
