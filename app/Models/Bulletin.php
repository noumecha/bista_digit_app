<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bulletin extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', // get base on evaluation and classe id
        'classe_id',
        'app_configuration_id',
        'annee_scolaire_id',
        'bulletin_file',
        'type_bulletin',
        'evaluation_id',
        'trimestre_id',
        'discipline_id',
        'appreciation',
        'average',
        'min_average',
        'max_average',
        'general_average',
        'standard_deviation',
        'range',
    ];

    /**
     * a Bulletin correspond to a specific trimestre
     */
    public function trimestre(): BelongsTo
    {
        return $this->belongsTo(Trimestre::class, 'trimestre_id');
    }

    /**
     * a Bulletin correspond to a specific evaluation
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    /**
     * a Bulletin content a specifif discipline datas
     */
    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'discipline_id');
    }

    /**
     * a Bulletin correspond to a specific classe
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * a Bulletin correspond to a specific school year
     */
    public function annee_scolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }

    /**
     * a Bulletin correspond to a specific student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
