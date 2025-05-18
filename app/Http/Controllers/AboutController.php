<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\AppConfiguration;
use App\Models\CategorieActualite;
use App\Models\Specialite;
use App\Models\User;

class AboutController extends Controller
{
    /**
     * index function
     */
    public function index () {
        $appconfiguration = AppConfiguration::all()->first();
        $students = User::all()->where('typeUser','=','eleve')->count();
        $teachers = User::all()->where('typeUser','=','enseignant')->count();
        $actualites = Actualite::query()->latest()->paginate(3);
        $categories = CategorieActualite::all();
        $atouts = Specialite::all()->where("type", '!=',"booster-page")->where("type","!=", "leader-page");
        return view('front.about',compact(
            'appconfiguration','atouts','actualites','teachers','students'
        ));
    }
}
