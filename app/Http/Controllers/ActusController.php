<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActusController extends Controller
{
    /**
     * index function
     */
    public function index () {
        $actualites = Actualite::all();
        return view('actualites.index', compact('actualites'));
    }

    /**
     *
     */
    public function store(Request $request) {
        $request->validate([
            'titre' => 'required|min:3|max:255|unique:actualites,titre',
            'contenu' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096',
        ], [
            'titre.required' => 'Veuillez entrez un titre',
            'contenu.required' => 'Veuillez remplire le contenu de l\'actualité',
            'image.required' => 'Veuillez selectionner une image de mise en avant',
        ]);

        Actualite::create([
            'name' => $request->name,
            'matricule' => $request->contenu,
            'image' => $request->hasFile('image') ? $request->file('images')->store('actualites', 'public') : '',
        ]);

        return redirect()->route('actualites.index')->with('success', 'Actualites ajouté avec succès');
    }


    /**
     *
     */
    public function edit($id) {
        $actualites = Actualite::all();
        $actualiteToEdit = Actualite::findOrFail($id);

        return view('actualites.index', compact('actualites','actualiteToEdit'));
    }

    /**
     *
     */
    public function update(Request $request, $id) {
        $request->validate([
            'titre' => 'required|min:3|max:255',
            'contenu' => 'required',
            'image' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
            'titre.required' => 'Veuillez entrez un titre',
            'contenu' => 'Veuillez remplire le contenu de l\'actualité',
            'image' => 'Veuillez selectionner une image de mise en avant',
        ]);
        $actualite = Actualite::findOrFail($id);

        if($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('actualites', 'public');
            if ($actualite->image) {
                Storage::disk('public')->delete($actualite->image);
            }
            $actualite->image = $imagePath;
        }
        $actualite->update($request->except('image'));

        return redirect()->route('actualites.index')->with('success', 'Actualites mis à jour avec succès');
    }

    /**
     *
     */
    public function destroy($id) {
        $actualite = Actualite::findOrFail($id);
        $actualite->delete();

        return redirect()->route('actualites.index')->with('deleteSuccess', 'Actualité supprimer avec succès');
    }
}
