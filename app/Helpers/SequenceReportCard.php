<?php

use App\Models\Bulletin;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\Note;
use App\Models\User;

/**
 * generate bulletin for single student in a specific class
 * for a sequence
 */
function generateSingleSeqReportCard($student, $classe, $evaluation, $trimestre) {
    try {
        $notes = Note::where('user_id', $student->id)
            ->where('evaluation_id', $evaluation->id)
            ->where('classe_id', $classe->id)
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->get();
        $average = getAverage(getCurrentYear()->id, $notes);
        $appreciation = getAppreciation($average);
        $princClassTeacherName = getPrincipalClassTeacher($classe->id, getCurrentYear()->id);
        $displineStats = getDisciplinesStats($student->disciplines);
        // checking if the bulletin already exists :
        $exists = Bulletin::where('classe_id', $classe->id)
            ->where('user_id',$student->id)
            ->where('trimestre_id', $trimestre->id)
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->where('evaluation_id', $evaluation->id)->exists();
        if($exists) {
            return [
                "type" => "error",
                "message" => "l'élève {$student->name} a déjà un bulletin pour : {$evaluation->libelleEvaluation}"
            ];
        }
        // create new sequenciel bulletin
        $bulletin = Bulletin::create([
            'user_id' => $student->id,
            'classe_id' => $classe->id,
            'app_configuration_id' => appConfiguration()->id,
            'annee_scolaire_id' => getCurrentYear()->id,
            'bulletin_file' => $student->bulletin_file,
            'type_bulletin' => 'sequenciel',
            'evaluation_id' => $evaluation->id,
            'trimestre_id' => $trimestre->id,
            'discipline_stats' => json_encode($displineStats),
            'appreciation' => $appreciation,
            'average' => $average,
            'principal_class_teacher' => $princClassTeacherName
        ]);
        // update bulletins stats
        updateAllReportCardStats(
            $classe->id,
            $evaluation->id,
            $trimestre->id,
            getCurrentYear()->id
        );
        if($bulletin) {
            return [
                "type" => "success",
                "message" => "Bulletin sequenciel de {$student->name} généré avec succès !"
            ];
        }
    } catch (Exception $ex) {
        throw $ex;
    }
}
/**
 * generate all class report cards for a sequence
 */
function generateAllSeqReportCard($classe, $evaluation, $trimestre) {
    try {
        $allUsersIds = ClasseAnneeScolaireStudent::all()
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->where('classe_id', $classe->id)->pluck('user_id');
        $students = User::all()->where('typeUser','eleve')
            ->whereIn('id', $allUsersIds);
        foreach ($students as $student) {
            $notes = Note::where('user_id', $student->id)
                ->where('evaluation_id', $evaluation->id)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('classe_id', $classe->id)
                ->get();
            $average = getAverage(getCurrentYear()->id, $notes);
            $appreciation = getAppreciation($average);
            $princClassTeacherName = getPrincipalClassTeacher($classe->id, getCurrentYear()->id);
            $displineStats = getDisciplinesStats($student->disciplines);
            $exists = Bulletin::where('classe_id', $classe->id)
                ->where('user_id',$student->id)
                ->where('trimestre_id', $trimestre->id)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('evaluation_id', $evaluation->id)->exists();
            if($exists) {
                return [
                    "type" => "error",
                    "message" => "l'élève {$student->name} a déjà un bulletin pour cette séquence !"
                ];
            }
            Bulletin::create([
                'user_id' => $student->id,
                'classe_id' => $classe->id,
                'app_configuration_id' => appConfiguration()->id,
                'annee_scolaire_id' => getCurrentYear()->id,
                'bulletin_file' => $student->bulletin_file,
                'type_bulletin' => 'sequenciel',
                'evaluation_id' => $evaluation->id,
                'trimestre_id' => $trimestre->id,
                'discipline_stats' => json_encode($displineStats),
                'appreciation' => $appreciation,
                'average' => $average,
                'principal_class_teacher' => $princClassTeacherName
            ]);
            updateAllReportCardStats(
                $classe->id,
                $evaluation->id,
                $trimestre->id,
                getCurrentYear()->id
            );
        }
        return [
            "type" => "success",
            "message" => "Bulletins de la séquence : {$evaluation->libelleEvaluation} de la classe de {$classe->libClasse} générés avec succès !"
        ];
    } catch (Exception $ex) {
        throw $ex;
    }
}