<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Evaluation;
use App\Models\Remplissage;
use App\Models\Trimestre;
use DateTime;
use Illuminate\Http\Request;

class BoosterEvaluationController extends Controller
{
    /**
     *
     */
    public function evaluations (Request $request) {
        // on initialize :
        $evals = Evaluation::all()->where('type','booster-evaluation');
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
        $query = Evaluation::query()->where('type','booster-evaluation')
            ->whereIn('trimestre_id', $trimestresYears);
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
            return view('partials._booster_evaluations_table', compact('evaluations','trimestres'));
        } else {
            return view('programme.booster_evaluations', compact('evaluations','trimestres'));
        }
    }
    /**
     *
     */
    public function evaluationSave (Request $request) {
        $request->validate([
            'libelleEvaluation' => 'required|min:3|max:255',
            'trimestre_id' => 'required',
            'dateDeDebut' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $startDate = new DateTime($value);
                    $yearStart = new DateTime(getCurrentYear()->dateDeDebut);
                    $yearEnd = new DateTime(getCurrentYear()->dateDeFin);
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
                    $endDate = new DateTime($value);
                    $yearStart = new DateTime(getCurrentYear()->dateDeDebut);
                    $yearEnd = new DateTime(getCurrentYear()->dateDeFin);
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
        if (isset($request->dateDeDebut) && isset($request->dateDeFin)) {
            if(new DateTime($request->dateDeFin) <= new DateTime($request->dateDeDebut)) {
                return response()->json([
                    'error' => 'La date de fin ne doit pas être inférieur ou égale à la date de debut'
                ]);
            }
        }
        $currentDate = new DateTime();
        $state = '';
        if($currentDate >= new DateTime($request->dateDeDebut) && $currentDate <= new DateTime($request->dateDeFin)) {
            $state = 'en cours';
        } elseif ($currentDate < new DateTime($request->dateDeDebut)) {
            $state = 'programmée';
        } else {
            $state = 'terminée';
        }
        /* check if the trimester already have 2 evaluation
        $trimestreIds = Trimestre::where('annee_scolaire_id', getCurrentYear()->id)->pluck('id');
        $checks = Evaluation::where('trimestre_id', $request->trimestre_id)->where('type','booster-evaluation')
            ->whereIn('trimestre_id', $trimestreIds);
        if($checks->count() === 2) {
            return response()->json([
                'error' => 'Un trimestre ne peut pas avoir plus de 2 évaluations en une année'
            ]);
        }*/
        $evaluation = Evaluation::create([
            'libelleEvaluation' => $request->libelleEvaluation,
            'trimestre_id' => $request->trimestre_id,
            'dateDeDebut' => $request->dateDeDebut,
            'dateDeFin' => $request->dateDeFin,
            'statut' => $state,
            'type' => 'booster-evaluation'
        ]);

        if ($evaluation) {
            return response()->json(['success' => 'Evaluation ajoutée avec succès']);
        }
    }

    /**
     *
     */
    public function evaluationEdit ($id) {
        $evaluationToEdit = Evaluation::findOrFail($id);
        $trimestre = Trimestre::findOrFail($evaluationToEdit->trimestre_id);
        return response()->json([
            'evaluationToEdit' => $evaluationToEdit,
            'dateDeDebutTrim' => $trimestre->dateDeDebut,
            'dateDeFinTrim' => $trimestre->dateDeFin,
        ]);
    }

    /**
     *
     */
    public function evaluationDelete ($id) {
        $evaluation = Evaluation::findOrFail($id);
        $rempliassage = Remplissage::where('evaluation_id', $evaluation->id);
        $rempliassage->delete();
        $evaluation->delete();
        return redirect()->route('booster.evaluations')->with('deleteSuccess', 'Evaluation supprimée avec succès');
    }

    /**
     *
     */
    public function evaluationUpdate (Request $request, $id) {
        $request->validate([
            'libelleEvaluation' => 'required|min:3|max:255',
            'trimestre_id' => 'required',
            'dateDeDebut' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $startDate = new DateTime($value);
                    $yearStart = new DateTime(getCurrentYear()->dateDeDebut);
                    $yearEnd = new DateTime(getCurrentYear()->dateDeFin);
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
                    $endDate = new DateTime($value);
                    $yearStart = new DateTime(getCurrentYear()->dateDeDebut);
                    $yearEnd = new DateTime(getCurrentYear()->dateDeFin);
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
        if (isset($request->dateDeDebut) && isset($request->dateDeFin)) {
            if(new DateTime($request->dateDeFin) <= new DateTime($request->dateDeDebut)) {
                return response()->json([
                    'error' => 'La date de fin ne doit pas être inférieur ou égale à la date de debut'
                ]);
            }
        }
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
}
