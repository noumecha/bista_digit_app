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
use Barryvdh\DomPDF\Facade\Pdf;
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
        // load schoolYear
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        // load appConfiguration
        $appConfig = AppConfiguration::first();
        //$request->validate(
        $rules = [
            'evaluation_id' => 'required',
            'trimestre_id' => 'required',
            'classe_id' => 'required',
            'type_bulletin' => 'required',
            'option_type' => 'required',
            'user_id' => 'nullable',
        ];

        $request->validate(array_merge($rules, [
            'user_id' => $request->option_type === "one" ? 'required' : 'nullable'
        ]), [
            'classe_id.required' => 'Veuillez sélectionner une classe',
            'evaluation_id.required' => 'Veuillez sélectionner une évaluation',
            'trimestre_id.required' => 'Veuillez sélectionner un trimestre',
            'type_bulletin.required' => 'Veuillez sélectionner le type de bulletin',
            'option_type.required' => 'Veuillez sélectionner une option',
            'user_id.required' => 'Veuillez sélectionner élève',
        ]);

        try {
            // getting classe and evaluation
            $classe = Classe::findOrFail($request->classe_id);
            $evaluation = Evaluation::findOrFail($request->evaluation_id)
                ->where('trimestre_id', $request->trimestre_id)->first();
            $trimestre = Trimestre::findOrFail($request->trimestre_id);
            $matieres = Matiere::whereIn('id', getCurrentYearCoefConfigurationMatId($activeYear->id, $classe->id))
                ->get();
            // check if all or a user in the specified class as note in every corresponding evaluation matiere
            $userIds = ClasseAnneeScolaireStudent::where('user_id', $request->user_id)
                ->where('annee_scolaire_id', $activeYear->id)->where('classe_id', $classe->id)->pluck('user_id');
            if($request->option_type === "one" && isset($request->user_id)) {
                $student = User::where('id', $request->user_id)->where('typeUser','eleve')
                    ->whereIn('id', $userIds)->first();
                foreach ($matieres as $matiere) {
                    $note = Note::where('user_id', $student->id)
                    ->where('matiere_id', $matiere->id)
                    ->where('classe_id', $classe->id)
                    ->where('evaluation_id', $evaluation->id)->exists();
                    if(!$note) {
                        return response()->json(["error" => "L'élève {$student->name} n'a pas de note en {$matiere->libelleMatiere}."]);
                    }
                }
            } else {
                foreach ($classe->students as $student) {
                    foreach ($matieres as $matiere) {
                        if (!Note::where('user_id', $student->id)
                                ->where('matiere_id', $matiere->id)
                                ->where('evaluation_id', $evaluation->id)
                                ->exists()) {
                            return back()->with('error', "L'élève {$student->name} n'a pas de note en {$matiere->libelleMatiere}.");
                        }
                    }
                }
            }
            // determinate the notes by group
            $firstGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('1er groupe', $activeYear->id))->pluck('id');
            $sndGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('2e groupe', $activeYear->id))->pluck('id');
            $thirdGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('3e groupe', $activeYear->id))->pluck('id');
            if($request->option_type === "one" && isset($request->user_id)) {
                $student = User::where('id', $request->user_id)->where('typeUser','eleve')
                ->whereIn('id', $userIds)->first();
                /* getting all notes of the specific student */
                $notes = Note::where('user_id', $student->id)
                ->where('evaluation_id', $evaluation->id)
                ->where('classe_id', $classe->id)
                ->get();
                /** make the necessary calculation */
                $average = getAverage($activeYear->id, $notes);
                $appreciation = getAppreciation($average);
                $princClassTeacherName = getPrincipalClassTeacher($request->classe_id, $activeYear->id);
                $displineStats = getDisciplinesStats($student->disciplines);
                // create bulletin base on the selected type
                if($request->type_bulletin === 'sequenciel') {
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
                }
                if ($request->type_bulletin === 'trimestre') {
                }
                if ($request->type_bulletin === 'annuel') {
                }
            } else {
            }
            return response()->json(["success" => "Bulletin(s) généré(s) avec succès !"]);
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

        //return view('bulletin.annual', compact('user'));
        return view('bulletin.evaluation', compact('user'));
    }

    /**
     * Bulletin deletion
     */
    public function destroy($id) {
        $bulletin = Bulletin::findOrFail($id);
        $bulletin->delete();
        return response()->json(['deleteSuccess' => 'Bulletin supprimé avec succès!']);
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
        $firstGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('1er groupe', $activeYear->id))->pluck('id');
        $sndGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('2e groupe', $activeYear->id))->pluck('id');
        $thirdGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('3e groupe', $activeYear->id))->pluck('id');
        // gettings notes by groups
        $userIds = ClasseAnneeScolaireStudent::where('user_id', $bulletin->user_id)
                ->where('annee_scolaire_id', $activeYear->id)->where('classe_id', $bulletin->classe_id)->pluck('user_id');
        $student = User::where('id', $bulletin->user_id)->where('typeUser','eleve')
                ->whereIn('id', $userIds)->first();
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

}
