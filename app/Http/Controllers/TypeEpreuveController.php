<?php

namespace App\Http\Controllers;

use App\Models\TypeEpreuve;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TypeEpreuveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TypeEpreuve::query();
        $searchText = $request->input('searchText');
        if(!empty($searchText)) {
            $query->where('libelleTypeEpreuve','LIKE' ,"%{$searchText}%");
        }
        $typeEpreuves = $query->paginate(10);
        if($request->ajax()) {
            return view('partials._type_epreuves_table', compact('typeEpreuves'));
        } else {
            return view('education.type_epreuves', compact('typeEpreuves'));
        }
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

        $typeEpreuve = TypeEpreuve::create([
            'libelleTypeEpreuve' => $request->libelleTypeEpreuve,
        ]);

        if ($typeEpreuve) {
            return response()->json(["success" => "Type d'épreuve ajoutée avec succès"]);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $typeEpreuveToEdit = TypeEpreuve::findOrFail($id);
        return response()->json([
            'typeEpreuveToEdit' => $typeEpreuveToEdit
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'libelleTypeEpreuve' => ['required','min:3','max:255',Rule::unique('type_epreuves')->ignore($id)]
        ], [
            'libelleTypeEpreuve.required' => 'Veuillez entrez un libellé de Type d\'épreuve',
            'libelleTypeEpreuve.unique' => 'Ce libellé existe déjà',
        ]);
        $typeEpreuve = TypeEpreuve::findOrFail($id);
        $typeEpreuve->update($request->all());
        return response()->json(['success' => 'Type d\'épreuve mis à jour avec succès']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $typeEpreuve = TypeEpreuve::findOrFail($id);
        $typeEpreuve->delete();
        return redirect()->route('education.type_epreuves')->with('deleteSuccess', 'Type d\'épreuve supprimé avec succès');
    }
}

