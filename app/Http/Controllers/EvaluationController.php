<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Evaluation;
use App\Models\Remplissage;
use App\Models\Trimestre;
use DateTime;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        // on initialize :
        $evals = Evaluation::all();
        foreach ($evals as $eval) {
            $endDate = new DateTime($eval->dateDeFin);
            $startDate = new DateTime($eval->dateDeDebut);
            $currentDate = new DateTime();
            if ($currentDate > $endDate && $eval->statut !== 'terminée') {
                $eval->update(['statut' => 'terminée']);
            } elseif ($currentDate >= $startDate && $currentDate <= $endDate) {
                $eval->update(['statut' => 'en cours']);
            } elseif ($currentDate < $startDate) {
                $eval->update(['statut' => 'programmée']);
            }
        }
        // useful variables
        $trimestres = Trimestre::all();
        $activeYear = AnneeScolaire::all()->where('statut',true)->first();

        // filters
        $searchEvaluation = $request->input('searchEvaluation');
        $trimestreFilter = $request->input('trimestreFilter');
        $statutFilter = $request->input('statutFilter');

        // querying
        $trimestresYears = Trimestre::all()->where('annee_scolaire_id', $activeYear->id)->pluck('id');
        $query = Evaluation::query()->whereIn('trimestre_id', $trimestresYears);

        // filtering
        if(!empty($searchEvaluation)) {
            $query->where('libelleEvaluation', 'LIKE', "%{$searchEvaluation}%");
        }
        if(!empty($trimestreFilter)) {
            $query->where('trimestre_id',$trimestreFilter);
        }
        if(!empty($statutFilter)) {
            $query->where('statut',$statutFilter);
        }

        $evaluations = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._evaluations_table', compact('evaluations','trimestres'));
        } else {
            return view('evaluation.evaluations', compact('evaluations','trimestres'));
        }
    }

    /**
     *
     */
    public function store(Request $request) {
        $request->validate([
            'libelleEvaluation' => 'required|min:3|max:255',
            'trimestre_id' => 'required',
            'dateDeDebut' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $year = AnneeScolaire::all()->where('statut',true)->first();
                    $startDate = new DateTime($value);
                    $yearStart = new DateTime($year->dateDeDebut);
                    $yearEnd = new DateTime($year->dateDeFin);
                    if ($startDate < $yearStart || $startDate > $yearEnd) {
                        $fail('La date de début doit être comprise entre Septembre '
                        . $yearStart->format('Y') . ' et Juillet '
                        . $yearEnd->format('Y'));
                    }
                },
            ],
            'dateDeFin' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $year = AnneeScolaire::all()->where('statut',true)->first();
                    $endDate = new DateTime($value);
                    $yearStart = new DateTime($year->dateDeDebut);
                    $yearEnd = new DateTime($year->dateDeFin);
                    if ($endDate < $yearStart || $endDate > $yearEnd) {
                        $fail('La date de fin doit être comprise entre Septembre '
                        . $yearStart->format('Y') . ' et Juillet '
                        . $yearEnd->format('Y'));
                    }
                },
            ],
        ], [
            'libelleEvaluation.required' => 'Veuillez entrez un libelle pour l\'évaluation',
            'trimestre_id.required' => 'Selectionnez une année scolaire',
            'dateDeDebut.required' => 'Définissez une date de debut de l\'évaluation',
            'dateDeFin.required' => 'Définissez une date de fin de l\'évaluation',
            'annee_scolaire_id.required' => 'Selectionnez une année scolaire',
        ]);

        $currentDate = new DateTime();
        $state = '';
        if($currentDate >= new DateTime($request->dateDeDebut) && $currentDate <= new DateTime($request->dateDeFin)) {
            $state = 'en cours';
        } elseif ($currentDate < new DateTime($request->dateDeDebut)) {
            $state = 'programmée';
        } else {
            $state = 'terminée';
        }

        $evaluation = Evaluation::create([
            'libelleEvaluation' => $request->libelleEvaluation,
            'trimestre_id' => $request->trimestre_id,
            'dateDeDebut' => $request->dateDeDebut,
            'dateDeFin' => $request->dateDeFin,
            'statut' => $state,
        ]);

        if ($evaluation) {
            return response()->json(['success' => 'Evaluation ajoutée avec succès']);
        }
    }
    /**
     *  get trimestres date
    */
    public function getTrimsDate($trimId) {
        $trimestre = Trimestre::findOrFail($trimId);
        return response()->json([
            'dateDeDebutTrim' => $trimestre->dateDeDebut,
            'dateDeFinTrim' => $trimestre->dateDeFin,
        ]);
    }

    /**
     * edit specific evaluation
     */
    public function edit($id) {
        $evaluationToEdit = Evaluation::findOrFail($id);
        $trimestre = Trimestre::findOrFail($evaluationToEdit->trimestre_id);
        return response()->json([
            'evaluationToEdit' => $evaluationToEdit,
            'dateDeDebutTrim' => $trimestre->dateDeDebut,
            'dateDeFinTrim' => $trimestre->dateDeFin,
        ]);
    }

    /**
     * udpate specific evaluation
     */
    public function update(Request $request, $id) {
        $request->validate([
            'libelleEvaluation' => 'required|min:3|max:255',
            'trimestre_id' => 'required',
            'dateDeDebut' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $year = AnneeScolaire::all()->where('statut',true)->first();
                    $startDate = new DateTime($value);
                    $yearStart = new DateTime($year->dateDeDebut);
                    $yearEnd = new DateTime($year->dateDeFin);
                    if ($startDate < $yearStart || $startDate > $yearEnd) {
                        $fail('La date de début doit être comprise entre Septembre '
                        . $yearStart->format('Y') . ' et Juillet '
                        . $yearEnd->format('Y'));
                    }
                },
            ],
            'dateDeFin' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $year = AnneeScolaire::all()->where('statut',true)->first();
                    $endDate = new DateTime($value);
                    $yearStart = new DateTime($year->dateDeDebut);
                    $yearEnd = new DateTime($year->dateDeFin);
                    if ($endDate < $yearStart || $endDate > $yearEnd) {
                        $fail('La date de fin doit être comprise entre Septembre '
                        . $yearStart->format('Y') . ' et Juillet '
                        . $yearEnd->format('Y'));
                    }
                },
            ],
        ], [
            'libelleEvaluation.required' => 'Veuillez entrez un libelle pour l\'évaluation',
            'trimestre_id.required' => 'Selectionnez une année scolaire',
            'dateDeDebut.required' => 'Définissez une date de debut de l\'évaluation',
            'dateDeFin.required' => 'Définissez une date de fin de l\'évaluation',
            'annee_scolaire_id.required' => 'Selectionnez une année scolaire',
        ]);

        $currentDate = new DateTime();
        $state = '';
        if($currentDate >= new DateTime($request->dateDeDebut) && $currentDate <= new DateTime($request->dateDeFin)) {
            $state = 'en cours';
        } elseif ($currentDate < new DateTime($request->dateDeDebut)) {
            $state = 'programmée';
        } else {
            $state = 'terminée';
        }

        $evaluation = Evaluation::findOrFail($id);

        $evaluation->update([
            'libelleEvaluation' => $request->libelleEvaluation,
            'trimestre_id' => $request->trimestre_id,
            'dateDeDebut' => $request->dateDeDebut,
            'dateDeFin' => $request->dateDeFin,
            'statut' => $state,
        ]);

        return response()->json(['success' => 'Evaluation mise à jour avec succès']);

    }


    /**
     * delete specific evaluation
     */
    public function destroy($id) {
        $evaluation = Evaluation::findOrFail($id);
        $rempliassage = Remplissage::where('evaluation_id', $evaluation->id);
        $rempliassage->delete();
        $evaluation->delete();
        return redirect()->route('evaluation.evaluations')->with('success', 'Evaluation supprimée avec succès');
    }
}
