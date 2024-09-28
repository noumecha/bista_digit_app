<?php

namespace App\Http\Controllers;

use App\Models\CategorieActualite;
use Illuminate\Http\Request;

class CategorieActualiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = CategorieActualite::all();
        $search = $request->input('search');

        $query = CategorieActualite::query();
        if(!empty($search)) {
            $query->where('libelleCategorie', 'LIKE', "%{$search}%");
        }

        $categories = $query->paginate(4);
        return view('actualites.categories', compact('categories','search'));
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

        CategorieActualite::create([
            'libelleCategorie' => $request->libelleCategorie,
        ]);

        return redirect()->route('actualites.categories')->with('success', 'Catégorie ajoutée avec succès');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, CategorieActualite $categorieActualite, $id)
    {
        $categories = $categorieActualite::all();
        $categorieToEdit = $categorieActualite::findOrFail($id);
        $search = $request->input('search');
        $query = CategorieActualite::query();
        if(!empty($search)) {
            $query->where('libelleCategorie', 'LIKE', "%{$search}%");
        }

        $categories = $query->paginate(4);

        return view('actualites.categories', compact('categorieToEdit','categories','search'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategorieActualite $categorieActualite, $id)
    {
        $request->validate([
            'libelleCategorie' => 'required|min:3|max:255',
        ], [
            'libelleCategorie.required' => 'Veuillez entrez un libellé de Catégorie',
        ]);

        $categorie = $categorieActualite::findOrFail($id);
        $categorie->update($request->all());

        return redirect()->route('actualites.categories')->with('success', 'Catégorie d\'Actualité mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategorieActualite $categorieActualite, $id)
    {
        $categorie = $categorieActualite::findOrFail($id);
        $categorie->delete();

        return redirect()->route('actualites.categories')->with('deleteSuccess', 'Catégorie d\'Actualité supprimée avec succès');
    }
}
