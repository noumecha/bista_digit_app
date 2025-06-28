<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\AppConfiguration;
use App\Models\Bulletin;
use App\Models\CategorieActualite;
use App\Models\Classe;
use App\Models\Club;
use App\Models\Epreuve;
use App\Models\Matiere;
use App\Models\PublishedStatistic;
use App\Models\Slider;
use App\Models\Specialite;
use App\Models\Trimestre;
use App\Models\TypeEpreuve;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * home page
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
     * all programmes page
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
     * all epreuves pages
     */
    public function epreuves (Request $request) {
        // vars :
        $classes = Classe::all();
        $matieres = Matiere::all();
        $types = TypeEpreuve::all();
        $anneeEpreuves = Epreuve::select('anneeEpreuve')->distinct()->get();
        // search vars
        $search = $request->input('search');
        $classeId = $request->input('classeId');
        $matiereId = $request->input('matiereId');
        $typeId = $request->input('typeId');
        $anneeEpreuve = $request->input('anneeEpreuve');
        // create query
        $query = Epreuve::query();
        if($search) {
            $query->where('libelleEpreuve', 'LIKE', "%{$search}%");
        }
        if($classeId) {
            $query->where('classe_id', $classeId);
        }
        if($matiereId) {
            $query->where('matiere_id', $matiereId);
        }
        if($typeId) {
            $query->where('type_epreuve_id', $typeId);
        }
        if($anneeEpreuve) {
            $query->where('anneeEpreuve', $anneeEpreuve);
        }
        $epreuves = $query->latest()->paginate(9);
        foreach ($epreuves as $epreuve) {
            $epreuve->isImage = preg_match('/\.(jpg|jpeg|png|gif)$/i', $epreuve->fichier);
        }
        if($request->ajax()) {
            return view('partials._epreuves_datas', compact(
                'epreuves'
            ));
        } else {
            return view('front.epreuves', compact(
                'epreuves','classes','matieres','types','anneeEpreuves'
            ));
        }
    }

    /**
     * epreuve single page
     */
    public function showEpreuve($id) {
        $epreuve = Epreuve::findOrFail($id);
        $epreuve->isImage = preg_match('/\.(jpg|jpeg|png|gif)$/i', $epreuve->fichier);
        return view('front.showepreuve')->with('epreuve', $epreuve);
    }

    /**
     * actus on front
     */
    public function actualites (Request $request) {
        $categories = CategorieActualite::all();
        $search = $request->input('search');
        $categoryFilter = $request->input('category');
        $actualitesSliders = Actualite::query()->latest()->paginate(5);
        $query = Actualite::query();
        if($search) {
            $query->where('titre', 'LIKE', "%{$search}%")->orWhere('contenu', 'LIKE', "%{$search}%");
        }
        if($categoryFilter) {
            $query->where('categorie_actualites_id', $categoryFilter);
        }
        $actualites = $query->latest()->paginate(9);
        if($request->ajax()) {
            return view('partials._actus_datas', compact(
                'actualites',
            ));
        } else {
            return view('front.actus', compact(
                'actualites', 'categories', 'search', 'categoryFilter','actualitesSliders'
            ));
        }
    }

    /**
     * stats datas on front
     */
    public function stats(Request $request) {
        // base datas
        $trimestres = Trimestre::all();
        $classes = Classe::all();
        // For OBC stats
        $yearFilter = $request->input('annee_scolaire_id');
        $obcStats = PublishedStatistic::where('type', 'obc')
            ->orderBy('annee_scolaire_id', 'desc')
            ->get();
        $selectedOBC = $yearFilter
            ? PublishedStatistic::where('type', 'obc')
                ->where('annee_scolaire_id', $yearFilter)
                ->first()
            : $obcStats->first();
        // for trimestrials stats
        $query = PublishedStatistic::query()->where('annee_scolaire_id', getCurrentYear()->id)
            ->where('type', 'trimestriel');
        // filters
        $trimestreFilter = $request->input('trimestre_id');
        $classFilter = $request->input('classe_id');
        if(!empty($trimestreFilter)) {
            $query->where('trimestre_id', $trimestreFilter);
        }
        if(!empty($classFilter)) {
            $query->where('classe_id', $classFilter);
        }
        $stats = $query->orderBy('created_at', 'desc')->paginate(10);
        // return
        if($request->ajax()) {
            if($request->has('obc')) {
                /*return response()->json([
                    'obc_stats' => view('statistics.partials.obc-stats-datas', compact('selectedOBC'))
                ]);*/
                return view('statistics.partials.obc-stats-datas', compact('selectedOBC'));
            } else {
                /*return response()->json([
                    'trim_stats' => view('statistics.partials.stats-datas', compact('stats'))
                ]);*/
                return view('statistics.partials.stats-datas', compact('stats'));
            }
        } else {
            return view('statistics.show', compact(
                'stats','trimestres','classes','obcStats','selectedOBC'
            ));
        }
    }

    /**
     * Show detailed results for a published statistic
     */
    public function showDetails($id)
    {
        $publishedStat = PublishedStatistic::findOrFail($id);
        // For trimestrial stats
        $bulletins = Bulletin::where('trimestre_id', $publishedStat->trimestre_id)
            ->where('classe_id', $publishedStat->classe_id)
            ->where('type_bulletin', 'trimestre')
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->orderBy('average', 'desc')->get();

        return view('statistics.details', [
            'publishedStat' => $publishedStat,
            'bulletins' => $bulletins,
            'trimestre' => $publishedStat->trimestre,
            'classe' => $publishedStat->classe
        ]);
    }

    /**
     * contact page
     */
    public function contact () {
        $appconfiguration = AppConfiguration::all()->first();
        $actualites = Actualite::query()->latest()->paginate(3);
        return view('front.contact',compact('appconfiguration', 'actualites'));
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
        $actualites = Actualite::query()->latest()->paginate(3);
        return view('actualites.show', compact('actualite', 'actualites'));
    }

    /**
     *
     */
    public function showCategorie(CategorieActualite $category, Request $request) {
        $actualites = Actualite::where('categorie_actualites_id','=',$category->id)->paginate(10);

        return view('front.showCategorie', compact('actualites','category'));
    }
}
