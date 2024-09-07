<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeScolaire extends Model
{
    use HasFactory;
    /**
     * @var array
     */
    protected $fillable = [
        'libelleAnneeScolaire',
    ];

    /**
     *
     */
    public function users() {
        return $this->belongsToMany(User::class);
    }
}
