<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Remplissage;
use Illuminate\Http\Request;
use DateTime;

class RemplissageController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        // on initialize :
        $remps = Remplissage::all();
        foreach ($remps as $remp) {
            $endDate = new DateTime($remp->date_fin);
            $startDate = new DateTime($remp->date_debut);
            $currentDate = new DateTime();
            if ($currentDate > $endDate && $remp->statut !== 'terminé') {
                $remp->update(['statut' => 'terminé']);
            } elseif ($currentDate >= $startDate && $currentDate <= $endDate) {
                $remp->update(['statut' => 'en cours']);
            } elseif ($currentDate < $startDate) {
                $remp->update(['statut' => 'programmé']);
            }
        }
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
            'duree' => 'required|numeric|integer',
            'openDays' => 'required|boolean',
            'evaluation_id' => 'required',
        ], [
            'date_debut.required' => 'Veuillez selectionner la date debut du remplissage',
            'date_fin.required' => 'Veuillez selectionner la date de fin du remplissage pour cette évaluation',
            'evaluation_id.required' => 'Selectionnez une année scolaire',
            'duree' => 'Veuillez définir la durée du remplissage',
            'evaluation_id.unique' => 'Un remplissage est déja configurer pour cette évaluation',
        ]);

        $currentDate = new DateTime();
        $state = '';
        if($currentDate >= new DateTime($request->date_debut) && $currentDate <= new DateTime($request->date_fin)) {
            $state = 'en cours';
        } elseif ($currentDate < new DateTime($request->date_debut)) {
            $state = 'programmé';
        } else {
            $state = 'terminé';
        }

        $exists = Remplissage::where('evaluation_id', $request->evaluation_id)->exists();
        if($exists) {
            return response()->json([
                'error' => 'Une configuration de remplissage pour cette évaluation existe déjà!'
            ]);
        }

        $remplissage = Remplissage::create([
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'duree' => $request->duree,
            'openDays' => $request->openDays ? 1 : 0,
            'statut' => $state,
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
        $evaluation = Evaluation::findOrFail($remplissageToEdit->evaluation_id);
        return response()->json([
            'remplissageToEdit' => $remplissageToEdit,
            'dateDeFinEval' => $evaluation->dateDeFin
        ]);
    }

    /**
     *  get evaluations dates
    */
    public function getEvalsDate($evalId) {
        $evaluation = Evaluation::findOrFail($evalId);
        return response()->json([
            'dateDeFinEval' => $evaluation->dateDeFin,
        ]);
    }

    /**
     * update specific remplissage configuration
     */
    public function update(Request $request, $id) {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'duree' => 'required|numeric|integer',
            'openDays' => 'required|boolean',
        ], [
            'date_debut.required' => 'Veuillez selectionner la date debut du remplissage',
            'date_fin.required' => 'Veuillez selectionner la date de fin du remplissage pour cette évaluation',
            'evaluation_id.required' => 'Selectionnez une année scolaire',
            'duree' => 'Veuillez définir la durée du remplissage',
        ]);

        $currentDate = new DateTime();
        $state = '';
        if($currentDate >= new DateTime($request->date_debut) && $currentDate <= new DateTime($request->date_fin)) {
            $state = 'en cours';
        } elseif ($currentDate < new DateTime($request->date_debut)) {
            $state = 'programmé';
        } else {
            $state = 'terminé';
        }

        $remplissage = Remplissage::findOrFail($id);

        $remplissage->update([
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'duree' => $request->duree,
            'openDays' => $request->openDays ? 1 : 0,
            'statut' => $state,
        ]);

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
