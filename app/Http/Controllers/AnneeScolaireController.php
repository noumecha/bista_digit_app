<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\ClasseEffectif;
use App\Models\FonctionAnneeScolaireUser;
use App\Models\UserAnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DateTime;


class AnneeScolaireController extends Controller
{
    /**
     * first function to show the datas
     */
    public function index(Request $request) {
        $searchYear = $request->input('searchYear');
        $query = AnneeScolaire::query();

        if(!empty($searchYear)) {
            $query->where('libelleAnneeScolaire', 'LIKE', "%{$searchYear}%");
        }

        $years = $query->paginate(10);
        if($request->ajax()) {
            return view('partials._year_table', compact('years'));
        } else {
            return view('anneescolaire.years', compact('years'));
        }
    }

     /**
     * saving school year
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) {
        $request->validate([
            'libelleAnneeScolaire' => [
                'required',
                'unique:annee_scolaires',
                'regex:/^[0-9]{4}\/[0-9]{4}$/',
            ],
            'dateDeDebut' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $libelleAnneeScolaire = $request->input('libelleAnneeScolaire');
                    $years = explode('/', $libelleAnneeScolaire);
                    $startDate = new DateTime($value);
                    $yearStart = new DateTime($years[0] . '-09-01');
                    $yearEnd = new DateTime($years[1] . '-07-31');
                    if ($startDate < $yearStart || $startDate > $yearEnd) {
                        $fail('La date de début doit être comprise entre Septembre ' . $years[0] . ' et Juillet ' . $years[1]);
                    }
                },
            ],
            'dateDeFin' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $libelleAnneeScolaire = $request->input('libelleAnneeScolaire');
                    $years = explode('/', $libelleAnneeScolaire);
                    $endDate = new DateTime($value);
                    $yearStart = new DateTime($years[0] . '-09-01');
                    $yearEnd = new DateTime($years[1] . '-07-31');
                    if ($endDate < $yearStart || $endDate > $yearEnd) {
                        $fail('La date de fin doit être comprise entre Septembre ' . $years[0] . ' et Juillet ' . $years[1]);
                    }
                },
            ],
        ], [
            'libelleAnneeScolaire.required' => 'Définissez une année scolaire',
            'libelleAnneeScolaire.unique' => 'Cette année scolaire existe déjà',
            'libelleAnneeScolaire.regex' => 'le libbellé doit être au format XXXX/XXXX -> exemple 2024/2025',
            'dateDeDebut.required' => 'Définissez une date de debut pour l\'année scolaire',
            'dateDeFin.required' => 'Définissez une date de fin pour l\'année scolaire',
        ]);
        $anneeScolaire = AnneeScolaire::create([
            'libelleAnneeScolaire' => $request->libelleAnneeScolaire,
            'dateDeDebut' => $request->dateDeDebut,
            'dateDeFin' => $request->dateDeFin,
            'statut' => false,
        ]);
        // create all ClasseEffectifs when creating a year :
        $classes = Classe::all();
        foreach ($classes as $classe) {
            ClasseEffectif::create([
                'annee_scolaire_id' => $anneeScolaire->id,
                'classe_id' => $classe->id
            ]);
        }
        if($anneeScolaire) {
            return response()->json(['success' => 'Année scolaire ajoutée avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur lors de l\'enregistrement de la nouvelle année']);
        }
    }

    /**
     * function to edit year
     */
    public function edit($id) {
        $yearToEdit = AnneeScolaire::findOrFail($id);
        return response()->json(['year' => $yearToEdit]);
    }

    /**
     * this function helps to activate a year for using his datas
     */
    public function activate($id) {

        AnneeScolaire::where('statut', '=', true)->update(['statut' => false]);

        $year = AnneeScolaire::findOrFail($id);
        $year->update([
            'statut' => true,
        ]);

        return redirect()->route('annee_scolaire.show')->with('listSuccess', 'Année scolaire activé avec succès!');
    }

    /**
     * this function helps to deactivate a year.
     */
    public function desactivate($id) {
        $year = AnneeScolaire::findOrFail($id);
        $year->update([
            'statut' => false,
        ]);

        return redirect()->route('annee_scolaire.show')->with('listSuccess', 'Année scolaire désactivé avec succès!');
    }

    /**
     * function to update a year.
     */
    public function update(Request $request, $id) {
        $request->validate([
            'libelleAnneeScolaire' => [
                'required',
                'regex:/^[0-9]{4}\/[0-9]{4}$/',
                Rule::unique('annee_scolaires')->ignore($id)
            ],
            'dateDeDebut' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $libelleAnneeScolaire = $request->input('libelleAnneeScolaire');
                    $years = explode('/', $libelleAnneeScolaire);
                    $startDate = new DateTime($value);
                    $yearStart = new DateTime($years[0] . '-09-01');
                    $yearEnd = new DateTime($years[1] . '-07-31');
                    if ($startDate < $yearStart || $startDate > $yearEnd) {
                        $fail('La date de début doit être comprise entre Septembre ' . $years[0] . ' et Juilet ' . $years[1]);
                    }
                },
            ],
            'dateDeFin' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $libelleAnneeScolaire = $request->input('libelleAnneeScolaire');
                    $years = explode('/', $libelleAnneeScolaire);
                    $endDate = new DateTime($value);
                    $yearStart = new DateTime($years[0] . '-09-01');
                    $yearEnd = new DateTime($years[1] . '-07-31');
                    if ($endDate < $yearStart || $endDate > $yearEnd) {
                        $fail('La date de fin doit être comprise entre Septembre ' . $years[0] . ' et Juillet ' . $years[1]);
                    }
                },
            ],
        ], [
            'libelleAnneeScolaire.unique' => 'Cette année scolaire existe déjà',
            'libelleAnneeScolaire.regex' => 'le libbellé doit être au format XXXX/XXXX -> exemple 2024/2025',
            'libelleAnneeScolaire.required' => 'Définissez une année scolaire',
            'dateDeDebut.required' => 'Définissez une date de debut pour l\'année scolaire',
            'dateDeFin.required' => 'Définissez une date de fin pour l\'année scolaire',
         ]);

        $year = AnneeScolaire::findOrFail($id);
        $year->update($request->all());

        return response()->json(['success' => 'Année mise à jour avec succès']);
    }

    /**
     * function to delete a year
     */
    public function destroy($id) {
        $year = AnneeScolaire::findOrFail($id);
        $userYears = UserAnneeScolaire::where('annee_scolaire_id',$year->id);
        $fonctionYearUser = FonctionAnneeScolaireUser::where('annee_scolaire_id',$year->id);
        $classeEffectifs = ClasseEffectif::where('annee_scolaire_id', $year->id);
        try {
            $year->delete();
            $userYears->delete();
            $fonctionYearUser->delete();
            $classeEffectifs->delete();
            return redirect()->route('anneescolaire.years')->with('deleteSuccess', 'Année supprimée avec succès');
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
}
