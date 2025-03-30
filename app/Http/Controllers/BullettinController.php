<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\AppConfiguration;
use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\CoefAnneeScolaire;
use App\Models\Coefficient;
use App\Models\Discipline;
use App\Models\Evaluation;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Trimestre;
use App\Models\TrimestreNote;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BullettinController extends Controller
{
    /**
     * Bulletin lists
     */
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $evaluations = Evaluation::all();
        $trimestres = Trimestre::all();
        $classes = Classe::all();
        // loading app configuration :
        $appconfiguration = AppConfiguration::all()->last();
        // filter vars
        $evaluationFilter = $request->input('evaluationFilter');
        $trimestreFilter = $request->input('trimestreFilter');
        $classFilter = $request->input('classFilter');
        $searchStudent = $request->input('searchStudent');
        $typeFilter = $request->input('typeFilter');
        // querying
        $query = Bulletin::query();
        // filtering
        if(!empty($typeFilter)) {
            $query->where('type_bulletin', $typeFilter);
        }
        if(!empty($evaluationFilter)) {
            $query->where('evaluation_id', $evaluationFilter);
        }
        if(!empty($trimestreFilter)) {
            $query->where('trimestre_id', $trimestreFilter);
        }
        if(!empty($classFilter)) {
            $query->where('classe_id', $classFilter);
        }
        if(!empty($searchStudent)) {
            $query->whereHas('user_id', function ($q) use ($searchStudent) {
                $q->where('name', 'LIKE', "%{$searchStudent}%")
                ->orWhere('surname', 'LIKE', "%{$searchStudent}%");
            });
        }

        $bulletins = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._bulletins_table', compact('bulletins','appconfiguration','trimestres','evaluations','classes','user'));
        } else {
            return view('bulletin.bulletins', compact('bulletins','appconfiguration','trimestres','evaluations','classes','user'));
        }
    }

    /**
     * generate bulletin for single student in a specific class
     */
    public function generateSingle($student, $classe, $evaluation, $typeBulletin, $trimestre) {
        try {
            $appConfig = AppConfiguration::first();
            if($typeBulletin === 'sequenciel') {
                $notes = Note::where('user_id', $student->id)
                        ->where('evaluation_id', $evaluation->id)
                        ->where('classe_id', $classe->id)
                        ->get();
                $average = getAverage(getCurrentYear()->id, $notes);
                $appreciation = getAppreciation($average);
                $princClassTeacherName = getPrincipalClassTeacher($classe->id, getCurrentYear()->id);
                $displineStats = getDisciplinesStats($student->disciplines);
                // checking if the bulletin already exists :
                $exists = Bulletin::where('classe_id', $classe->id)
                    ->where('user_id',$student->id)
                    ->where('trimestre_id', $trimestre->id)
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
                    'app_configuration_id' => $appConfig->id,
                    'annee_scolaire_id' => getCurrentYear()->id,
                    'bulletin_file' => $student->bulletin_file,
                    'type_bulletin' => $typeBulletin,
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
                return [
                    "type" => "error",
                    "message" => "Bulletin {$typeBulletin} de {$student->name} généré avec succès !"
                ];
            }
            if($typeBulletin === 'trimestre') {
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
                // checking if the bulletin already exists :
                $exists = Bulletin::where('classe_id', $classe->id)
                    ->where('user_id',$student->id)
                    ->where('trimestre_id', $trimestre->id)
                    ->where('annee_scolaire_id', getCurrentYear()->id)
                    ->where('type_bulletin', $typeBulletin)->exists();
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
                    'app_configuration_id' => $appConfig->id,
                    'annee_scolaire_id' => getCurrentYear()->id,
                    'type_bulletin' => $typeBulletin,
                    'trimestre_id' => $trimestre->id,
                    'evaluation_id' => null,
                    'discipline_stats' => json_encode($trimDisplineStats),
                    'appreciation' => $trimAppreciation,
                    'average' => $trimAverage,
                    'principal_class_teacher' => $princClassTeacherName
                ]);
                // update bulletins stats
                updateAllTrimReportCardStats(
                    $classe->id,
                    $trimestre->id,
                    $typeBulletin,
                    getCurrentYear()->id
                );
                return [
                    "type" => "success",
                    "message" => "Bulletin {$typeBulletin} de {$student->name} généré avec succès !"
                ];
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * generate bulletin for all student in a specific class
     */
    public function generateAll($classe, $evaluation, $typeBulletin, $trimestre) {
        try {
            $appConfig = AppConfiguration::first();
            $allUsersIds = ClasseAnneeScolaireStudent::all()->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('classe_id', $classe->id)->pluck('user_id');
            $students = User::all()->where('typeUser','eleve')
                ->whereIn('id', $allUsersIds);
            if (
                $typeBulletin === 'sequenciel'
            ) {
                foreach ($students as $student) {
                    $notes = Note::where('user_id', $student->id)
                        ->where('evaluation_id', $evaluation->id)
                        ->where('classe_id', $classe->id)
                        ->get();
                    $average = getAverage(getCurrentYear()->id, $notes);
                    $appreciation = getAppreciation($average);
                    $princClassTeacherName = getPrincipalClassTeacher($classe->id, getCurrentYear()->id);
                    $displineStats = getDisciplinesStats($student->disciplines);
                    $exists = Bulletin::where('classe_id', $classe->id)
                        ->where('user_id',$student->id)
                        ->where('trimestre_id', $trimestre->id)
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
                        'app_configuration_id' => $appConfig->id,
                        'annee_scolaire_id' => getCurrentYear()->id,
                        'bulletin_file' => $student->bulletin_file,
                        'type_bulletin' => $typeBulletin,
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
            }
            if($typeBulletin === 'trimestre') {
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
                    // checking if the bulletin already exists :
                    $exists = Bulletin::where('classe_id', $classe->id)
                        ->where('user_id',$student->id)
                        ->where('trimestre_id', $trimestre->id)
                        ->where('annee_scolaire_id', getCurrentYear()->id)
                        ->where('type_bulletin', $typeBulletin)->exists();
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
                        'app_configuration_id' => $appConfig->id,
                        'annee_scolaire_id' => getCurrentYear()->id,
                        'type_bulletin' => $typeBulletin,
                        'trimestre_id' => $trimestre->id,
                        'evaluation_id' => null,
                        'discipline_stats' => json_encode($trimDisplineStats),
                        'appreciation' => $trimAppreciation,
                        'average' => $trimAverage,
                        'principal_class_teacher' => $princClassTeacherName
                    ]);
                    // update bulletins stats
                    updateAllTrimReportCardStats(
                        $classe->id,
                        $trimestre->id,
                        $typeBulletin,
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
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * generate bulletin for all students in specific classe
     */
    public function generate(Request $request) {
        $rules = [
            'evaluation_id' => 'nullable',
            'trimestre_id' => 'nullable',
            'classe_id' => 'required',
            'type_bulletin' => 'required',
            'option_type' => 'required',
            'user_id' => 'nullable',
        ];

        $request->validate(array_merge($rules, [
            'user_id' => $request->option_type === "one" ? 'required' : 'nullable',
            'evaluation_id' => $request->type_bulletin === "trimestre" || $request->type_bulletin === "annuel"
            ? 'nullable' : 'required',
            'trimestre_id' => $request->type_bulletin === "annuel" ? 'nullable' : 'required'
        ]), [
            'classe_id.required' => 'Veuillez sélectionner une classe',
            'evaluation_id.required' => 'Veuillez sélectionner une évaluation',
            'trimestre_id.required' => 'Veuillez sélectionner un trimestre',
            'type_bulletin.required' => 'Veuillez sélectionner le type de bulletin',
            'option_type.required' => 'Veuillez sélectionner une option',
            'user_id.required' => 'Veuillez sélectionner élève',
        ]);
        // now create new bulletin base on data :
        try {
            // getting classe, evaluation and matieres
            $classe = Classe::findOrFail($request->classe_id);
            $evaluation = $request->type_bulletin === 'sequenciel' ?
                Evaluation::where('id',$request->evaluation_id)
                    ->where('trimestre_id', $request->trimestre_id)->first()
                : null;
            $trimestre = Trimestre::where('id', $request->trimestre_id)
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->first();
            $matieres = Matiere::whereIn('id', getCurrentYearCoefConfigurationMatId(getCurrentYear()->id, $classe->id))
                ->get();
            // check if each student have notes
            $userIds = ClasseAnneeScolaireStudent::where('user_id', $request->user_id)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('classe_id', $classe->id)->pluck('user_id');
            if(
                $request->option_type === "one" &&
                $request->type_bulletin === 'sequenciel' &&
                isset($request->user_id) && isset($request->evaluation_id)
            ) {
                $student = User::where('id', $request->user_id)->where('typeUser','eleve')
                    ->whereIn('id', $userIds)->first();
                foreach ($matieres as $matiere) {
                    $note = Note::where('user_id', $student->id)
                        ->where('matiere_id', $matiere->id)
                        ->where('classe_id', $classe->id)
                        ->where('evaluation_id', $evaluation->id)->exists();
                    if(!$note) {
                        return response()->json([
                            "error" => "L'élève {$student->name} n'a pas de note en {$matiere->libelleMatiere}
                            pour l'évaluation {$evaluation->libelleEvaluation}"
                        ]);
                    }
                }
            }
            // generate for one student
            if(
                $request->option_type === "one" &&
                $request->type_bulletin === 'sequenciel' &&
                isset($request->user_id)
            ) {
                $student = User::where('id', $request->user_id)->where('typeUser','eleve')
                ->whereIn('id', $userIds)->first();
                $result = $this->generateSingle($student, $classe, $evaluation, $request->type_bulletin, $trimestre);
                return response()->json([
                    $result["type"] => $result["message"]
                ]);
            }
            if ($request->option_type === "one" && $request->type_bulletin === 'trimestre') {
                $student = User::where('id', $request->user_id)->where('typeUser','eleve')
                    ->whereIn('id', $userIds)->first();
                $result = $this->generateSingle($student, $classe, $evaluation, $request->type_bulletin, $trimestre);
                return response()->json([
                    $result["type"] => $result["message"]
                ]);
            }
            if ($request->option_type === "one" && $request->type_bulletin === 'annuel') {
            }
            // generate for a class
            if (
                $request->option_type === "all" &&
                $request->type_bulletin === 'sequenciel' &&
                isset($request->evaluation_id)
            ) {
                $result = $this->generateAll($classe, $evaluation, $request->type_bulletin, $trimestre);
                return response()->json([
                    $result["type"] => $result["message"]
                ]);
            }
            if ($request->option_type === "all" && $request->type_bulletin === 'trimestre') {
                $result = $this->generateAll($classe, $evaluation, $request->type_bulletin, $trimestre);
                return response()->json([
                    $result["type"] => $result["message"]
                ]);
            }
            if ($request->option_type === "all" && $request->type_bulletin === 'annuel') {
                dd($request);
            }

        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * update a specific bulletin (regenerate it)
     */
    public function udpate(Request $request) {
        dd($request);
    }

    /**
     * Bulletin configuration - only for test purpose
     */
    public function configs()
    {
        $user = User::find(Auth::id());
        $bulletin = Bulletin::findOrFail(11);
        return view('bulletin.trimestrielle', compact(['user','bulletin']));
    }

    /**
     * delete bulletin forever
     */
    public function destroy($id) {
        $bulletin = Bulletin::findOrFail($id);
        $bulletin->delete();
        return redirect()->route('bulletins.list')->with('deleteSuccess', 'Bulletin supprimé avec succès!');
    }

    /**
     * get students base on a specific class id
     */
    public function getStudents($classeId) {
        $studentsIds = ClasseAnneeScolaireStudent::all()->where('classe_id', $classeId)
            ->where('annee_scolaire_id', getCurrentYear()->id)->pluck('user_id');
        $students = User::where('typeUser','eleve')->whereIn('id', $studentsIds)->get();
        return response()->json($students);
    }

    /**
     * get evaluations base on a specific trimestre id
     */
    public function getEvaluations($trimId) {
        $trim = Trimestre::all()->where('id', $trimId)
            ->where('annee_scolaire_id', getCurrentYear()->id)->first();
        $evaluations = Evaluation::where('trimestre_id', $trim->id)->get();
        return response()->json($evaluations);
    }

    /**
     * preview bulletin
     */
    public function preview($id)
    {
        $bulletin = Bulletin::findOrFail($id);
        // load schoolYear
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        // determinate the notes by group
        $firstGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('1er groupe', getCurrentYear()->id, $bulletin->classe_id))->pluck('id');
        $sndGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('2e groupe', getCurrentYear()->id, $bulletin->classe_id))->pluck('id');
        $thirdGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('3e groupe', getCurrentYear()->id, $bulletin->classe_id))->pluck('id');
        // gettings notes by groups
        $userIds = ClasseAnneeScolaireStudent::where('user_id', $bulletin->user_id)
                ->where('annee_scolaire_id', getCurrentYear()->id)->where('classe_id', $bulletin->classe_id)->pluck('user_id');
        $student = User::where('id', $bulletin->user_id)->where('typeUser','eleve')
                ->whereIn('id', $userIds)->first();
        if($bulletin->type_bulletin === 'sequenciel') {
            // update disciplines stats first
            $studentDisciplines = Discipline::all()->where('user_id', $student->id)
                ->where('evaluation_id',$bulletin->evaluation->id);
            $displineStats = getDisciplinesStats($studentDisciplines);
            $bulletin->update([
                'discipline_stats' => json_encode($displineStats),
            ]);
            // all groups matieres datas
            $studentNotesFirstGroup = Note::where('user_id', $student->id)
                ->where('evaluation_id', $bulletin->evaluation_id)
                ->where('classe_id', $bulletin->classe_id)
                ->whereIn('matiere_id', $firstGroupMatiereIds)
                ->get();
            $studentNotesSndGroup = Note::where('user_id', $student->id)
                ->where('evaluation_id', $bulletin->evaluation_id)
                ->where('classe_id', $bulletin->classe_id)
                ->whereIn('matiere_id', $sndGroupMatiereIds)
                ->get();
            $studentNotesThirdGroup = Note::where('user_id', $student->id)
                ->where('evaluation_id', $bulletin->evaluation_id)
                ->where('classe_id', $bulletin->classe_id)
                ->whereIn('matiere_id', $thirdGroupMatiereIds)
                ->get();
            // decode discplines
            $disciplines = json_decode($bulletin->discipline_stats);
            return view(
                'bulletin.user-report-card',
                compact('bulletin','studentNotesFirstGroup','studentNotesSndGroup','studentNotesThirdGroup','disciplines')
            );
        }
        // for trimestre :
        if($bulletin->type_bulletin === "trimestre") {
            $disciplines = [];
            $bulletinsAvgs = [];
            $sequencialBulletins = Bulletin::all()->where('trimestre_id', $bulletin->trimestre_id)
                ->where('type_bulletin', "sequenciel")
                ->where('user_id', $bulletin->user_id)
                ->where('classe_id', $bulletin->classe_id)
                ->where('annee_scolaire_id', getCurrentYear()->id);
            // getting something :
            foreach($sequencialBulletins as $key => $evalBulletin) {
                // update disciplines stats first
                $studentDisciplines = Discipline::all()->where('user_id', $student->id)
                    ->where('evaluation_id',$evalBulletin->evaluation->id)
                    ->where('classe_id', $evalBulletin->classe_id);
                $displineStats = getDisciplinesStats($studentDisciplines);
                $evalBulletin->update([
                    'discipline_stats' => json_encode($displineStats),
                ]);
                // try to implements something to update trimestre note data before rendering the bulletin
                $notes = Note::all()->where('classe_id', $evalBulletin->classe_id)
                    ->where('user_id', $student->id)
                    ->where('annee_scolaire_id', getCurrentYear()->id)
                    ->where('evaluation_id', $evalBulletin->evaluation->id);
                foreach($notes as $note) {
                    updateTrimestreNotes(
                        $evalBulletin->evaluation,
                        $evalBulletin->classe_id,
                        $student->id,
                        $note->matiere_id
                    );
                }
                // create the discipline data
                array_push($disciplines, json_decode($evalBulletin->discipline_stats));
                // bulletins average :
                array_push($bulletinsAvgs, [
                    "evaluation_name" => $evalBulletin->evaluation->libelleEvaluation,
                    "evaluation_average" => $evalBulletin->average
                ]);
            }
            // all groups matieres notes
            $studentNotesFirstGroup = TrimestreNote::where('user_id', $student->id)
                ->where('trimestre_id', $bulletin->trimestre_id)
                ->where('classe_id', $bulletin->classe_id)
                ->whereIn('matiere_id', $firstGroupMatiereIds)->get();
            $studentNotesSndGroup = TrimestreNote::where('user_id', $student->id)
                ->where('trimestre_id', $bulletin->trimestre_id)
                ->where('classe_id', $bulletin->classe_id)
                ->whereIn('matiere_id', $sndGroupMatiereIds)->get();
            $studentNotesThirdGroup = TrimestreNote::where('user_id', $student->id)
                ->where('trimestre_id', $bulletin->trimestre_id)
                ->where('classe_id', $bulletin->classe_id)
                ->whereIn('matiere_id', $thirdGroupMatiereIds)->get();
            return view(
                'bulletin.user-report-card',
                compact('bulletin','bulletinsAvgs','studentNotesFirstGroup','studentNotesSndGroup','studentNotesThirdGroup','disciplines')
            );
        }
        // for annual :
        if($bulletin->type_bulletin === "annuel") {
            dd($bulletin);
        }
    }
}
