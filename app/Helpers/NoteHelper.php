<?php

use App\Models\BoosterNote;
use App\Models\BoosterStudent;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\Note;

/**
 * percentage of filling note
 */
function getRemplissagePourcentage($classeId, $matiereId, $evaluationId) {
    $elevesCount = ClasseAnneeScolaireStudent::where('classe_id', $classeId)
        ->where('annee_scolaire_id', getCurrentYear()->id)
        ->count();

    $notesCount = Note::where('classe_id', $classeId)
        ->where('matiere_id', $matiereId)
        ->where('evaluation_id', $evaluationId)
        ->where('annee_scolaire_id', getCurrentYear()->id)
        ->count();

    return $elevesCount > 0 ? round(($notesCount / $elevesCount) * 100, 2) : 0;
}

/**
 * for programme booster notes
 */
function getBoosterRemplissagePourcentage($classeId, $matiereId, $evaluationId) {
    $elevesIds = BoosterStudent::all()->pluck('user_id');
    $elevesCount = ClasseAnneeScolaireStudent::where('classe_id', $classeId)
        ->where('annee_scolaire_id', getCurrentYear()->id)
        ->whereIn('user_id', $elevesIds)
        ->count();

    $notesCount = BoosterNote::where('classe_id', $classeId)
        ->where('booster_matiere_id', $matiereId)
        ->where('evaluation_id', $evaluationId)
        ->where('annee_scolaire_id', getCurrentYear()->id)
        ->count();

    return $elevesCount > 0 ? round(($notesCount / $elevesCount) * 100, 2) : 0;
}
