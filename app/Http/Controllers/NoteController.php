<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\Coefficient;
use App\Models\EnseignantMatiereModel;
use App\Models\Enseignement;
use App\Models\EnsMatAnneeScolaire;
use Illuminate\Support\Facades\Auth;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\NoteHistory;
use App\Models\Remplissage;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        $user = User::find(Auth::id());
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        // datas
        $remplissages = Remplissage::all()->where('statut','=','en cours');
        // getting classes base on the teacher teaching :
        if($user->typeUser === 'enseignant') {
            $ensMatYearIds = EnsMatAnneeScolaire::where('annee_scolaire_id', $activeYear->id)
            ->pluck('enseignant_matiere_models_id');
            $enseignantMatiereIds = EnseignantMatiereModel::where('user_id',$user->id)
            ->whereIn('id',$ensMatYearIds)->pluck('id');
            $enseignantClassesIds = Enseignement::whereIn('enseignant_matiere_id', $enseignantMatiereIds)
                ->pluck('classe_id');
            $classes = Classe::whereIn('classe_id', $enseignantClassesIds);
        } else {
            $classes = Classe::all();
        }
        // filters
        $searchNote = $request->input('searchNote');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $remplissageFilter = $request->input('remplissageFilter');

        $query = Note::query();
        $studentsIds = ClasseAnneeScolaireStudent::all()->where('annee_scolaire_id', $activeYear->id)
            ->pluck('user_id');
        $studentQuery = User::query()->where('typeUser','eleve')->whereIn('id',$studentsIds);
        if(!empty($searchNote)) {
            $studentQuery->where('name','LIKE',"%{$searchNote}%");
        }
        if (!empty($classeFilter)) {
            $query->where('classe_id', $classeFilter);
            $studentQuery->where('classe_id', $classeFilter);
        }
        if (!empty($matiereFilter)) {
            $query->where('matiere_id', $matiereFilter);
        }
        if (!empty($remplissageFilter)) {
            $query->where('remplissage_id',$remplissageFilter);
        }
        $notes = $query->paginate(10);
        $students = $studentQuery->paginate(10);

        if($request->ajax()) {
            return view('partials._note_table', compact('students','notes','remplissages','classes','classeFilter','matiereFilter','remplissageFilter'));
        } else {
            return view('evaluation.notes', compact('remplissages','classes','students','notes','classeFilter','matiereFilter','remplissageFilter'));
        }
    }

    /**
     * create or update note
     */
    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required',
            'matiere_id' => 'required',
            'evaluation_id' => 'required',
            'remplissage_id' => 'required',
            'classe_id' => 'required',
            'appreciation' => 'required',
            'note' => 'required|numeric|min:0|max:20'
        ], [
            'user_id.required' => 'Aucun élève selectionner',
            'matiere_id.required' => 'Veuillez selectionner une matière',
            'evaluation_id.required' => 'Veuillez selctionner une évaluation',
            'remplissage_id.required' => 'Vérifiez bien qu\'une configuration de remplissage est [en cours]',
            'classe_id.required' => 'Veuillez slectionner une classe',
            'appreciation.required' => 'Veuillez entre une note pour la définition de l\'appreciation',
            'note.required' => 'Veuillez entrez une note',
            'note.numeric' => 'La note doite etre un nombre',
            'note.min' => 'La note doit etre égale au moins à 0',
            'note.max' => 'La note doit etre égale au plus à 20'
        ]);
        $existNote = Note::where('user_id', $request->user_id)
            ->where('matiere_id',$request->matiere_id)
            ->where('classe_id',$request->classe_id)
            ->where('evaluation_id',$request->evaluation_id)
            ->where('remplissage_id',$request->remplissage_id)
            ->exists();
        if($existNote) {
            return response()->json([
                'error' => 'La note existe déjà !'
            ]);
        } else {
            // calculate the note range and gcma(general class matiere average on a subject)
            $notes = [];
            $notesData = Note::all()->where('matiere_id',$request->matiere_id,)
            ->where('classe_id',$request->classe_id)->where('evaluation_id',$request->evaluation_id);
            foreach($notesData as $noteData) {
                array_push($noteData->note, $notes);
            }
            array_push($notes, $request->note); // adding the request note
            $range = getRange($request->note, $notes); // finally get the range
            $gcma = getGeneralMoy($notes); // get the general class average of the subject
            $minValue = min($notes);
            $maxValue = max($notes);

            // then save the note in the db
            $note = Note::create([
                'matiere_id' => $request->matiere_id,
                'user_id' => $request->user_id,
                'classe_id' => $request->classe_id,
                'evaluation_id' => $request->evaluation_id,
                'remplissage_id' => $request->remplissage_id,
                'note' => $request->note,
                'range' => $range,
                'mgc' => $gcma,
                'min_value' => $minValue,
                'max_value' => $maxValue,
                'appreciation' => $request->appreciation
            ]);
            if($note) {
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
        $note = Note::findOrFail($id);
        // update the note range and gcma(general class matiere average on a subject)
        $notes = [];
        $notesData = Note::all()->where('matiere_id',$request->matiere_id,)
        ->where('classe_id',$request->classe_id)->where('evaluation_id',$request->evaluation_id);
        foreach($notesData as $noteData) {
            array_push($noteData->note, $notes);
        }
        // remove the old value in the array and add the new_value
        $noteToRemoveValue = $note->note;
        $noteToRemoveKey = array_search($noteToRemoveValue, $notes);
        if($noteToRemoveKey) {
            unset($notes[$noteToRemoveKey]);
            array_values($notes);
        }
        array_push($notes, $request->new_value); // adding the request note
        $range = getRange($request->new_value, $notes); // finally get the range
        $gcma = getGeneralMoy($notes); // get the general class average of the subject
        $minValue = min($notes);
        $maxValue = max($notes);
        $oldValue = $note->note;
        $note->update([
            'note' => $request->new_value,
            'range' => $range,
            'gcma' => $gcma,
            'min_value' => $minValue,
            'max_value' => $maxValue,
            'appreciation' => $request->appreciation
        ]);

        // saving history note
        $noteHistory = NoteHistory::create([
            'note_id' => $note->id,
            'user_id' => Auth::id(),
            'old_value' => $oldValue,
            'new_value' => $request->new_value,
            'reason' => $request->reason,
        ]);

        if($noteHistory) {
            return response()->json(['success' => 'Note mise à jour avec succès']);
        }
    }

    /**
     * getting matiere in coefficient classe base on the classe selection
     */
    public function getMatieres($classe_id) {
        $user = User::find(Auth::id());
        if($user->typeUser === 'enseignant') {
            $matieresIds = EnseignantMatiereModel::where('user_id', $user->id)->pluck('matiere_id');
            $coefsIds = Coefficient::where('classe_id', $classe_id)
                ->whereIn('matiere_id', $matieresIds)
                ->pluck('matiere_id');
            $matieres = Matiere::whereIn('id', $coefsIds)->get();
        } else {
            $coefficients = Coefficient::where('classe_id', $classe_id)->pluck('matiere_id');
            $matieres = Matiere::whereIn('id', $coefficients)->get();
        }
        return response()->json($matieres->values());
    }

    /**
     * delete a specific note
     */
    public function destroy($id) {
        $note = Note::findOrFail($id);
        $noteHistory = NoteHistory::where('note_id', $note->id);
        try {
            $note->delete();
            $noteHistory->delete();
            return redirect()->route('evaluation.notes')->with('deleteSuccess', 'Note supprimé avec succès');
        } catch (Exception $ex) {
            dd($ex);
        }
    }
}
