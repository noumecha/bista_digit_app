<?php

namespace App\Http\Controllers;

use App\Models\CategorieActualite;
use Illuminate\Http\Request;

class CategorieActualiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CategorieActualite::all();
        return view('actualites.categories', compact('categories'));
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
    public function edit(CategorieActualite $categorieActualite, $id)
    {
        $categories = $categorieActualite::all();
        $categorieToEdit = $categorieActualite::findOrFail($id);

        return view('actualites.categories', compact('categorieToEdit','categories'));
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
