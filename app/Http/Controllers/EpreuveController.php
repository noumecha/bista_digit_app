<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Epreuve;
use App\Models\Matiere;
use App\Models\TypeEpreuve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EpreuveController extends Controller
{
    /**
     * index function
     */
    public function index (Request $request) {
        // usefull vars
        $classes = Classe::all();
        $matieres = Matiere::all();
        $typeEpreuves = TypeEpreuve::all();
        $yearEpreuves = Epreuve::select('anneeEpreuve')->distinct()->get();
        // filter vars
        $searchEpreuve = $request->input('searchEpreuve');
        $yearFilter = $request->input('yearFilter');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $typeEpreuveFilter = $request->input('typeEpreuveFilter');
        $query = Epreuve::query();
        if (!empty($searchEpreuve)) {
            $query->where('libelleEpreuve', 'LIKE', "%{$searchEpreuve}%");
        }
        if (!empty($typeEpreuveFilter)) {
            $query->where('type_epreuve_id', $typeEpreuveFilter);
        }
        if (!empty($classeFilter)) {
            $query->where('classe_id', $classeFilter);
        }
        if (!empty($matiereFilter)) {
            $query->where('matiere_id', $matiereFilter);
        }
        if (!empty($yearFilter)) {
            $query->where('anneeEpreuve', $yearFilter);
        }
        $epreuves = $query->paginate(10);
        if($request->ajax()) {
            return response()->json([
                'epreuves' => view('partials._epreuves_table', compact('epreuves'))->render(),
                'yearEpreuves' => $yearEpreuves
            ]);
        } else {
            return view('education.epreuves', compact('epreuves','yearEpreuves','typeEpreuves','matieres','classes'));
        }
    }

    /**
     * save some epreuve in db
     */
    public function store(Request $request) {
        $request->validate([
            'libelleEpreuve' => 'required|min:3|max:255|unique:epreuves,libelleEpreuve',
            'anneeEpreuve' => 'required|min:9|max:9|regex:/^[0-9]{4}\/[0-9]{4}$/',
            'fichier' => 'required|mimes:pdf,jpg,jpeg,png|max:4096',
            'matiere_id' => 'required',
            'classe_id' => 'required',
            'type_epreuve_id' => 'required'
        ], [
            'libelleEpreuve.required' => 'Veuiellez entré un libellé pour l\'épreuve',
            'libelleEpreuve.unique' => 'Ce libellé d\'épreuve existe déjà',
            'anneeEpreuve.required' => 'Entrée l\'année de l\'épreuve',
            'anneeEpreuve.regex' => 'l\'année de l\'épreuve doit être au format XXXX/XXXX -> exemple 2024/2025',
            'anneeEpreuve.max' => 'l\'année doit contenir au moins 9 caractères',
            'fichier.required' => 'Veuillez choisir un fichier',
            'matiere_id.required' => 'Veuillez selectionner une matière',
            'classe_id.required' => 'Veuillez selectionner une classe',
            'type_epreuve_id.required' => 'Veuillez selectionner le type d\'épreuve',
        ]);

        //
        $fileName = '';
        if ($request->hasFile('fichier')) {
            $matiere = Matiere::findOrFail($request->matiere_id);
            $classe = Classe::findOrFail($request->classe_id);
            $file = $request->file('fichier');
            $libelle = str_replace(' ','_', trim($request->libelleEpreuve));
            $fileName = $matiere->libelleMatiere. '_'.$libelle.'_'.$classe->libClasse.'.'.$file->getClientOriginalExtension();
        }
        $epreuve = Epreuve::create([
            'libelleEpreuve' => $request->libelleEpreuve,
            'anneeEpreuve' => $request->anneeEpreuve,
            'user_id' => Auth::id(),
            'type_epreuve_id' => $request->type_epreuve_id,
            'matiere_id' => $request->matiere_id,
            'classe_id' => $request->classe_id,
            'fichier' => $file->storeAs('epreuves', $fileName, 'public'),
            //'fichier' => $request->hasFile('fichier') ? $request->file('fichier')->store('epreuves', 'public') : '',//$file->storeAs('epreuves', $fileName, 'public'),
        ]);
        if ($epreuve) {
            return response()->json(["success" => "Epreuve ajouté avec succès"]);
        } else {
            return response()->json(["error" => "Erreur lors de l'ajout d'une d'épreuve !"]);
        }
    }


    /**
     * edit epreuve
     */
    public function edit($id) {
        $epreuveToEdit = Epreuve::findOrFail($id);
        return response()->json([
            'epreuveToEdit' => $epreuveToEdit
        ]);
    }

    /**
     * update information of some epreuve
     */
    public function update(Request $request, $id) {
        $request->validate([
            'libelleEpreuve' => 'required|min:3|max:255',
            'anneeEpreuve' => 'required|min:9|max:9|regex:/^[0-9]{4}\/[0-9]{4}$/',
            'fichier' => 'mimes:jpg,jpeg,pdf,png|max:4096',
            'matiere_id' => 'required',
            'classe_id' => 'required',
            'type_epreuve_id' => 'required'
        ], [
            'libelleEpreuve.required' => 'Veuillez entré un libellé pour l\'épreuve',
            'anneeEpreuve.regex' => 'l\'année de l\'épreuve doit être au format XXXX/XXXX -> exemple 2024/2025',
            'anneeEpreuve.required' => 'Entrée l\'année de l\'épreuve',
            'matiere_id.required' => 'Veuillez selectionner une matière',
            'classe_id.required' => 'Veuillez selectionner une classe',
            'type_epreuve_id.required' => 'Veuillez selectionner le type d\'épreuve',
        ]);
        $epreuve = Epreuve::findOrFail($id);

        $fileName = '';
        if ($request->hasFile('fichier')) {
            $matiere = Matiere::findOrFail($request->matiere_id);
            $classe = Classe::findOrFail($request->classe_id);
            $file = $request->file('fichier');
            $libelle = str_replace(' ','_', trim($request->libelleEpreuve));
            $fileName = $matiere->libelleMatiere. '_'.$libelle.'_'.$classe->libClasse.'.'.$file->getClientOriginalExtension();
            if ($epreuve->fichier) {
                Storage::disk('public')->delete($epreuve->fichier);
            }
            $epreuve->fichier = $file->storeAs('epreuves', $fileName, 'public');
        }
        $epreuve->update($request->except('fichier'));
        return response()->json([
            'success' => 'Epreuve mise à jour avec succès'
        ]);
    }

    /**
     * delete a specific epreuve
     */
    public function destroy($id) {
        $epreuve = Epreuve::findOrFail($id);
        $epreuve->delete();
        return redirect()->route('education.epreuves')->with('deleteSuccess', 'Epreuve supprimée avec succès');
    }
}
