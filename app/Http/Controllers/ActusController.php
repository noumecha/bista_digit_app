<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\CategorieActualite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ActusController extends Controller
{
    /**
     * index function
     */
    public function index (Request $request) {
        $actualites = Actualite::all();
        $categories = CategorieActualite::all();
        $search = $request->input('search');
        $categoryFilter = $request->input('category');

        $query = Actualite::query();
        if(!empty($search) && !empty($categoryFilter)) {
            $query->where('titre', 'LIKE', "%{$search}%")
                ->orWhere('contenu', 'LIKE', "%{$search}%")
                ->orWhere('categorie_actualites_id', $categoryFilter);
        } elseif(!empty($categoryFilter)) {
            $query->where('categorie_actualites_id', $categoryFilter);
        } elseif (!empty($search)) {
            $query->where('titre', 'LIKE', "%{$search}%")
                ->orWhere('contenu', 'LIKE', "%{$search}%");
        }

        $actualites = $query->paginate(10);

        return view('actualites.index', compact('actualites', 'categories', 'search', 'categoryFilter'));
    }

    /**
     *
     */
    public function store(Request $request) {
        $request->validate([
            'titre' => 'required|min:3|max:255|unique:actualites,titre',
            'contenu' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096',
            'categorie_actualites_id' => 'required'
        ], [
            'titre.required' => 'Veuillez entrez un titre',
            'titre.unique' => 'Ce titre existe déja',
            'contenu.required' => 'Veuillez remplire le contenu de l\'actualité',
            'image.required' => 'Veuillez selectionner une image de mise en avant',
            'categorie_actualites_id.required' => 'Veuillez selectionner selectionner la catégorie',
        ]);

        Actualite::create([
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'user_id' => Auth::id(),
            'categorie_actualites_id' => $request->categorie_actualites_id,
            'image' => $request->hasFile('image') ? $request->file('image')->store('actualites', 'public') : '',
        ]);

        return redirect()->route('actualites.index')->with('success', 'Actualites ajouté avec succès');
    }


    /**
     *
     */
    public function edit(Request $request, $id) {
        $actualites = Actualite::all();
        $categories = CategorieActualite::all();
        $actualiteToEdit = Actualite::findOrFail($id);
        $search = $request->input('search');
        $categoryFilter = $request->input('category');

        $query = Actualite::query();
        if(!empty($search) && !empty($categoryFilter)) {
            $query->where('titre', 'LIKE', "%{$search}%")
                ->orWhere('contenu', 'LIKE', "%{$search}%")
                ->orWhere('categorie_actualites_id', $categoryFilter);
        } elseif(!empty($categoryFilter)) {
            $query->where('categorie_actualites_id', $categoryFilter);
        } elseif (!empty($search)) {
            $query->where('titre', 'LIKE', "%{$search}%")
                ->orWhere('contenu', 'LIKE', "%{$search}%");
        }

        $actualites = $query->paginate(10);

        return view('actualites.index', compact('actualites','actualiteToEdit','categories'));
    }

    /**
     *
     */
    public function update(Request $request, $id) {
        $request->validate([
            'titre' => 'required|min:3|max:255',
            'contenu' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:4096',
            'categorie_actualites_id' => 'required'
        ], [
            'titre.required' => 'Veuillez entrez un titre',
            'titre.unique' => 'Ce titre existe déja',
            'contenu.required' => 'Veuillez remplire le contenu de l\'actualité',
            'categorie_actualites_id.required' => 'Veuillez selectionner selectionner la catégorie',
        ]);
        $actualite = Actualite::findOrFail($id);

        if($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('actualites', 'public');
            if ($actualite->image) {
                Storage::disk('public')->delete($actualite->image);
            }
            $actualite->image = $imagePath;
        }
        //$actualite->user_id = User::find(Auth::id());
        $actualite->update($request->except('image'));

        return redirect()->route('actualites.index')->with('success', 'Actualité mise à jour avec succès');
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
