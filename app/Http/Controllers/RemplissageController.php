<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Remplissage;
use Illuminate\Http\Request;

class RemplissageController extends Controller
{
    /**
     *
     */
    public function index() {
        $remplissages = Remplissage::all();
        $evaluations = Evaluation::all();

        $query = Remplissage::query();
        $remplissages = $query->paginate(10);

        return view('evaluation.remplissages', compact('remplissages', 'evaluations'));
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

        return redirect()->route('evaluation.remplissages')->with('success', 'Remplissage ajoutée avec succès');
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

        return view('evaluation.remplissages', compact('remplissages', 'evaluations','remplissageToEdit'));
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

        return redirect()->route('evaluation.remplissages')->with('success', 'Remplissage mis à jour avec succès');
    }


    /**
     *
     */
    public function destroy($id) {
        $remplissage = Remplissage::findOrFail($id);
        $remplissage->delete();

        return redirect()->route('evaluation.remplissages')->with('success', 'Remplissage supprimée avec succès');
    }
}
