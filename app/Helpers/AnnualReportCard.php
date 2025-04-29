<?php

use App\Models\AnnualNote;
use App\Models\Bulletin;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\Evaluation;
use App\Models\Note;
use App\Models\Trimestre;
use App\Models\User;

/**
 * generate single student annual report card
 */
function generateSingleAnnualReportCard($student, $classe) {
    try {
        // get alls year evaluation
        $trimestres = Trimestre::all()->where('annee_scolaire_id', getCurrentYear()->id);
        $trimestresIds = $trimestres->pluck('id');
        $evaluations = Evaluation::all()->whereIn('trimestre_id', $trimestresIds);
        $bulletinsAvgs = [];
        foreach($trimestresIds as $trimId) {
            $bulletin = Bulletin::where('trimestre_id', $trimId)
                ->where('classe_id', $classe->id)
                ->where('annee_scolaire_id',getCurrentYear()->id)
                ->where('type_bulletin', 'trimestre')
                ->where('user_id', $student->id)->first();
            if(!$bulletin) {
                $currentTrimestre = Trimestre::where('trimestre_id', $trimId);
                return [
                    "type" => "error",
                    "message" => "Le bulletin de {$currentTrimestre->libelleTrimestre}
                        de l'élève {$student->name} n'existe pas, impossible de générer le bulletin annuel !"
                ];
            } else {
                array_push($bulletinsAvgs, $bulletin->average);
            }
        }
        $annualAverage = getTrimAverage($bulletinsAvgs);
        $annualAppreciation = getAppreciation($annualAverage);
        $princClassTeacherName = getPrincipalClassTeacher($classe->id, getCurrentYear()->id);
        $annualDisplineStats = getDisciplinesStats($student->disciplines);
        $annualConseilsStats = getConseilsStats($student->conseils_disciplines);
        // checking if the bulletin already exists :
        $exists = Bulletin::where('classe_id', $classe->id)
            ->where('user_id',$student->id)
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->where('type_bulletin', 'annuel')->exists();
        if($exists) {
            return [
                "type" => "error",
                "message" => "l'élève {$student->name} a déjà un bulletin annuel pour l'année : ". getCurrentYear()->libelleAnneeScolaire
            ];
        }
        // update annual notes
        foreach($evaluations as $evaluation) {
            $notes = Note::all()->where('classe_id', $classe->id)
                ->where('user_id', $student->id)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('evaluation_id', $evaluation->id);
            foreach($notes as $note) {
                updateAnnualNotes(
                    $classe->id,
                    $student->id,
                    $note->matiere_id
                );
            }
        }
        // create new annual bulletin
        Bulletin::create([
            'user_id' => $student->id,
            'classe_id' => $classe->id,
            'app_configuration_id' => appConfiguration()->id,
            'annee_scolaire_id' => getCurrentYear()->id,
            'type_bulletin' => 'annuel',
            'evaluation_id' => null,
            'trimestre_id' => null,
            'discipline_stats' => json_encode($annualDisplineStats),
            'conseils_stats' => json_encode($annualConseilsStats),
            'appreciation' => $annualAppreciation,
            'average' => $annualAverage,
            'principal_class_teacher' => $princClassTeacherName
        ]);
        // update bulletins stats
        updateAllAnnualReportCardStats(
            $classe->id,
            'annuel',
            getCurrentYear()->id
        );
        return [
            "type" => "success",
            "message" => "Bulletin Annuel de {$student->name} généré avec succès !"
        ];
    } catch (Exception $ex) {
        throw $ex;
    }
}

/**
 * generate all report cards for a specific class
 */
function generateAllAnnualReportCard($classe) {
    try {
        $allUsersIds = ClasseAnneeScolaireStudent::all()
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->where('classe_id', $classe->id)->pluck('user_id');
        $students = User::all()->where('typeUser','eleve')
            ->whereIn('id', $allUsersIds);
        foreach ($students as $student) {
            // get alls year evaluation
            $trimestres = Trimestre::all()->where('annee_scolaire_id', getCurrentYear()->id);
            $trimestresIds = $trimestres->pluck('id');
            $evaluations = Evaluation::all()->whereIn('trimestre_id', $trimestresIds);
            $bulletinsAvgs = [];
            foreach($trimestresIds as $trimId) {
                $bulletin = Bulletin::where('trimestre_id', $trimId)
                    ->where('classe_id', $classe->id)
                    ->where('annee_scolaire_id',getCurrentYear()->id)
                    ->where('type_bulletin', 'trimestre')
                    ->where('user_id', $student->id)->first();
                if(!$bulletin) {
                    $currentTrimestre = Trimestre::where('trimestre_id', $trimId);
                    return [
                        "type" => "error",
                        "message" => "Le bulletin de {$currentTrimestre->libelleTrimestre}
                            de l'élève {$student->name} n'existe pas, impossible de générer le bulletin annuel !"
                    ];
                } else {
                    array_push($bulletinsAvgs, $bulletin->average);
                }
            }
            $annualAverage = getTrimAverage($bulletinsAvgs);
            $annualAppreciation = getAppreciation($annualAverage);
            $princClassTeacherName = getPrincipalClassTeacher($classe->id, getCurrentYear()->id);
            $annualDisplineStats = getDisciplinesStats($student->disciplines);
            $annualConseilsStats = getConseilsStats($student->conseils_disciplines);
            // checking if the bulletin already exists :
            $exists = Bulletin::where('classe_id', $classe->id)
                ->where('user_id',$student->id)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('type_bulletin', 'annuel')->exists();
            if($exists) {
                return [
                    "type" => "error",
                    "message" => "l'élève {$student->name} a déjà un bulletin annuel pour l'année : ". getCurrentYear()->libelleAnneeScolaire
                ];
            }
            // update annual notes
            foreach($evaluations as $evaluation) {
                $notes = Note::all()->where('classe_id', $classe->id)
                    ->where('user_id', $student->id)
                    ->where('annee_scolaire_id', getCurrentYear()->id)
                    ->where('evaluation_id', $evaluation->id);
                foreach($notes as $note) {
                    updateAnnualNotes(
                        $classe->id,
                        $student->id,
                        $note->matiere_id
                    );
                }
            }
            // create new annual bulletin
            Bulletin::create([
                'user_id' => $student->id,
                'classe_id' => $classe->id,
                'app_configuration_id' => appConfiguration()->id,
                'annee_scolaire_id' => getCurrentYear()->id,
                'type_bulletin' => 'annuel',
                'evaluation_id' => null,
                'trimestre_id' => null,
                'discipline_stats' => json_encode($annualDisplineStats),
                'conseils_stats' => json_encode($annualConseilsStats),
                'appreciation' => $annualAppreciation,
                'average' => $annualAverage,
                'principal_class_teacher' => $princClassTeacherName
            ]);
            // update bulletins stats
            updateAllAnnualReportCardStats(
                $classe->id,
                'annuel',
                getCurrentYear()->id
            );
        }
        return [
            "type" => "success",
            "message" => "Bulletins Annuels de la classe de : $classe->libClasse générés avec succès !"
        ];
    } catch (Exception $ex) {
        throw $ex;
    }
}

