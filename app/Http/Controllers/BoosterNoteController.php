<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\BoosterMatiere;
use App\Models\BoosterNote;
use App\Models\BoosterStudent;
use App\Models\BoosterTeacher;
use App\Models\Classe;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\Coefficient;
use App\Models\EnseignantMatiereModel;
use App\Models\Enseignement;
use App\Models\EnsMatAnneeScolaire;
use App\Models\Evaluation;
use App\Models\Matiere;
use App\Models\NoteHistory;
use App\Models\NoteRemplissageTrace;
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
        $remplissages = Remplissage::all()->where('statut','=','en cours');
        // getting classes base on the teacher teaching :
        if($user->typeUser === 'enseignant') {
            $boosterClassesIds = BoosterTeacher::where('user_id',$user->id)->pluck('classe_id');
            $classes = Classe::whereIn('classe_id', $boosterClassesIds);
        } else {
            $boosterClassesIds = BoosterTeacher::all()->pluck('classe_id');
            $classes = Classe::all()->whereIn('id', $boosterClassesIds);
        }
        // filters
        $searchNote = $request->input('searchNote');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $remplissageFilter = $request->input('remplissageFilter');
        // queries
        $query = BoosterNote::query()->where('annee_scolaire_id', getCurrentYear()->id);
        $studentsYearClassseIds = ClasseAnneeScolaireStudent::all()
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->pluck('user_id');
        $studentBoosterIds = BoosterStudent::all()->pluck('user_id');
        $studentQuery = User::query()->where('typeUser','eleve')->whereIn('id',$studentBoosterIds)
            ->whereIn('id', $studentsYearClassseIds);
        // filtering by filters inputs
        if(!empty($searchNote)) {
            $studentQuery->where('name','LIKE',"%{$searchNote}%");
        }
        if (!empty($classeFilter)) {
            // filtering by different classe Id throw years
            $studentsYearClassseId = ClasseAnneeScolaireStudent::all()->where('classe_id', $classeFilter)
                ->where('annee_scolaire_id', getCurrentYear()->id)
                ->pluck('classe_id');
            $query->whereIn('classe_id', $studentsYearClassseId);
            // filtering by different classe Id throw years
            $studentsYearClassseIds = ClasseAnneeScolaireStudent::all()->where('annee_scolaire_id', getCurrentYear()->id)
                ->where('classe_id', $classeFilter)
                ->pluck('user_id');
            $studentQuery->whereIn('id', $studentsYearClassseIds);
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
    public function remplissageTrace(Request $request) {
        // usefull vars
        $classes = Classe::all();
        $matieres = BoosterMatiere::all();
        $evaluations = Evaluation::all()->where('type','booster-evaluation');
        // filters vars
        $query = NoteRemplissageTrace::query()->where('type', 'booster-note')
            ->where('annee_scolaire_id', getCurrentYear()->id);
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
            $query->where('matiere_id', $matiereFilter);
        }
        $traces = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._controle_remplissage_table', compact('traces'));
        } else {
            return view('evaluation.controle_remplissage', compact('traces','classes','evaluations','matieres'));
        }
    }

    /**
     * show modification trace
     */
    public function noteHistories(Request $request) {
        // usefull vars
        $classes = Classe::all();
        $matieres = BoosterMatiere::all();
        $evaluations = Evaluation::all()->where('type','booster-evaluation');
        // var for filtering
        $notesId = BoosterNote::where('annee_scolaire_id', getCurrentYear()->id)->pluck('id');
        $query = NoteHistory::query()->where('type','booster-note')->whereIn('note_id', $notesId);
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $evaluationFilter = $request->input('evaluationFilter');
        $searchStudent = $request->input('searchStudent');
        if (!empty($searchStudent)) {
            $query->whereHas('note.eleve', function ($q) use ($searchStudent) {
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
            $query->whereHas('note.matiere', function ($q) use ($matiereFilter) {
                $q->where('id', $matiereFilter);
            });
        }
        $histories = $query->paginate(10);
        if ($request->ajax()) {
            return view('partials._historiques_notes_table', compact('histories'));
        } else {
            return view('evaluation.hitoriques_notes', compact('histories','matieres','classes','evaluations'));
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
            'note' => 'required|numeric|min:0|max:20'
        ], [
            'booster_student_id.required' => 'Aucun élève selectionner',
            'booster_matiere_id.required' => 'Veuillez selectionner une matière',
            'evaluation_id.required' => 'Veuillez selectionnez une évaluation',
            'remplissage_id.required' => 'Vérifiez bien qu\'une configuration de remplissage est [en cours]',
            'classe_id.required' => 'Veuillez slectionner une classe',
            'appreciation.required' => 'Veuillez entre une note pour la définition de l\'appreciation',
            'note.required' => 'Veuillez entrez une note',
            'note.numeric' => 'La note doite etre un nombre',
            'note.min' => 'La note doit etre égale au moins à 0',
            'note.max' => 'La note doit etre égale au plus à 20'
        ]);
        $existNote = BoosterNote::where('booster_user_id', $request->booster_stduent_id)
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
                'booster_matiere_id' => $request->matiere_id,
                'booster_student_id' => $request->user_id,
                'classe_id' => $request->classe_id,
                'evaluation_id' => $request->evaluation_id,
                'remplissage_id' => $request->remplissage_id,
                'note' => $request->note,
                'appreciation' => $request->appreciation,
                'annee_scolaire_id' => getCurrentYear()->id
            ]);
            // create new note trace :
            $noteTrace = NoteRemplissageTrace::updateOrCreate([
                'booster_student_id' => Auth::id(),
                'classe_id' => $request->classe_id,
                'booster_matiere_id' => $request->matiere_id,
                'evaluation_id' => $request->evaluation_id,
                'remplissage_id' => $request->remplissage_id,
                'annee_scolaire_id' => getCurrentYear()->id
            ],[
                'nb_notes_remplies' => DB::raw('nb_notes_remplies + 1')
            ]);
            // update all the notes with the corresponding new min value and max value
            updateNoteMinMaxRange(
                $note->matiere_id,
                $note->classe_id,
                $note->evaluation_id,
            );
            if($note && $noteTrace) {
                return response()->json(['success' => 'Note enregistrée avec succès']);
            }
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
        $noteHistory = NoteHistory::create([
            'note_id' => $note->id,
            'booster_student_id' => Auth::id(),
            'old_value' => $oldValue,
            'new_value' => $request->new_value,
            'reason' => $request->reason,
        ]);
        // update all the notes with the corresponding new min value and max value
        updateNoteMinMaxRange(
            $note->matiere_id,
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
    public function getMatieres($classeId) {
        $user = User::find(Auth::id());
        if($user->typeUser === 'enseignant') {
            $boosterMatieresIds = BoosterTeacher::where('user_id', $user->id)->pluck('booster_matiere_id');
            $matieresIds = BoosterMatiere::whereIn('id', $boosterMatieresIds)->pluck('matiere_id');
            $coefsIds = Coefficient::where('classe_id', $classeId)
                ->whereIn('matiere_id', $matieresIds)
                ->pluck('matiere_id');
            $matieres = Matiere::whereIn('id', $coefsIds)->get();
        } else {
            $coefficients = Coefficient::where('classe_id', $classeId)->pluck('matiere_id');
            $matieres = BoosterMatiere::whereIn('id', $coefficients)->get();
        }
        return response()->json($matieres->values());
    }

    /**
     * delete a specific note
     */
    public function destroy($id) {
        $note = BoosterNote::findOrFail($id);
        $noteHistory = NoteHistory::where('note_id', $note->id);
        try {
            $note->delete();
            $noteHistory->delete();
            return redirect()->route('evaluation.notes')->with('deleteSuccess', 'Note supprimé avec succès');
        } catch (Throwable $ex) {
            dd($ex);
        }
    }
}
