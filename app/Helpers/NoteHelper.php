<?php

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
