<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Devoir;
use App\Models\DevoirAnneeScolaire;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DevoirController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        // utils vars
        $teacher = User::find(Auth::id());
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $teacher->typeUser === "enseignant" ? $matieres = $teacher->matieres : $matieres = Matiere::all();
        $teacher->typeUser === "enseignant" ? $classes = $teacher->teacherClasses($activeYear) : $classes = Classe::all();
        // query vars :
        $migrateYears = AnneeScolaire::all()->where('created_at', '>', $activeYear->created_at);
        $devoirSchoolYears = DevoirAnneeScolaire::all()->where('annee_scolaire_id','=', $activeYear->id);
        $devoirSchoolYearsIds = $devoirSchoolYears->pluck('devoir_id');
        // filter vars :
        $searchDevoir = $request->input('searchDevoir');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        // querying :
        if(isset($devoirSchoolYears)) {
            $query = Devoir::query()->whereIn('id', $devoirSchoolYearsIds);
        }
        // filtering :
        if(!empty($searchDevoir)) {
            $query->where('titre_devoir', 'LIKE', "%{$searchDevoir}%");
        }
        if(!empty($classeFilter)) {
            $query->whereHas('classe', function ($q) use ($classeFilter) {
                $q->where('id', $classeFilter);
            });
        }
        if(!empty($matiereFilter)) {
            $query->whereHas('matiere', function ($q) use ($matiereFilter) {
                $q->where('id', $matiereFilter);
            });
        }

        //dd($query);
        $devoirs = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._devoirs_table', compact('matieres','classes','devoirs','teacher','activeYear','migrateYears'));
        } else {
            return view('education.devoirs', compact('matieres','classes','devoirs','teacher','activeYear','migrateYears'));
        }

    }

    /**
     *
     */
    public function store() {

    }
    /**
     *
     */
    public function update() {

    }
    /**
     *
     */
    public function destroy() {

    }
    /**
     *
     */
    public function migrate() {

    }
    /**
     *
     */
    public function deleteInCurrentYear() {

    }
}
