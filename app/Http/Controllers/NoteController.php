<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Evaluation;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Remplissage;
use App\Models\User;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        // datas
        $notes = Note::all();
        $matieres = Matiere::all();
        $remplissages = Remplissage::all()->where('statut','=','activé');
        $evaluations = Evaluation::all();
        $classes = Classe::all();
        //$students = User::query()->where('typeUser','=','eleve')->paginate(10);

        // filters
        $searchNote = $request->input('searchNote');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $remplissageFilter = $request->input('remplissageFilter');

        $query = Note::query();
        $studentQuery = User::query()->where('typeUser','=','eleve');

        if(!empty($searchNote)) {
            /*$query->whereHas('user', function($q) use ($searchNote) {
                $q->where('name','LIKE',"%{$searchNote}%");
            });*/
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
            return view('partials._note_table', compact('students','notes','remplissages','classes','evaluations','matieres','classeFilter','matiereFilter','remplissageFilter'));
        } else {
            return view('evaluation.notes', compact('remplissages','searchNote','classeFilter','matiereFilter','remplissageFilter','classes','evaluations','matieres','students','evaluations','notes'));
        }
    }

    /**
     *
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
            'evaluation_id.required' => 'Veuillez selectionner une évaluation',
            'remplissage_id.required' => 'Veuillez selctionner une évaluation',
            'classe_id.required' => 'Veuillez slectionner une classe',
            'appreciation.required' => 'Veuillez une définir une note pour la définition de l\'appreciation',
            'note.required' => 'Veuillez entrez une note',
            'note.numeric' => 'La note doite etre un nombre',
            'note.min' => 'La note doit etre égale au moins à 0',
            'note.max' => 'La note doit etre égale au plus à 20'
        ]);

        //dd($request);
        Note::create([
            'matiere_id' => $request->matiere_id,
            'user_id' => $request->user_id,
            'classe_id' => $request->classe_id,
            'evaluation_id' => $request->evaluation_id,
            'remplissage_id' => $request->remplissage_id,
            'note' => $request->note,
            'appreciation' => $request->appreciation,
        ]);

        return response()->json(['success' => 'Note enregistrée avec succès']);
    }

    /**
     *
     */
    public function edit($id) {

    }

    /**
     *
     */
    public function update(Request $request, $id) {
        $note = Note::findOrFail($id);
        if($note) {
            $note->update([
                'note' => $request->note,
                'appreciation' => $request->appreciation
            ]);
            return response()->json(['success' => 'Note mise à jour avec succès']);
        }
        return response()->json(['error' => 'Note introuvable']);
    }


    /**
     *
     */
    public function destroy($id) {
        $note = Note::findOrFail($id);
        if($note) {
            $note->delete();
            return response()->json(['success' => 'Note supprimé avec succès']);
        }
        return response()->json(['error' => 'Note introuvable']);
    }
}
