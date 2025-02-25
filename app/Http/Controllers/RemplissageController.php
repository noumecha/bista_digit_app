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
    public function index(Request $request) {
        //
        $evaluations = Evaluation::all();

        // filter vars
        $evaluationFilter = $request->input('evaluationFilter');
        $statutFilterr = $request->input('statutFilter');
        // querying
        $query = Remplissage::query();
        // filtering
        if(!empty($evaluationFilter)) {
            $query->where('evaluation_id', $evaluationFilter);
        }
        if(!empty($statutFilter)) {
            $query->where('statut', $statutFilter);
        }

        $remplissages = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._remplissages_table', compact('remplissages', 'evaluations'));
        } else {
            return view('evaluation.remplissages', compact('remplissages', 'evaluations'));
        }
    }

    /**
     * store specific remplissage configuration
     */
    public function store(Request $request) {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'statut' => 'required',
            'duree' => 'required|numeric|integer',
            'openDays' => 'required',
            'evaluation_id' => 'required',
        ], [
            'date_debut.required' => 'Veuillez selectionner la date debut du remplissage',
            'date_fin.required' => 'Veuillez selectionner la date de fin du remplissage pour cette évaluation',
            'evaluation_id.required' => 'Selectionnez une année scolaire',
            'duree' => 'Veuillez définier la durée du remplissage',
            'evaluation_id.unique' => 'Un remplissage est déja configurer pour cette évaluation',
        ]);

        $remplissage = Remplissage::create([
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'statut' => $request->statut,
            'evaluation_id' => $request->evaluation_id,
        ]);

        if ($remplissage) {
            return response()->json(["success", "Remplissage configuré avec succès"]);
        }
    }

    /**
     * edit a specific configuration
     */
    public function edit($id) {
        $remplissageToEdit = Remplissage::findOrFail($id);
        return response()->json(['remplissageToEdit' => $remplissageToEdit]);
    }

    /**
     * update specific remplissage configuration
     */
    public function update(Request $request, $id) {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'statut' => 'required',
            'duree' => 'required|numeric|integer',
            'openDays' => 'required',
            'evaluation_id' => 'required',
        ], [
            'date_debut.required' => 'Veuillez selectionner la date debut du remplissage',
            'date_fin.required' => 'Veuillez selectionner la date de fin du remplissage pour cette évaluation',
            'evaluation_id.required' => 'Selectionnez une année scolaire',
            'duree' => 'Veuillez définier la durée du remplissage',
            'evaluation_id.unique' => 'Un remplissage est déja configurer pour cette évaluation',
        ]);
        $remplissage = Remplissage::findOrFail($id);

        $remplissage->update($request->all());

        return response()->json(['success','Remplissage mis à jour avec succès']);
    }


    /**
     * deleting a specific remplissage
     */
    public function destroy($id) {
        $remplissage = Remplissage::findOrFail($id);
        $remplissage->delete();
        return redirect()->route('evaluation.remplissages')->with('deleteSuccess', 'Remplissage supprimée avec succès');
    }
}