/**
 * update all annual notes
 */
function updateAnnualNotes($classeId, $studentId, $matiereId) {
    try {
        $trimestres = Trimestre::where('annee_scolaire_id', getCurrentYear()->id);
        $trimestresId = $trimestres->pluck('id');
        $evaluationIds = Evaluation::whereIn('trimestre_id',$trimestresId)->pluck('id');
        $notes = Note::all()->where('classe_id', $classeId)
            ->where('matiere_id', $matiereId)
            ->where('user_id', $studentId)
            ->whereIn('evaluation_id', $evaluationIds);
        $notesTable = [];
        foreach($notes as $note) {
            array_push($notesTable,$note);
        }
        $eval1Note = 00.0;
        $eval2Note = 00.0;
        $eval3Note = 00.0;
        $eval4Note = 00.0;
        $eval5Note = 00.0;
        $eval6Note = 00.0;
        $note = 00.0;
        if(count($notesTable) !== 0) {
            $eval1Note = $notesTable[0]->note;
            $eval2Note = $notesTable[1]->note;
            $eval3Note = $notesTable[2]->note;
            $eval4Note = $notesTable[3]->note;
            $eval5Note = $notesTable[4]->note;
            $eval6Note = $notesTable[5]->note;
            $note = ($eval1Note + $eval2Note + $eval3Note + $eval4Note + $eval5Note + $eval6Note)/
            count($notesTable);
        }
        // update or create the corresponding annual note
        $annualNote = AnnualNote::updateOrCreate(
            [
                'user_id' => $studentId,
                'matiere_id' => $matiereId,
                'classe_id' => $classeId,
                'annee_scolaire_id' => getCurrentYear()->id
            ],
            [
                'eval1_note' => $eval1Note,
                'eval2_note' => $eval2Note,
                'eval3_note' => $eval3Note,
                'eval4_note' => $eval4Note,
                'eval5_note' => $eval5Note,
                'eval6_note' => $eval6Note,
                'note' => $note,
                'appreciation' => getAppreciation($note),
            ]
        );
        // update annual notes min max range for the class :
        updateAnnualMinMaxRange($matiereId, $classeId);
    } catch (Exception $ex) {
        throw $ex;
    }
}

/**
 * update annual report card stats
 */
function updateAllAnnualReportCardStats($classeId, $typeBulltin, $yearId) {
    try {
        $bulletins = Bulletin::where('classe_id',$classeId)
            ->where('type_bulletin',$typeBulltin)
            ->where('annee_scolaire_id',$yearId)->get();
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

/**
 * updating annual note stats
 */
function updateAnnualMinMaxRange($matiereId, $classeId) {
    try {
        $classeYearStudentsIds = ClasseAnneeScolaireStudent::all()
            ->where('annee_scolaire_id',getCurrentYear()->id)
            ->where('classe_id',$classeId)
            ->pluck('user_id');
        $notes = AnnualNote::all()->where('matiere_id',$matiereId)
            ->where('annee_scolaire_id',getCurrentYear()->id)
            ->whereIn('user_id',$classeYearStudentsIds);
        $noteValues = [];
        foreach($notes as $note) {
            array_push($noteValues, $note->note);
        }
        $minValue = min($noteValues);
        $maxValue = max($noteValues);
        $gcma = getGeneralMoy($noteValues);
        foreach($notes as $note) {
            $range = getRange($note->note, $noteValues);
            $note->update([
                'mgc' => $gcma,
                'min_note' => $minValue,
                'max_note' => $maxValue,
                'rang' => $range,
            ]);
        }
    } catch (Exception $ex) {
        throw $ex;
    }
}