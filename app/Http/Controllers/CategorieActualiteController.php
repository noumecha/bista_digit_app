<?php

namespace App\Http\Controllers;

use App\Models\CategorieActualite;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategorieActualiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchCategorie = $request->input('searchCategorie');
        // querying
        $query = CategorieActualite::query();
        // filtering
        if(!empty($searchCategorie)) {
            $query->where('libelleCategorie', 'LIKE', "%{$searchCategorie}%");
        }

        $categories = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._categories_actus_table', compact('categories'));
        } else {
            return view('actualites.categories', compact('categories'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'libelleCategorie' => 'required|min:3|max:255|unique:categorie_actualites,libelleCategorie',
        ], [
            'libelleCategorie.required' => 'Veuillez entrez un libellé de Catégorie',
            'libelleCategorie.unique' => 'Ce libellé de Catégorie existe',
        ]);

        $categorie = CategorieActualite::create([
            'libelleCategorie' => $request->libelleCategorie,
        ]);

        if($categorie) {
            return response()->json(['success' => 'Catégorie ajoutée avec succès']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'ajout de la catégorie']);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categorieToEdit = CategorieActualite::findOrFail($id);
        return response()->json(['categorieToEdit' => $categorieToEdit]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'libelleCategorie' => 'required|min:3|max:255',Rule::unique('categorie_actualites')->ignore($id),
        ], [
            'libelleCategorie.required' => 'Veuillez entrez un libellé de Catégorie',
            'libelleCategorie.unique' => 'Ce libellé de Catégorie existe',
        ]);

        $categorie = CategorieActualite::findOrFail($id);
        $categorie->update($request->all());

        return response()->json(['success' => 'Catégorie d\'Actualité mise à jour avec succès']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categorie = CategorieActualite::findOrFail($id);
        $categorie->delete();
        return redirect()->route('actualites.categories')->with('deleteSuccess', 'Catégorie d\'Actualité supprimée avec succès');
    }
}
