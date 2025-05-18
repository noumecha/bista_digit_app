<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\AppConfiguration;
use App\Models\CategorieActualite;
use App\Models\Club;
use App\Models\Epreuve;
use App\Models\Slider;
use App\Models\Specialite;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * index function
     */
    public function index () {
        $appconfiguration = AppConfiguration::all()->last();
        $students = User::all()->where('typeUser','=','eleve')->count();
        $teachers = User::all()->where('typeUser','=','enseignant')->count();
        $sliders = Slider::query()->latest()->paginate(5);
        $actualites = Actualite::query()->latest()->paginate(3);
        $categories = CategorieActualite::all();
        $booster = Specialite::all()->where("type","booster-page")->last();
        $atouts = Specialite::all()->where("type", '!=',"booster-page")->where("type","!=", "leader-page");
        $leader = Specialite::all()->where("type","leader-page")->last();
        $club = Club::all()->last();
        return view('front.home', compact(
            'students','actualites','teachers','categories','sliders','appconfiguration',
            'booster', 'leader','atouts','club'
        ));
    }

    /**
     * index function
     */
    public function programmes () {
        $programmes = Specialite::query()->where("type","booster-page")->orWhere("type","leader-page")
        ->distinct()->paginate(10);
        $categories = CategorieActualite::query()->where("libelleCategorie","challenges")
            ->orWhere("libelleCategorie","Bourses")
            ->orWhere("libelleCategorie","programmes")
            ->orWhere("libelleCategorie","innovations")
            ->orWhere("libelleCategorie","résultats")->pluck('id');
        $actualites = Actualite::all()->whereIn('categorie_actualites_id', $categories);
        return view('front.programmes', compact('programmes', 'actualites'));
    }

    /**
     * booster page
     */
    public function boosterPage() {
        $booster = Specialite::where("type","booster-page")->first();
        $actualites = Actualite::query()->latest()->paginate(6);
        return view('front.boosterpage', compact('actualites','booster'));
    }

    /**
     * i am leader page
     */
    public function leaderPage() {
        $leader = Specialite::where("type","leader-page")->first();
        $categories = CategorieActualite::query()->where("libelleCategorie","challenges")
            ->orWhere("libelleCategorie","Bourses")
            ->orWhere("libelleCategorie","programmes")
            ->orWhere("libelleCategorie","innovations")
            ->orWhere("libelleCategorie","résultats")->pluck('id');
        $actualites = Actualite::query()->whereIn('categorie_actualites_id', $categories)->paginate(6);
        return view('front.leaderpage', compact('actualites','leader'));
    }

    /**
     * club page
     */
    public function clubs () {
        $clubs = Club::query()->latest()->paginate(3);
        $allClubs = Club::all();
        $actualites = Actualite::query()->where('club_id', '!=', null)->latest()->paginate(6);
        return view('front.clubs', compact('clubs', 'allClubs', 'actualites'));
    }

    /**
     * single club page
     */
    public function clubPage($id) {
        $currentClub = Club::findOrFail($id);
        $clubs = Club::query()->where("id","!=",$currentClub->id)->latest()->paginate(3);
        return view('front.clubpage', compact('currentClub','clubs'));
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
     * contact page
     */
    public function contact () {
        $appconfiguration = AppConfiguration::all()->first();
        return view('front.contact',compact('appconfiguration'));
    }

    /**
     * atout page
     */
    public function specialitePage($id) {
        $specialite = Specialite::findOrFail($id);
        $specialites = Specialite::query()->where("type", '!=',"booster-page")
            ->where("type","!=", "leader-page")
            ->where("id","!=",$specialite->id)
            ->latest()->paginate(3);
        $actualites = Actualite::query()->latest()->paginate(3);
        return view('front.specialitepage', compact('specialite','specialites','actualites'));
    }

    /**
     * showing single actualités on the site
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
