<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\AppConfiguration;
use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\CoefAnneeScolaire;
use App\Models\Coefficient;
use App\Models\Evaluation;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Trimestre;
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
        // querying
        $query = Bulletin::query();
        // filtering
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
     * generate bulletin for all students in specific classe
     */
    public function generate(Request $request) {
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $appConfig = AppConfiguration::first();
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
            ->where('annee_scolaire_id', $activeYear->id)
            ->first();
            $matieres = Matiere::whereIn('id', getCurrentYearCoefConfigurationMatId($activeYear->id, $classe->id))
                ->get();
            // check if each student have notes
            $userIds = ClasseAnneeScolaireStudent::where('user_id', $request->user_id)
                ->where('annee_scolaire_id', $activeYear->id)
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
                $notes = Note::where('user_id', $student->id)
                    ->where('evaluation_id', $evaluation->id)
                    ->where('classe_id', $classe->id)
                    ->get();
                $average = getAverage($activeYear->id, $notes);
                $appreciation = getAppreciation($average);
                $princClassTeacherName = getPrincipalClassTeacher($request->classe_id, $activeYear->id);
                $displineStats = getDisciplinesStats($student->disciplines);
                // checking if the bulletin already exists :
                $exists = Bulletin::where('classe_id', $request->classe_id)
                    ->where('user_id',$request->user_id)
                    ->where('trimestre_id', $request->trimestre_id)
                    ->where('evaluation_id', $request->evaluation_id)->exists();
                if($exists) {
                    return response()->json([
                        "error" => "l'élève {$student->name} a déjà un bulletin pour :
                        {$evaluation->libelleEvaluation}"
                    ]);
                }
                // create new sequenciel bulletin
                Bulletin::create([
                    'user_id' => $student->id,
                    'classe_id' => $request->classe_id,
                    'app_configuration_id' => $appConfig->id,
                    'annee_scolaire_id' => $activeYear->id,
                    'bulletin_file' => $student->bulletin_file,
                    'type_bulletin' => $request->type_bulletin,
                    'evaluation_id' => $request->evaluation_id,
                    'trimestre_id' => $request->trimestre_id,
                    'discipline_stats' => json_encode($displineStats),
                    'appreciation' => $appreciation,
                    'average' => $average,
                    'principal_class_teacher' => $princClassTeacherName
                ]);
                // update bulletins stats
                updateAllReportCardStats(
                    $classe->id,
                    $request->evaluation_id,
                    $request->trimestre_id,
                    $activeYear->id
                );
                return response()->json(["success" => "Bulletin généré avec succès !"]);
            }
            if ($request->option_type === "one" && $request->type_bulletin === 'trimestre') {
                // check if sequenciel bulletin of the corresponding trimestre exist
                $evaluations = Evaluation::all()->where('trimestre_id', $request->trimestre_id);
                $student = User::where('id', $request->user_id)->where('typeUser','eleve')
                    ->whereIn('id', $userIds)->first();
                    $evalBulletins = [];
                foreach($evaluations as $evaluation) {
                    $bulletin = Bulletin::all()->where('evaluation_id',$evaluation->id)
                        ->where('classe_id', $classe->id)
                        ->where('user_id', $student->id)->first();
                    if($bulletin->isEmpty()) {
                        return response()->json([
                            "error" => "Le bulletin de : {$evaluation->libelleEvaluation}
                            n'existe pas, impossible de générer le bulletin trimestriel!"
                        ]);
                    } else {
                        array_push($evalBulletins, $bulletin);
                    }
                }
                $trimAverage = getTrimAverage($evalBulletins);
                dd($trimAverage);
                $trimAppreciation = getAppreciation($trimAverage);
                $princClassTeacherName = getPrincipalClassTeacher($request->classe_id, $activeYear->id);
                $trimDisplineStats = getDisciplinesStats($student->disciplines);
                // checking if the bulletin already exists :
                $exists = Bulletin::where('classe_id', $request->classe_id)
                    ->where('user_id',$request->user_id)
                    ->where('trimestre_id', $request->trimestre_id)
                    ->where('type_bulletin', $request->type_bulletin)->exists();
                if($exists) {
                    return response()->json([
                        "error" => "l'élève ".$student->name." a déjà un bulletin pour le trimestre : {$trimetre->libelleTrimestre}"
                    ]);
                }
                // create new trimestrial bulletin
                Bulletin::create([
                    'user_id' => $student->id,
                    'classe_id' => $request->classe_id,
                    'app_configuration_id' => $appConfig->id,
                    'annee_scolaire_id' => $activeYear->id,
                    'type_bulletin' => $request->type_bulletin,
                    'trimestre_id' => $request->trimestre_id,
                    'discipline_stats' => json_encode($trimDisplineStats),
                    'appreciation' => $trimAppreciation,
                    'average' => $trimAverage,
                    'principal_class_teacher' => $princClassTeacherName
                ]);
                // update bulletins stats
                updateAllTrimReportCardStats(
                    $classe->id,
                    $request->trimestre_id,
                    $request->type_bulletin,
                    $activeYear->id
                );
            }
            if ($request->option_type === "one" && $request->type_bulletin === 'annuel') {
            }
            // generate for a class
            if (
                $request->option_type === "all" &&
                $request->type_bulletin === 'sequenciel' &&
                isset($request->evaluation_id)
            ) {
                $allUsersIds = ClasseAnneeScolaireStudent::all()->where('annee_scolaire_id', $activeYear->id)
                    ->where('classe_id', $classe->id)->pluck('user_id');
                $students = User::all()->where('typeUser','eleve')
                    ->whereIn('id', $allUsersIds);
                foreach ($students as $student) {
                    $notes = Note::where('user_id', $student->id)
                        ->where('evaluation_id', $evaluation->id)
                        ->where('classe_id', $classe->id)
                        ->get();
                    $average = getAverage($activeYear->id, $notes);
                    $appreciation = getAppreciation($average);
                    $princClassTeacherName = getPrincipalClassTeacher($request->classe_id, $activeYear->id);
                    $displineStats = getDisciplinesStats($student->disciplines);
                    $exists = Bulletin::where('classe_id', $request->classe_id)
                        ->where('user_id',$student->id)
                        ->where('trimestre_id', $request->trimestre_id)
                        ->where('evaluation_id', $request->evaluation_id)->exists();
                    if($exists) {
                        return response()->json([
                            "error" => "l'élève ".$student->name." a déjà un bulletin pour cette séquence"
                        ]);
                    }
                    Bulletin::create([
                        'user_id' => $student->id,
                        'classe_id' => $request->classe_id,
                        'app_configuration_id' => $appConfig->id,
                        'annee_scolaire_id' => $activeYear->id,
                        'bulletin_file' => $student->bulletin_file,
                        'type_bulletin' => $request->type_bulletin,
                        'evaluation_id' => $request->evaluation_id,
                        'trimestre_id' => $request->trimestre_id,
                        'discipline_stats' => json_encode($displineStats),
                        'appreciation' => $appreciation,
                        'average' => $average,
                        'principal_class_teacher' => $princClassTeacherName
                    ]);
                    updateAllReportCardStats(
                        $classe->id,
                        $request->evaluation_id,
                        $request->trimestre_id,
                        $activeYear->id
                    );
                }
                return response()->json(["success" => "Bulletins générés avec succès !"]);
            }
            if ($request->option_type === "all" && $request->type_bulletin === 'trimestre') {
                dd($request);
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
        //return view('bulletin.annual', compact('user'));
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
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $studentsIds = ClasseAnneeScolaireStudent::all()->where('classe_id', $classeId)
            ->where('annee_scolaire_id', $activeYear->id)->pluck('user_id');
        $students = User::where('typeUser','eleve')->whereIn('id', $studentsIds)->get();
        return response()->json($students);
    }

    /**
     * get evaluations base on a specific trimestre id
     */
    public function getEvaluations($trimId) {
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $trim = Trimestre::all()->where('id', $trimId)
            ->where('annee_scolaire_id', $activeYear->id)->first();
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
        $firstGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('1er groupe', $activeYear->id, $bulletin->classe_id))->pluck('id');
        $sndGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('2e groupe', $activeYear->id, $bulletin->classe_id))->pluck('id');
        $thirdGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('3e groupe', $activeYear->id, $bulletin->classe_id))->pluck('id');
        // gettings notes by groups
        $userIds = ClasseAnneeScolaireStudent::where('user_id', $bulletin->user_id)
                ->where('annee_scolaire_id', $activeYear->id)->where('classe_id', $bulletin->classe_id)->pluck('user_id');
        $student = User::where('id', $bulletin->user_id)->where('typeUser','eleve')
                ->whereIn('id', $userIds)->first();
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
        // for trimestre :
        $groupsNotes = [];
        // decode discplines
        $disciplines = json_decode($bulletin->discipline_stats);
        return view(
            'bulletin.user-report-card',
            compact('bulletin','studentNotesFirstGroup','studentNotesSndGroup','studentNotesThirdGroup','disciplines')
        );
    }
}
