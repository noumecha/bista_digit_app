<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\CategorieActualite;
use App\Models\Epreuve;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * index function
     */
    public function index () {
        $students = User::all()->where('typeUser','=','eleve')->count();
        $teachers = User::all()->where('typeUser','=','enseignant')->count();
        $categories = CategorieActualite::all();
        return view('front.home', compact('students', 'teachers','categories'));
    }

    /**
     * index function
     */
    public function programmes () {
        return view('front.programmes');
    }

    /**
     * index function
     */
    public function clubs () {
        return view('front.clubs');
    }

    /**
     * index function
     */
    public function epreuves () {
        $epreuves = Epreuve::all();
        foreach ($epreuves as $epreuve) {
            $epreuve->isImage = preg_match('/\.(jpg|jpeg|png|gif)$/i', $epreuve->fichier);
        }
        //dd($epreuves);
        return view('front.epreuves', compact('epreuves'));
    }

    /**
     * index function
     */
    public function showEpreuve($id) {
        $epreuve = Epreuve::findOrFail($id);
        $epreuve->isImage = preg_match('/\.(jpg|jpeg|png|gif)$/i', $epreuve->fichier);
        return view('front.showepreuve')->with('epreuve', $epreuve);
    }

    /**
     * index function
     */
    public function actualites (Request $request) {
        $actualites = Actualite::all();
        $categories = CategorieActualite::all();
        $search = $request->input('search');
        $categoryFilter = $request->input('category');

        $query = Actualite::query();
        if($search) {
            $query->where('titre', 'LIKE', "%{$search}%")->orWhere('contenu', 'LIKE', "%{$search}%");
        }
        if($categoryFilter) {
            $query->where('categorie_actualites_id', $categoryFilter);
        }

        $actualites = $query->paginate(9);
        return view('front.actus', compact('actualites', 'categories', 'search', 'categoryFilter'));
    }

    /**
     *
     */
    public function showActualite($id) {
        $actualite = Actualite::findOrFail($id);
        return view('actualites.show')->with('actualite', $actualite);
    }

    /**
     *
     */
    public function showCategorie(CategorieActualite $category, Request $request) {
        $actualites = Actualite::where('categorie_actualites_id','=',$category->id)->paginate(10);

        return view('front.showCategorie', compact('actualites','category'));
    }
}
