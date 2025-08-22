<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\AnnualNote;
use App\Models\AppConfiguration;
use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\ConseilDiscipline;
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
        $evaluations = Evaluation::all()->where('type','normal-evaluation');
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
                    ->where('type','normal-evaluation')
                    ->where('trimestre_id', $request->trimestre_id)->first()
                : null;
            $trimestre = Trimestre::where('id', $request->trimestre_id)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->first();
            $matieres = Matiere::whereIn('id', getCurrentYearCoefConfigurationMatId(getCurrentYear()->id, $classe->id))
                ->get();
            // check if each student have notes
            $userIds = ClasseAnneeScolaireStudent::where('annee_scolaire_id', getCurrentYear()->id)
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
                        ->where('annee_scolaire_id', getCurrentYear()->id)
                        ->where('evaluation_id', $evaluation->id)->exists();
                    if(!$note) {
                        return response()->json([
                            "error" => "L'élève {$student->name} n'a pas de note en {$matiere->libelleMatiere}
                            pour l'évaluation {$evaluation->libelleEvaluation}"
                        ]);
                    }
                }
            }
            // cheking when we want to generate for a class
            if(
                $request->option_type === "all" &&
                $request->type_bulletin === 'sequenciel' &&
                isset($request->evaluation_id)
            ) {
                $students = User::all()->where('typeUser','eleve')
                    ->whereIn('id', $userIds);
                foreach ($students as $student) {
                    foreach ($matieres as $matiere) {
                        $note = Note::where('user_id', $student->id)
                            ->where('matiere_id', $matiere->id)
                            ->where('classe_id', $classe->id)
                            ->where('annee_scolaire_id', getCurrentYear()->id)
                            ->where('evaluation_id', $evaluation->id)->exists();
                        if(!$note) {
                            return response()->json([
                                "error" => "L'élève {$student->name} n'a pas de note en {$matiere->libelleMatiere}
                                pour l'évaluation {$evaluation->libelleEvaluation}"
                            ]);
                        }
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
                $result = generateSingleSeqReportCard($student, $classe, $evaluation, $trimestre);
                return response()->json([
                    $result["type"] => $result["message"]
                ]);
            }
            if ($request->option_type === "one" && $request->type_bulletin === 'trimestre') {
                $student = User::where('id', $request->user_id)->where('typeUser','eleve')
                    ->whereIn('id', $userIds)->first();
                $result = generateSingleTrimReportCard($student, $classe, $evaluation, $trimestre);
                return response()->json([
                    $result["type"] => $result["message"]
                ]);
            }
            if ($request->option_type === "one" && $request->type_bulletin === 'annuel') {
                $student = User::where('id', $request->user_id)->where('typeUser','eleve')
                    ->whereIn('id', $userIds)->first();
                $result = generateSingleAnnualReportCard($student, $classe);
                return response()->json([
                    $result["type"] => $result["message"]
                ]);
            }
            // generate for a class
            if (
                $request->option_type === "all" &&
                $request->type_bulletin === 'sequenciel' &&
                isset($request->evaluation_id)
            ) {
                $result = generateAllSeqReportCard($classe, $evaluation, $trimestre);
                return response()->json([
                    $result["type"] => $result["message"]
                ]);
            }
            if ($request->option_type === "all" && $request->type_bulletin === 'trimestre') {
                $result = generateAllTrimReportCard($classe, $evaluation, $trimestre);
                return response()->json([
                    $result["type"] => $result["message"]
                ]);
            }
            if ($request->option_type === "all" && $request->type_bulletin === 'annuel') {
                $result = generateAllAnnualReportCard($classe);
                return response()->json([
                    $result["type"] => $result["message"]
                ]);
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
        try {
            $bulletin = Bulletin::findOrFail($id);
            if($bulletin->type_bulletin === "sequenciel") {
                $notes = Note::where('user_id', $bulletin->user_id)
                    ->where('evaluation_id', $bulletin->evaluation_id)
                    ->where('classe_id', $bulletin->classe_id);
                $notes->delete();
            }
            if($bulletin->type_bulletin === "trimestre") {
                $notes = TrimestreNote::where('user_id', $bulletin->user_id)
                    ->where('trimestre_id', $bulletin->trimestre_id)
                    ->where('classe_id', $bulletin->classe_id);
                $notes->delete();
            }
            if($bulletin->type_bulletin === "annuel") {
                $notes = AnnualNote::where('user_id', $bulletin->user_id)
                    ->where('annee_scolaire_id', getCurrentYear()->id)
                    ->where('classe_id', $bulletin->classe_id);
                $notes->delete();
            }
            $bulletin->delete();
            return redirect()->route('bulletins.list')
                ->with('deleteSuccess', 'Bulletin supprimé avec succès!');
        } catch (Exception $ex) {
            return redirect()->route('bulletins.list')
                ->with('deleteSuccess', $ex->getMessage());
        }
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
        $evaluations = Evaluation::where('trimestre_id', $trim->id)
            ->where('type','normal-evaluation')->get();
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
        $firstGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds(
            '1er groupe', getCurrentYear()->id, $bulletin->classe_id))->pluck('id');
        $sndGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds(
            '2e groupe', getCurrentYear()->id, $bulletin->classe_id))->pluck('id');
        $thirdGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds(
            '3e groupe', getCurrentYear()->id, $bulletin->classe_id))->pluck('id');
        // gettings notes by groups
        $userIds = ClasseAnneeScolaireStudent::where('user_id', $bulletin->user_id)
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->where('classe_id', $bulletin->classe_id)->pluck('user_id');
        $student = User::where('id', $bulletin->user_id)->where('typeUser','eleve')
            ->whereIn('id', $userIds)->first();
        // for sequence :
        if($bulletin->type_bulletin === 'sequenciel') {
            // update disciplines stats first
            $studentDisciplines = Discipline::all()->where('user_id', $student->id)
                ->where('evaluation_id',$bulletin->evaluation->id);
            $studentConseils = ConseilDiscipline::where('user_id', $student->id)
                ->where('evaluation_id',$bulletin->evaluation->id);
            $displineStats = getDisciplinesStats($studentDisciplines);
            $conseilsStats = getConseilsStats($studentConseils);
            $bulletin->update([
                'discipline_stats' => json_encode($displineStats),
                'conseils_stats' => json_encode($conseilsStats)
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
            $conseils = $bulletin->conseils_stats;
            return view(
                'bulletin.user-report-card',
                compact('bulletin','studentNotesFirstGroup','studentNotesSndGroup','studentNotesThirdGroup','disciplines','conseils')
            );
        }
        // for trimestre :
        if($bulletin->type_bulletin === "trimestre") {
            $disciplines = [];
            $bulletinsAvgs = [];
            $conseils = [];
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
                $studentConseils = ConseilDiscipline::where('user_id', $student->id)
                    ->where('evaluation_id',$evalBulletin->evaluation->id)
                    ->where('classe_id', $evalBulletin->classe_id);
                $displineStats = getDisciplinesStats($studentDisciplines);
                $conseilsStats = getConseilsStats($studentConseils);
                $evalBulletin->update([
                    'discipline_stats' => json_encode($displineStats),
                    'conseils_stats' => json_encode($conseilsStats)
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
                array_push($conseils, json_decode($evalBulletin->conseils_stats));
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
                compact('bulletin','bulletinsAvgs','studentNotesFirstGroup','studentNotesSndGroup','studentNotesThirdGroup','disciplines','conseils')
            );
        }
        // for annual :
        if($bulletin->type_bulletin === "annuel") {
            $disciplines = [];
            $bulletinsAvgs = [];
            $conseils = [];
            $trimestrialBulletins = Bulletin::all()->where('type_bulletin',"trimestre")
                ->where('user_id', $bulletin->user_id)
                ->where('classe_id', $bulletin->classe_id)
                ->where('annee_scolaire_id', getCurrentYear()->id);
            // getting something :
            foreach($trimestrialBulletins as $key => $trimBulletin) {
                // update disciplines stats first
                $evaluationIds = Evaluation::where('trimestre_id', $trimBulletin->trimestre->id)
                    ->where('type','normal-evaluation')->pluck('id');
                $studentDisciplines = Discipline::all()->where('user_id', $student->id)
                    ->whereIn('evaluation_id', $evaluationIds)
                    ->where('classe_id', $trimBulletin->classe_id);
                $studentConseils = ConseilDiscipline::where('user_id', $student->id)
                    ->whereIn('evaluation_id', $evaluationIds)
                    ->where('classe_id', $trimBulletin->classe_id);
                $displineStats = getDisciplinesStats($studentDisciplines);
                $conseilsStats = getConseilsStats($studentConseils);
                $trimBulletin->update([
                    'discipline_stats' => json_encode($displineStats),
                    'conseils_stats' => json_encode($conseilsStats)
                ]);
                // try to implements something to update annual note data before rendering the bulletin
                $notes = Note::all()->where('classe_id', $trimBulletin->classe_id)
                    ->where('user_id', $student->id)
                    ->where('annee_scolaire_id', getCurrentYear()->id)
                    ->whereIn('evaluation_id', $evaluationIds);
                foreach($notes as $note) {
                    updateAnnualNotes(
                        $trimBulletin->classe_id,
                        $student->id,
                        $note->matiere_id
                    );
                }
                // create the discipline data
                array_push($disciplines, json_decode($trimBulletin->discipline_stats));
                array_push($conseils, json_decode($trimBulletin->conseils_stats));
                // bulletins average :
                array_push($bulletinsAvgs, [
                    "trimestre_name" => $trimBulletin->trimestre->libelleTrimestre,
                    "trimestre_average" => $trimBulletin->average
                ]);
            }
            // all annual groups matieres notes
            $studentNotesFirstGroup = AnnualNote::where('user_id', $student->id)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('classe_id', $bulletin->classe_id)
                ->whereIn('matiere_id', $firstGroupMatiereIds)->get();
            $studentNotesSndGroup = AnnualNote::where('user_id', $student->id)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('classe_id', $bulletin->classe_id)
                ->whereIn('matiere_id', $sndGroupMatiereIds)->get();
            $studentNotesThirdGroup = AnnualNote::where('user_id', $student->id)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('classe_id', $bulletin->classe_id)
                ->whereIn('matiere_id', $thirdGroupMatiereIds)->get();
            return view(
                'bulletin.user-report-card',
                compact('bulletin','bulletinsAvgs','studentNotesFirstGroup','studentNotesSndGroup','studentNotesThirdGroup','disciplines','conseils')
            );
        }
    }

    /**
     * Student bulletin show 
     */
    public function studentBulletin() {
        $user = User::findOrFail(Auth::id());
        return view('eleves.bulletin', compact('user'));
    }
}
