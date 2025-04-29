<?php

use App\Models\Bulletin;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\Evaluation;
use App\Models\Note;
use App\Models\TrimestreNote;
use App\Models\User;

/**
 * generate single student class report card
 * for a trimester
 */
function generateSingleTrimReportCard($student, $classe, $evaluation, $trimestre) {
    try {
        // check if sequenciel bulletin of the corresponding trimestre exist
        $evaluations = Evaluation::all()->where('trimestre_id', $trimestre->id);
        $bulletinsAvgs = [];
        foreach($evaluations as $evaluation) {
            $bulletin = Bulletin::where('evaluation_id',$evaluation->id)
                ->where('classe_id', $classe->id)
                ->where('annee_scolaire_id',getCurrentYear()->id)
                ->where('user_id', $student->id)->first();
            if(!$bulletin) {
                return [
                    "type" => "error",
                    "message" => "Le bulletin de : {$evaluation->libelleEvaluation}
                        de l'élève n'existe pas, impossible de générer le bulletin trimestriel!"
                ];
            } else {
                array_push($bulletinsAvgs, $bulletin->average);
            }
        }
        $trimAverage = getTrimAverage($bulletinsAvgs);
        $trimAppreciation = getAppreciation($trimAverage);
        $princClassTeacherName = getPrincipalClassTeacher($classe->id, getCurrentYear()->id);
        $trimDisplineStats = getDisciplinesStats($student->disciplines);
        $trimConseilsStats = getConseilsStats($student->conseils_disciplines);
        // checking if the bulletin already exists :
        $exists = Bulletin::where('classe_id', $classe->id)
            ->where('user_id',$student->id)
            ->where('trimestre_id', $trimestre->id)
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->where('type_bulletin', 'trimestre')->exists();
        if($exists) {
            return [
                "type" => "error",
                "message" => "l'élève {$student->name} a déjà un bulletin pour le trimestre : {$trimestre->libelleTrimestre}"
            ];
        }
        // update trimestre notes
        foreach($evaluations as $evaluation) {
            $notes = Note::all()->where('classe_id', $classe->id)
                ->where('user_id', $student->id)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('evaluation_id', $evaluation->id);
            foreach($notes as $note) {
                updateTrimestreNotes(
                    $evaluation,
                    $classe->id,
                    $student->id,
                    $note->matiere_id
                );
            }
        }
        // create new trimestrial bulletin
        Bulletin::create([
            'user_id' => $student->id,
            'classe_id' => $classe->id,
            'app_configuration_id' => appConfiguration()->id,
            'annee_scolaire_id' => getCurrentYear()->id,
            'type_bulletin' => 'trimestre',
            'trimestre_id' => $trimestre->id,
            'evaluation_id' => null,
            'discipline_stats' => json_encode($trimDisplineStats),
            'conseils_stats' => json_encode($trimConseilsStats),
            'appreciation' => $trimAppreciation,
            'average' => $trimAverage,
            'principal_class_teacher' => $princClassTeacherName
        ]);
        // update bulletins stats
        updateAllTrimReportCardStats(
            $classe->id,
            $trimestre->id,
            'trimestre',
            getCurrentYear()->id
        );
        return [
            "type" => "success",
            "message" => "Bulletin Trimestriel de {$student->name} généré avec succès !"
        ];
    } catch (Exception $ex) {
        throw $ex;
    }
}

/**
 * generate all class report cards
 * for a trimester
 */
function generateAllTrimReportCard($classe, $evaluation, $trimestre) {
    try {
        $allUsersIds = ClasseAnneeScolaireStudent::all()
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->where('classe_id', $classe->id)->pluck('user_id');
        $students = User::all()->where('typeUser','eleve')
            ->whereIn('id', $allUsersIds);
        foreach ($students as $student) {
            // check if sequenciel bulletin of the corresponding trimestre exist
            $evaluations = Evaluation::all()->where('trimestre_id', $trimestre->id);
            $bulletinsAvgs = [];
            foreach($evaluations as $evaluation) {
                $bulletin = Bulletin::where('evaluation_id',$evaluation->id)
                    ->where('classe_id', $classe->id)
                    ->where('annee_scolaire_id',getCurrentYear()->id)
                    ->where('user_id', $student->id)->first();
                if(!$bulletin) {
                    return [
                        "type" => "error",
                        "message" => "Le bulletin de : {$evaluation->libelleEvaluation}
                            de l'élève {$student->name} n'existe pas, impossible de générer le bulletin trimestriel!"
                    ];
                } else {
                    array_push($bulletinsAvgs, $bulletin->average);
                }
            }
            $trimAverage = getTrimAverage($bulletinsAvgs);
            $trimAppreciation = getAppreciation($trimAverage);
            $princClassTeacherName = getPrincipalClassTeacher($classe->id, getCurrentYear()->id);
            $trimDisplineStats = getDisciplinesStats($student->disciplines);
            $trimConseilsStats = getConseilsStats($student->conseils_disciplines);
            // checking if the bulletin already exists :
            $exists = Bulletin::where('classe_id', $classe->id)
                ->where('user_id',$student->id)
                ->where('trimestre_id', $trimestre->id)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('type_bulletin', 'trimestre')->exists();
            if($exists) {
                return [
                    "type" => "error",
                    "message" => "l'élève {$student->name} a déjà un bulletin pour le trimestre : {$trimestre->libelleTrimestre}"
                ];
            }
            // create new trimestrial bulletin
            Bulletin::create([
                'user_id' => $student->id,
                'classe_id' => $classe->id,
                'app_configuration_id' => appConfiguration()->id,
                'annee_scolaire_id' => getCurrentYear()->id,
                'type_bulletin' => 'trimestre',
                'trimestre_id' => $trimestre->id,
                'evaluation_id' => null,
                'discipline_stats' => json_encode($trimDisplineStats),
                'conseils_stats' => json_encode($trimConseilsStats),
                'appreciation' => $trimAppreciation,
                'average' => $trimAverage,
                'principal_class_teacher' => $princClassTeacherName
            ]);
            // update bulletins stats
            updateAllTrimReportCardStats(
                $classe->id,
                $trimestre->id,
                'trimestre',
                getCurrentYear()->id
            );
            // update trimestre notes
            $notes = Note::all()->where('classe_id', $classe->id)
            ->where('user_id', $student->id)
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->where('evaluation_id', $evaluation->id);
            foreach($notes as $note) {
                // update trims bulletin and stats
                updateTrimestreNotes(
                    $evaluation,
                    $classe->id,
                    $student->id,
                    $note->matiere_id
                );
            }
        }
        return [
            "type" => "success",
            "message" => "Bulletins du trimestre : {$trimestre->libelleTrimestre} de la classe de {$classe->libClasse} générés avec succès !"
        ];
    } catch (Exception $ex) {
        throw $ex;
    }
}

