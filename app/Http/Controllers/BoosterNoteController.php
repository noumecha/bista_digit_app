<?php

namespace App\Http\Controllers;

use App\Models\BoosterClasse;
use App\Models\BoosterMatiere;
use App\Models\BoosterNote;
use App\Models\BoosterNoteHistory;
use App\Models\BoosterNoteRemplissageTrace;
use App\Models\BoosterStudent;
use App\Models\BoosterTeacher;
use App\Models\Classe;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\Coefficient;
use App\Models\Evaluation;
use App\Models\Matiere;
use App\Models\Remplissage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class BoosterNoteController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        $user = User::find(Auth::id());
        // datas
        $remplissages = Remplissage::all()->where('statut','en cours');
        // getting classes base on the teacher teaching :
        if($user->typeUser === 'enseignant') {
            $boosterClassesIds = BoosterTeacher::where('user_id',$user->id)->pluck('classe_id');
            $boosterStudentIds = BoosterStudent::all()->pluck('user_id');
            $studentsYearClasseIds = ClasseAnneeScolaireStudent::all()
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->whereIn('user_id', $boosterStudentIds)
                ->pluck('classe_id');
            $classes = Classe::whereIn('id', $boosterClassesIds)->whereIn('id', $studentsYearClasseIds);
        } else {
            $boosterClassesIds = BoosterClasse::all()->pluck('classe_id');
            $boosterStudentIds = BoosterStudent::all()->pluck('user_id');
            $studentsYearClasseIds = ClasseAnneeScolaireStudent::all()
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->whereIn('user_id', $boosterStudentIds)
                ->pluck('classe_id');
            $classes = Classe::whereIn('id', $boosterClassesIds)
                ->orWhereIn('id', $studentsYearClasseIds)->distinct()->get();
        }
        // filters
        $searchNote = $request->input('searchNote');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $remplissageFilter = $request->input('remplissageFilter');
        // queries
        $query = BoosterNote::query()->where('annee_scolaire_id', getCurrentYear()->id);
        $studentsYearClasseIds = ClasseAnneeScolaireStudent::all()
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->pluck('user_id');
        $studentIds = User::all()->whereIn('id', $studentsYearClasseIds)
            ->where('typeUser','eleve')->pluck('id');
        // filtering by filters inputs
        $studentQuery = BoosterStudent::query()->whereIn('user_id', $studentIds);
        if(!empty($searchNote)) {
            $studentQuery->whereHas('student', function ($q) use ($searchNote) {
                $q->where('name', 'LIKE', "%{$searchNote}%")
                ->orWhere('surname', 'LIKE', "%{$searchNote}%");
            });
        }
        if (!empty($classeFilter)) {
            $boosterClassesIds = BoosterClasse::all()->pluck('classe_id');
            $boosterStudentIds = BoosterStudent::all()->pluck('user_id');
            $studentsYearClasseId = ClasseAnneeScolaireStudent::all()->where('classe_id', $classeFilter)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->whereIn('classe_id', $boosterClassesIds)
                ->pluck('classe_id');
            $studentsIds = ClasseAnneeScolaireStudent::all()->where('classe_id', $classeFilter)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->whereIn('classe_id', $boosterClassesIds)
                ->pluck('user_id');
            #dd($usersIds);
            $query->whereIn('classe_id', $studentsYearClasseId);
            $studentQuery->whereIn('user_id', $studentsIds);
        }
        if (!empty($matiereFilter)) {
            $query->where('booster_matiere_id', $matiereFilter);
        }
        if (!empty($remplissageFilter)) {
            $query->where('remplissage_id',$remplissageFilter);
        }
        // results
        $notes = $query->paginate(10);
        $students = $studentQuery->paginate(10);
        if($request->ajax()) {
            return view('partials._booster_notes_table', compact('students','notes','remplissages','classes','classeFilter','matiereFilter','remplissageFilter'));
        } else {
            return view('programme.booster_notes', compact('remplissages','classes','students','notes','classeFilter','matiereFilter','remplissageFilter'));
        }
    }

    /**
     * show remplissage state
     */
    public function boosterRemplissageTrace(Request $request) {
        // usefull vars
        $classes = Classe::all();
        $matieres = BoosterMatiere::all();
        $evaluations = Evaluation::all()->where('type','booster-evaluation');
        // filters vars
        $query = BoosterNoteRemplissageTrace::query()->where('annee_scolaire_id', getCurrentYear()->id);
        $searchTeacher = $request->input('searchTeacher');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $evaluationFilter = $request->input('evaluationFilter');
        if (!empty($searchTeacher)) {
            $query->whereHas('teacher', function ($q) use ($searchTeacher) {
                $q->where('name', 'LIKE', "%{$searchTeacher}%")
                ->orWhere('surname', 'LIKE', "%{$searchTeacher}%");
            });
        }
        if (!empty($classeFilter)) {
            $query->where('classe_id', $classeFilter);
        }
        if (!empty($evaluationFilter)) {
            $query->where('evaluation_id', $evaluationFilter);
        }
        if (!empty($matiereFilter)) {
            $query->whereHas('matiere', function ($q) use ($matiereFilter) {
                $q->where('id',$matiereFilter);
            });
        }
        $traces = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._booster_controle_remplissage_table', compact('traces'));
        } else {
            return view('programme.booster_controle_remplissage', compact('traces','classes','evaluations','matieres'));
        }
    }

    /**
     * show modification trace
     */
    public function boosterNoteHistories(Request $request) {
        // usefull vars
        $classes = BoosterClasse::all();
        $matieres = BoosterMatiere::all();
        $evaluations = Evaluation::all()->where('type','booster-evaluation');
        // var for filtering
        $notesId = BoosterNote::where('annee_scolaire_id', getCurrentYear()->id)->pluck('id');
        $query = BoosterNoteHistory::query()->whereIn('booster_note_id', $notesId);
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $evaluationFilter = $request->input('evaluationFilter');
        $searchStudent = $request->input('searchStudent');
        if (!empty($searchStudent)) {
            $query->whereHas('note.student.student', function ($q) use ($searchStudent) {
                $q->where('name', 'LIKE', "%{$searchStudent}%")
                ->orWhere('surname', 'LIKE', "%{$searchStudent}%");
            });
        }
        if (!empty($classeFilter)) {
            $query->whereHas('note.classe', function ($q) use ($classeFilter) {
                $q->where('id', $classeFilter);
            });
        }
        if (!empty($evaluationFilter)) {
            $query->whereHas('note.evaluation', function ($q) use ($evaluationFilter) {
                $q->where('id', $evaluationFilter);
            });
        }
        if (!empty($matiereFilter)) {
            $query->whereHas('note.matiere.matiere', function ($q) use ($matiereFilter) {
                $q->where('id', $matiereFilter);
            });
        }
        $histories = $query->paginate(10);
        if ($request->ajax()) {
            return view('partials._booster_historiques_notes_table', compact('histories'));
        } else {
            return view('programme.booster_historiques_notes', compact('histories','matieres','classes','evaluations'));
        }
    }

    /**
     * create note - when filling some note
     */
    public function store(Request $request) {
        $request->validate([
            'booster_student_id' => 'required',
            'booster_matiere_id' => 'required',
            'evaluation_id' => 'required',
            'remplissage_id' => 'required',
            'classe_id' => 'required',
            'appreciation' => 'required',
            'boosternote' => 'required|numeric|min:0|max:20'
        ], [
            'booster_student_id.required' => 'Aucun élève selectionner',
            'booster_matiere_id.required' => 'Veuillez selectionner une matière',
            'evaluation_id.required' => 'Veuillez selectionnez une évaluation',
            'remplissage_id.required' => 'Vérifiez bien qu\'une configuration de remplissage est [en cours]',
            'classe_id.required' => 'Veuillez selectionner une classe',
            'appreciation.required' => 'Veuillez entrer une note pour la définition de l\'appreciation',
            'boosternote.required' => 'Veuillez entrez une note',
            'boosternote.numeric' => 'La note doit etre un nombre',
            'boosternote.min' => 'La note doit etre égale au moins à 0',
            'boosternote.max' => 'La note doit etre égale au plus à 20'
        ]);
        try {
            $existNote = BoosterNote::where('booster_student_id', $request->booster_student_id)
            ->where('booster_matiere_id',$request->booster_matiere_id)
            ->where('classe_id',$request->classe_id)
            ->where('evaluation_id',$request->evaluation_id)
            ->where('remplissage_id',$request->remplissage_id)
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->exists();
        if($existNote) {
            return response()->json([
                'error' => 'La note existe déjà !'
            ]);
        } else {
            // then save the note in the db
            $note = BoosterNote::create([
                'booster_matiere_id' => $request->booster_matiere_id,
                'booster_student_id' => $request->booster_student_id,
                'classe_id' => $request->classe_id,
                'evaluation_id' => $request->evaluation_id,
                'remplissage_id' => $request->remplissage_id,
                'note' => $request->boosternote,
                'appreciation' => $request->appreciation,
                'annee_scolaire_id' => getCurrentYear()->id
            ]);
            // create new booster note trace :
            $noteTrace = BoosterNoteRemplissageTrace::updateOrCreate([
                'user_id' => Auth::id(),
                'classe_id' => $request->classe_id,
                'booster_matiere_id' => $request->booster_matiere_id,
                'evaluation_id' => $request->evaluation_id,
                'remplissage_id' => $request->remplissage_id,
                'annee_scolaire_id' => getCurrentYear()->id
            ],[
                'nb_notes_remplies' => DB::raw('nb_notes_remplies + 1')
            ]);
            // update all the notes with the corresponding new min value and max value
            updateBoosterNoteMinMaxRange(
                $note->booster_matiere_id,
                $note->classe_id,
                $note->evaluation_id,
            );
            if($note && $noteTrace) {
                return response()->json(['success' => 'Note enregistrée avec succès']);
            }
        }
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erreur lors de l\'enregistrement : '.$th->getMessage()
            ]);
        }

    }

    /**
     * update a note make a change of a note now save it on
     * note_histories table
     */
    public function update(Request $request, $id) {
        $request->validate([
            'appreciation' => 'required',
            'new_value' => 'required|numeric|min:0|max:20',
            'reason' => 'required|string',
        ], [
            'appreciation.required' => 'Veuillez entre une note pour la définition de l\'appreciation',
            'new_value.numeric' => 'La note doite etre un nombre',
            'new_value.min' => 'La note doit etre égale au moins à 0',
            'new_value.max' => 'La note doit etre égale au plus à 20',
            'new_value.required' => 'Entrez la nouvelle valeur de la note',
            'reason.required' => 'Entrez la raison de la modification de la note'
        ]);
        // update note before creating history
        $note = BoosterNote::findOrFail($id);
        $oldValue = $note->note;
        $note->update([
            'note' => $request->new_value,
            'appreciation' => $request->appreciation
        ]);
        // saving history note
        $noteHistory = BoosterNoteHistory::create([
            'booster_note_id' => $note->id,
            'user_id' => Auth::id(),
            'old_value' => $oldValue,
            'new_value' => $request->new_value,
            'reason' => $request->reason,
        ]);
        // update all the notes with the corresponding new min value and max value
        updateBoosterNoteMinMaxRange(
            $note->booster_matiere_id,
            $note->classe_id,
            $note->evaluation_id
        );
        if($noteHistory) {
            return response()->json(['success' => 'Note mise à jour avec succès']);
        }
    }
    /**
     * getting matiere in coefficient classe base on the classe selection
     */
    public function getBoosterMatieres($classeId) {
        $user = User::find(Auth::id());
        $mats = [];
        if($user->typeUser === 'enseignant') {
            $boosterMatieresIds = BoosterTeacher::where('user_id', $user->id)->pluck('booster_matiere_id');
            $coefsIds = Coefficient::where('classe_id', $classeId)
                ->pluck('matiere_id');
            $matieres = BoosterMatiere::whereIn('id', $boosterMatieresIds)
                ->whereIn('matiere_id', $coefsIds)->get();
        } else {
            $coefficients = Coefficient::where('classe_id', $classeId)->pluck('matiere_id');
            $matieres = BoosterMatiere::whereIn('matiere_id', $coefficients)->get();
        }
        foreach($matieres as $matiere) {
            array_push($mats, [
                'id' => $matiere->id,
                'name' => $matiere->matiere->libelleMatiere
            ]);
        }
        return response()->json($mats);
    }

    /**
     * delete a specific note
     */
    public function destroy($id) {
        $note = BoosterNote::findOrFail($id);
        $noteHistory = BoosterNoteHistory::where('booster_note_id', $note->id);
        try {
            $note->delete();
            $noteHistory->delete();
            return redirect()->route('booster.notes')->with('deleteSuccess', 'Note supprimé avec succès');
        } catch (Throwable $ex) {
            return redirect()->route('booster.notes')->with(
                'deleteError', 'Erreur lors de la suppression : '.$ex->getMessage()
            );
        }
    }
}
