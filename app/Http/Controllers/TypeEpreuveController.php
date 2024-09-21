<?php

namespace App\Http\Controllers;

use App\Models\TypeEpreuve;
use Illuminate\Http\Request;

class TypeEpreuveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typeEpreuves = TypeEpreuve::all();
        return view('education.type_epreuves', compact('typeEpreuves'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'libelleTypeEpreuve' => 'required|min:2|max:255|unique:type_epreuves,libelleTypeEpreuve',
        ], [
            'libelleTypeEpreuve.required' => 'Veuillez entrez un libellé de Type d\'épreuve',
            'libelleTypeEpreuve.unique' => 'Ce libellé existe déjà',
        ]);

        TypeEpreuve::create([
            'libelleTypeEpreuve' => $request->libelleTypeEpreuve,
        ]);

        return redirect()->route('education.type_epreuves')->with('success', 'Type d\'épreuve ajoutée avec succès');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypeEpreuve $typeEpreuve, $id)
    {
        $typeEpreuves = $typeEpreuve::all();
        $typeEpreuveToEdit = $typeEpreuve::findOrFail($id);

        return view('education.type_epreuves', compact('typeEpreuveToEdit','typeEpreuves'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TypeEpreuve $typeEpreuve, $id)
    {
        $request->validate([
            'libelleTypeEpreuve' => 'required|min:3|max:255',
        ], [
            'libelleTypeEpreuve.required' => 'Veuillez entrez un libellé de Type d\'épreuve',
        ]);

        $typeEpreuve = $typeEpreuve::findOrFail($id);
        $typeEpreuve->update($request->all());

        return redirect()->route('education.type_epreuves')->with('success', 'Type d\'épreuve mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeEpreuve $typeEpreuve, $id)
    {
        $typeEpreuve = $typeEpreuve::findOrFail($id);
        $typeEpreuve->delete();

        return redirect()->route('education.type_epreuves')->with('deleteSuccess', 'Type d\'épreuve supprimé avec succès');
    }
}

