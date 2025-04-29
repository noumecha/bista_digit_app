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
        'user_id', // student id
        'classe_id',
        'app_configuration_id',
        'annee_scolaire_id',
        'bulletin_file',
        'type_bulletin',
        'evaluation_id',
        'trimestre_id',
        'discipline_stats',
        'conseils_stats',
        'appreciation',
        'average',
        'min_average',
        'max_average',
        'general_average',
        'standard_deviation',
        'range',
        'principal_class_teacher'
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

    /**
     * a Bulletinn configuration belongs to a app configuration
     */
    public function appconfiguration():BelongsTo
    {
        return $this->belongsTo(AppConfiguration::class, 'app_configuration_id');
    }

    /***
     * function for getting total moyenne that elder or equal to 10
     */
    public function totalNumberOfMoy() {
        $total = 0;
        $bulletins = Bulletin::all()->where('classe_id', $this->classe_id)
            ->where('annee_scolaire_id',$this->annee_scolaire_id)
            ->where('evaluation_id',$this->evaluation_id)
            ->where('trimestre_id',$this->trimestre_id);
        foreach($bulletins as $bulletin) {
            if ($bulletin->average >= 10)
                $total++;
        }
        return $total;
    }

    /**
     * function to determine reussite percent
     */
    function getWinPercent() {
        $percent = 0;
        $bulletins = Bulletin::all()->where('classe_id', $this->classe_id)
            ->where('annee_scolaire_id',$this->annee_scolaire_id)
            ->where('evaluation_id',$this->evaluation_id)
            ->where('trimestre_id',$this->trimestre_id);
        $totalBulletins = count($bulletins);
        $totalWinData = 0;
        foreach ($bulletins as $bulletin) {
            if ($bulletin->average >= 10) {
                $totalWinData++;
            }
        }
        $percent = ($totalWinData / $totalBulletins) * 100;
        return $percent;
    }
}
