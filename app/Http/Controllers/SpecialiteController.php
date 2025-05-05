<?php

namespace App\Http\Controllers;

use App\Models\Specialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SpecialiteController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        # useful var
        $query = Specialite::query();
        # filtering
        $searchText = $request->input('searchText');
        if (!empty($searchText)) {
            $query->where('specialite_title', 'LIKE', "%{$searchText}%")
                ->orWhere('contenu', 'LIKE', "%{$searchText}%");
        }
        $specialites = $query->paginate(10);
        if($request->ajax()) {
            return view('partials._specialites_table', compact('specialites'));
        } else {
            return view('configurations.specialites', compact('specialites'));
        }
    }

    /**
     * add a new specialite in db
     */
    public function store(Request $request) {
        $request->validate([
            'specialite_title' => 'required|min:3|max:255|unique:specialites,specialite_title',
            'content' => 'required',
            'specialite_image' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096',
        ], [
            'specialite_title.required' => 'Veuillez entrez le titre de la specialite',
            'specialite_title.unique' => 'Ce titre de specialite existe déja',
            'specialite_title.min' => 'Le titre doit contenir minimum 3 caractères',
            'specialite_title.max' => 'Le titre contenir maximum 255 caractères',
            'content.required' => 'Veuillez remplir la description',
            'specialite_image.required' => 'Veuillez selectionner une image de mise en avant',
            'specialite_image.mimes' => 'L\'image doit être du type (jpg, jpeg, png, gif)'
        ]);

        if($request->hasFile('specialite_image'))
            $imagePath = $request->file('specialite_image')->store('specialites', 'public');
        else
            $imagePath = '';

        $specialite = Specialite::create([
            'specialite_title' => $request->specialite_title,
            'contenu' => $request->content,
            'specialite_image' => $imagePath,
        ]);

        if($specialite) {
            return response()->json(['success' => 'Specialite ajouté avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de la création de la specialite']);
        }
    }

    /**
     * for editing specialite
     */
    public function edit($id) {
        $specialiteToEdit = Specialite::findOrFail($id);
        return response()->json([
            'specialiteToEdit' => $specialiteToEdit,
            'content' => $specialiteToEdit->contenu
        ]);
    }

    /**
     * update specialite
     */
    public function update(Request $request, $id) {
        $request->validate([
            'specialite_title' => 'required|min:3|max:255',Rule::unique('specialites')->ignore($id),
            'content' => 'required',
            'specialite_image' => 'image|mimes:jpg,jpeg,png,gif|max:4096',
        ], [
            'specialite_title.required' => 'Veuillez entrez un nom de specialite',
            'specialite_title.min' => 'Le titre doit contenir au minimum 3 caractères',
            'specialite_title.max' => 'Le titre doit contenir au maximum 255 caractères',
            'specialite_title.unique' => 'Ce titre existe déja',
            'content.required' => 'Veuillez remplir la description',
            'specialite_image.mimes' => 'L\'image doit être du type (jpg, jpeg, png, gif)',
        ]);
        $specialite = Specialite::findOrFail($id);
        // update image if it's define
        if($request->hasFile('specialite_image')) {
            $imagePath = $request->file('specialite_image')->store('specialites', 'public');
            if ($specialite->specialite_image) {
                Storage::disk('public')->delete($specialite->specialite_image);
            }
            $specialite->specialite_image = $imagePath;
        }
        // finally update specialite
        $specialite->update([
            'specialite_title' => $request->specialite_title,
            'contenu' => $request->content
        ]);
        return response()->json(['success' => 'Informations de la specialite mises à jour avec succès']);
    }

    /**
     * delete a specialite
     */
    public function destroy($id) {
        $specialite = Specialite::findOrFail($id);
        $specialite->delete();
        return redirect()->route('specialite.index')->with('deleteSuccess', 'Specialite supprimé avec succès !');
    }

}
