<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Evaluation;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Validation\Rule;

class TrimestreController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        // on initialize :
        $trims = Evaluation::all();
        foreach ($trims as $trim) {
            $endDate = new DateTime($trim->dateDeFin);
            $currentDate = new DateTime();
            if ($currentDate > $endDate && $trim->statut !== 'terminé') {
                $trim->update(['statut' => 'terminé']);
            }
        }
        $activeYear = AnneeScolaire::all()->where('statut', true)->first();
        // filter vars
        $searchTrimestre = $request->input('searchTrimestre');
        // querying
        $query = Trimestre::query()->where('annee_scolaire_id', $activeYear->id);

        // filtering
        if(!empty($searchTrimestre)) {
            $query->where('libelleTrimestre', 'LIKE', "%{$searchTrimestre}%");
        }

        $trimestres = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._trimestres_table', compact('trimestres','activeYear'));
        } else {
            return view('evaluation.trimestres', compact('trimestres','activeYear'));
        }
    }

    /**
     * create new trimestre
     */
    public function store(Request $request) {
        $request->validate([
            'libelleTrimestre' => ['required','unique:trimestres',],
            'annee_scolaire_id' => 'required',
            'dateDeDebut' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $year = AnneeScolaire::findOrFail($request->input('annee_scolaire_id'));
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
                    $year = AnneeScolaire::findOrFail($request->input('annee_scolaire_id'));
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
            'libelleTrimestre.required' => 'Veuillez entrez un libelle pour le trimestre',
            'libelleTrimestre.unique' => 'Ce trimestre existe déjà',
            'dateDeDebut.required' => 'Définissez une date de debut pour le trimestre',
            'dateDeFin.required' => 'Définissez une date de fin pour le trimestre',
            'annee_scolaire_id.required' => 'Selectionnez une année scolaire',
        ]);

        $currentDate = new DateTime();
        $state = '';
        if(new DateTime($request->dateDeDebut) >= $currentDate && new DateTime($request->dateDeFin) <= $currentDate) {
            $state = 'en cours';
        } else {
            $state = 'terminé';
        }

        $trimestre = Trimestre::create([
            'libelleTrimestre' => $request->libelleTrimestre,
            'annee_scolaire_id' => $request->annee_scolaire_id,
            'dateDeDebut' => $request->dateDeDebut,
            'dateDeFin' => $request->dateDeFin,
            'statut' => $state,
        ]);

        if($trimestre) {
            return response()->json(['success' => 'Trimestre ajouté avec succès']);
        } else {
            return response()->json(['error' => 'Erreur lors de l\'enregistrement du trimestre']);
        }
    }

    /**
     *  get trimestres date
    */
    public function getYearsDate($yearId) {
        $anneeScolaire = AnneeScolaire::findOrFail($yearId);
        return response()->json([
            'dateDeDebutYear' => $anneeScolaire->dateDeDebut,
            'dateDeFinYear' => $anneeScolaire->dateDeFin,
        ]);
    }

    /**
     * edit specific trimestre
     */
    public function edit($id) {
        $trimestreToEdit = Trimestre::findOrFail($id);
        $anneeScolaire = AnneeScolaire::findOrFail($trimestreToEdit->annee_scolaire_id);
        return response()->json([
            'trimestreToEdit' => $trimestreToEdit,
            'dateDeDebutYear' => $anneeScolaire->dateDeDebut,
            'dateDeFinYear' => $anneeScolaire->dateDeFin,
        ]);
    }

    /**
     * update specific trimestre
     */
    public function update(Request $request, $id) {
        $request->validate([
            'libelleTrimestre' => ['required',Rule::unique('trimestres')->ignore($id)],
            'annee_scolaire_id' => 'required',
            'dateDeDebut' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $year = AnneeScolaire::findOrFail($request->input('annee_scolaire_id'));
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
                    $year = AnneeScolaire::findOrFail($request->input('annee_scolaire_id'));
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
            'libelleTrimestre.required' => 'Veuillez entrez un libelle pour le trimestre',
            'libelleTrimestre.unique' => 'Ce trimestre existe déjà',
            'dateDeDebut.required' => 'Définissez une date de debut pour le trimestre',
            'dateDeFin.required' => 'Définissez une date de fin pour le trimestre',
            'annee_scolaire_id.required' => 'Selectionnez une année scolaire',
        ]);

        $currentDate = new DateTime();
        $state = '';
        if(new DateTime($request->dateDeDebut) >= $currentDate->format('Y-m-d') && new DateTime($request->dateDeFin) <= $currentDate->format('Y-m-d')) {
            $state = 'en cours';
        } else {
            $state = 'terminé';
        }

        $trimestre = Trimestre::findOrFail($id);

        $trimestre->update([
            'libelleTrimestre' => $request->libelleTrimestre,
            'dateDeDebut' => $request->dateDeDebut,
            'dateDeFin' => $request->dateDeFin,
            'annee_scolaire_id' => $request->annee_scolaire_id,
            'statut' => $state,
        ]);

        return response()->json(['success' => 'Trimestre mis à jour avec succès']);
    }


    /**
     * delete sepecific trimestre
     */
    public function destroy($id) {
        $trimestre = Trimestre::findOrFail($id);
        $evaluation = Evaluation::where('trimestre_id', $trimestre->id);
        $evaluation->delete();
        $trimestre->delete();
        return redirect()->route('evaluation.trimestres')->with('success', 'Trimestre supprimé avec succès');
    }
}