/**
 * update trimestrial notes
 */
function updateTrimestreNotes($evaluation, $classeId, $studentId, $matiereId) {
    try {
        $evaluationIds = Evaluation::where('trimestre_id',$evaluation->trimestre_id)->pluck('id');
        $notes = Note::all()->where('classe_id', $classeId)
            ->where('matiere_id', $matiereId)
            ->where('user_id', $studentId)
            ->whereIn('evaluation_id', $evaluationIds);
        $notesTable = [];
        foreach($notes as $note) {
            array_push($notesTable,$note);
        }
        // claculate :
        $eval1Note = 00.0;
        $eval2Note = 00.0;
        $note = 00.0;
        if(count($notesTable) !== 0) {
            $eval1Note = $notesTable[0]->note;
            $eval2Note = $notesTable[1]->note;
            $note = ($eval1Note + $eval2Note)/2;
        }
        // update or create the corresponding trimestrenote
        $trimNote = TrimestreNote::updateOrCreate(
            [
                'user_id' => $studentId,
                'matiere_id' => $matiereId,
                'classe_id' => $classeId,
                'trimestre_id' => $evaluation->trimestre_id,
                'annee_scolaire_id' => getCurrentYear()->id
            ],
            [
                'eval1_note' => $eval1Note,
                'eval2_note' => $eval2Note,
                'note' => $note,
                'appreciation' => getAppreciation($note),
            ]
        );
        // update trims notes for the class :
        updateTrimMinMaxRange($matiereId, $classeId, $evaluation->trimestre_id);
    } catch (Exception $ex) {
        throw $ex;
    }
}

/**
 * function to get the trimestrial notes
 */
function updateTrimMinMaxRange($matiereId, $classeId, $trimestreId) {
    try {
        $classeYearStudentsIds = ClasseAnneeScolaireStudent::all()
            ->where('annee_scolaire_id',getCurrentYear()->id)
            ->where('classe_id',$classeId)
            ->pluck('user_id');
        $notes = TrimestreNote::all()->where('matiere_id',$matiereId)
            ->where('trimestre_id',$trimestreId)
            ->where('annee_scolaire_id',getCurrentYear()->id)
            ->whereIn('user_id',$classeYearStudentsIds);
        $noteValues = [];
        foreach($notes as $note) {
            array_push($noteValues, $note->note);
        }
        $minValue = min($noteValues);
        $maxValue = max($noteValues);
        $gcma = getGeneralMoy($noteValues);
        //dd($notes);
        foreach($notes as $note) {
            $range = getRange($note->note, $noteValues);
            $note->update([
                'class_avg' => $gcma,
                'min_note' => $minValue,
                'max_note' => $maxValue,
                'rang' => $range,
            ]);
        }
    } catch (Exception $ex) {
        throw $ex;
    }
}

/**
 * update trimestrial report card stats
 */
function updateAllTrimReportCardStats($classeId, $trimestreId, $typeBulltin, $yearId) {
    try {
        $bulletins = Bulletin::where('classe_id',$classeId)
            ->where('type_bulletin',$typeBulltin)
            ->where('annee_scolaire_id',$yearId)
            ->where('trimestre_id',$trimestreId)->get();
        $bulletinValues = [];
        foreach($bulletins as $bulletin) {
            array_push($bulletinValues, $bulletin->average);
        }
        $minValue = min($bulletinValues);
        $maxValue = max($bulletinValues);
        $gcma = getGeneralMoy($bulletinValues);
        $sd = getStandardDeviation($bulletinValues);
        foreach($bulletins as $bulletin) {
            $range = getRange($bulletin->average, $bulletinValues);
            $bulletin->update([
                'min_average' => $minValue,
                'max_average' => $maxValue,
                'general_average' => $gcma,
                'standard_deviation' => $sd,
                'range' => $range,
            ]);
        }
    } catch (Exception $ex) {
        throw $ex;
    }
}