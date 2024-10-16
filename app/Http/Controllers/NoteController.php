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
        $notes = Note::all();
        $matieres = Matiere::all();
        $remplissages = Remplissage::all()->where('statut','=','activé');
        $evaluations = Evaluation::all();
        $classes = Classe::all();
        $searchNote = $request->input('searchNote');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $remplissageFilter = $request->input('remplissageFilter');
        $students = User::query()->where('typeUser','=','eleve')->paginate(10);

        $query = Note::query();
        if(!empty($searchNote)) {
            $query->whereHas('user', function($q) use ($searchNote) {
                $q->where('name','LIKE',"%{$searchNote}%");
            });
        }
        if (!empty($classeFilter)) {
            $query->where('classe_id', $classeFilter);
        }
        if (!empty($matiereFilter)) {
            $query->where('matiere_id', $matiereFilter);
        }
        if (!empty($remplissageFilter)) {
            $query->where('remplissage_id',$remplissageFilter);
        }
        $notes = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._note_table', compact('students','notes'))->render();
        } else {
            return view('evaluation.notes', compact('remplissages','searchNote','classeFilter','matiereFilter','remplissageFilter','classes','evaluations','matieres','students','evaluations','notes'));
        }
    }

    /**
     *
     */
    public function store(Request $request) {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'statut' => 'required',
            'evaluation_id' => 'required',
        ], [
            'date_debut.required' => 'Veuillez selectionner la date debut du remplissage',
            'date_fin.required' => 'Veuillez selectionner la date de fin du remplissage pour cette évaluation',
            'evaluation_id.required' => 'Selectionnez une année scolaire',
            'evaluation_id.unique' => 'Un remplissage est déja configurer pour cette évaluation',
        ]);

        Remplissage::create([
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'statut' => $request->statut,
            'evaluation_id' => $request->evaluation_id,
        ]);

        return redirect()->route('evaluation.notes')->with('success', 'Remplissage ajoutée avec succès');
    }

    /**
     *
     */
    public function edit($id) {
        $remplissages = Remplissage::all();
        $evaluations = Evaluation::all();
        $remplissageToEdit = Remplissage::findOrFail($id);

        $query = Remplissage::query();
        $remplissages = $query->paginate(10);

        return view('evaluation.notes', compact('remplissages', 'evaluations','remplissageToEdit'));
    }

    /**
     *
     */
    public function update(Request $request, $id) {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'statut' => 'required',
            'evaluation_id' => 'required',
        ], [
            'date_debut.required' => 'Veuillez selectionner la date debut du remplissage',
            'date_fin.required' => 'Veuillez selectionner la date de fin du remplissage pour cette évaluation',
            'evaluation_id.required' => 'Selectionnez une année scolaire',
        ]);
        $remplissage = Remplissage::findOrFail($id);

        $remplissage->update($request->all());

        return redirect()->route('evaluation.notes')->with('success', 'Remplissage mis à jour avec succès');
    }


    /**
     *
     */
    public function destroy($id) {
        $remplissage = Remplissage::findOrFail($id);
        $remplissage->delete();

        return redirect()->route('evaluation.notes')->with('success', 'Remplissage supprimée avec succès');
    }
}
