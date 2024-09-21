<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeEpreuve extends Model
{
    use HasFactory;

    /**
     * @var $table
     */
    protected $table = 'type_epreuves';

    /**
     *
     */
    protected $fillable = ['libelleTypeEpreuve'];

    /**
     *
     */
    public function epreuves(): HasMany {
        return $this->hasMany(Epreuve::class);
    }

}
