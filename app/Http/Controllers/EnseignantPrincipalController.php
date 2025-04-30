<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\EnseignantMatiereModel;
use App\Models\EnseignantPrincipal;
use App\Models\Enseignement;
use App\Models\EnseignementAnneeScolaire;
use App\Models\EnsPrincAnneeScolaire;
use App\Models\User;
use App\Models\UserAnneeScolaire;
use Illuminate\Http\Request;

class EnseignantPrincipalController extends Controller
{
     /**
     *
     */
    public function index(Request $request) {
        // default vars
        $classes = Classe::all();
        $teachers = User::all()->where('typeUser', 'enseignant');
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $migrateYears = AnneeScolaire::all()->where('created_at', '>', $activeYear->created_at);
        // search or filter options :
        $searchTeacher = $request->input('searchTeacher');
        $classeFilter = $request->input('classeFilter');
        // for query :
        $enseignantPrincYear = EnsPrincAnneeScolaire::all()->where('annee_scolaire_id','=', $activeYear->id);
        $enseignantPrincIds = $enseignantPrincYear->pluck('enseignant_principal_id');
        //$enseignantPrincs = EnseignantPrincipal::all()->whereIn('id',$enseignantPrincIds);

        // querying
        $query = EnseignantPrincipal::query()->whereIn('id', $enseignantPrincIds);

        // filtering
        if(!empty($searchTeacher)) {
            $query->whereHas('user_id', function ($teacherQuery) use ($searchTeacher) {
                $teacherQuery->where('name', 'LIKE', "%{$searchTeacher}%")
                             ->orWhere('surname', 'LIKE', "%{$searchTeacher}%");
            });
        }
        if(!empty($classeFilter)) {
            $query->where('classe_id',$classeFilter);
        }

        //dd($query);
        $enseignantPrincipals = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._enseignants_principal_table', compact('activeYear','classes','migrateYears','enseignantPrincipals','teachers'));
        } else {
            return view('education.enseignantprincipals', compact('activeYear','classes','migrateYears','enseignantPrincipals','teachers'));
        }
    }

    /**
     * define principal teacher for a class in a year
     */
    public function store(Request $request) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'user_id' => 'required|exists:users,id',
            'active_year_id' => 'required',
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'user_id.required' => 'Selectionnez l\'enseignant',
            'active_year_id.required' => 'Aucune annéee selectionnée',
        ]);

        // check if a teacher is already a princ teacher of a class
        $exists = EnseignantPrincipal::where('user_id',$request->user_id)
            ->where('annee_scolaire_id',$request->active_year_id)
            ->exists();
        if($exists) {
            return response()->json([
                'error' => 'L\'enseignant est déjà l\'enseignant principale d\'une autre classe pour cette année',
            ]);
        }
        // check if the configuration alreary exists
        $exists = EnseignantPrincipal::where('classe_id',$request->classe_id)
            ->where('user_id',$request->user_id)
            ->where('annee_scolaire_id',$request->active_year_id)
            ->exists();
        if($exists) {
            return response()->json([
                'error' => 'L\'enseignant est déjà l\'enseignant principale de cette classe pour cette année',
            ]);
        }
        // also check if the class already have a principal teacher for the year
        $exists = EnseignantPrincipal::where('classe_id', $request->classe_id)
            ->where('annee_scolaire_id', getCurrentYear()->id)->exists();
        if($exists) {
            return response()->json([
                'error' => 'un enseignant principal pour cette classe est déjà défini !',
            ]);
        }

        $ensPrincipal = EnseignantPrincipal::create([
            'classe_id' => $request->classe_id,
            'user_id' => $request->user_id,
            'annee_scolaire_id' => $request->active_year_id
        ]);

        $ensPrincAnneeScolaire = EnsPrincAnneeScolaire::create([
            'enseignant_principal_id' => $ensPrincipal->id,
            'annee_scolaire_id' => $request->active_year_id
        ]);

        if($ensPrincipal && $ensPrincAnneeScolaire) {
            return response()->json(['success' => 'Enseignant principal définir avec succès']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de la définition de l\'enseignant principal']);
        }
    }

    /**
     * get specific ensPrincipal id for editing
     */
    public function edit($id) {
        $ensPrincToEdit = EnseignantPrincipal::findOrFail($id);
        return response()->json(['ensPrincToEdit' => $ensPrincToEdit]);
    }

    /**
     * update specific ensPrincipal
     */
    public function update(Request $request, $id) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'user_id' => 'required|exists:users,id',
            'active_year_id' => 'required',
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'user_id.required' => 'Selectionnez l\'enseignant',
            'active_year_id.required' => 'Aucune annéee selectionnée',
        ]);

        $exists = EnseignantPrincipal::where('classe_id',$request->classe_id)
            ->where('user_id',$request->user_id)
            ->where('create_year_id','=',$request->user_id)
            ->exists();
        if($exists)
            return response()->json(['error' => 'Un enseignant est déjà enseignant principal dans cette classe !']);

        $ensPrincipal = EnseignantPrincipal::findOrFail($id);
        $ensPrincipal->update([
            'classe_id' => $request->classe_id,
            'user_id' => $request->user_id,
        ]);

        return response()->json(['success', 'Enseignant principal modifier avec succès']);
    }

    /**
     * drop ensPrincipal from db
     */
    public function destroy($id) {
        $ensPrincipal = EnseignantPrincipal::findOrFail($id);
        $ensPrincAnneeScolaire = EnsPrincAnneeScolaire::where('enseignant_principal_id',$id);
        $ensPrincipal->delete();
        $ensPrincAnneeScolaire->delete();

        return redirect()->route('education.enseignantprincipal')->with('deleteSuccess', 'Enseignant principal supprimé de la classe avec succès!');
    }

     /**
     *  delete ensPrincipal for the current year
     */
    public function deleteEnsPrincCurrentYear(Request $request) {
        $enseignantPrincYear = EnsPrincAnneeScolaire::all()
            ->where('annee_scolaire_id', '=', $request->delusyear_year_id)
            ->where('enseignant_principal_id', '=', $request->delusyear_ensprinc_id)->first();

        // delete the configuration in the current year
        if ($enseignantPrincYear->delete()) {
            return redirect()->route('education.enseignantprincipal')->with('deleteSuccess', 'Configuration supprimé avec succès pour l\'année courrante');
        } else {
            return redirect()->route('education.enseignantprincipal')->with('errorSuccess', 'Echec de surpression de la configuration pour l\'année courrante');
        }
    }

    /**
     * Migrate ensPrincipal for a forward year to achieve application evolution
     */
    public function migrate(Request $request) {
        $request->validate([
            'migrate_year_id' => 'required|exists:annee_scolaires,id',
            'migrate_ensprinc_id' => 'required|exists:enseignant_principals,id',
            'migrate_current_year_id' => 'required|exists:annee_scolaires,id'
        ], [
            'migrate_year_id.required' => 'Aucune année selectionnée',
            'migrate_ensprinc_id.required' => 'Veuillez selectionnez une configuration',
            'migrate_current_year_id.required' => 'Veuillez activer une année scolaire',
        ]);
        // before migrate make sure that user that correspond to this ensPrincipal already migrate
        $ensPrincipal = EnseignantPrincipal::all()->where('id', $request->migrate_ensprinc_id)->first();
        // checking if the users exist in the migrate year :
        $teacher = UserAnneeScolaire::all()->where('user_id', $ensPrincipal->user_id)
            ->where('annee_scolaire_id',$request->migrate_year_id)->first();

        // checking if the ensPrincipal already migrated:
        $ensPrincYear = EnsPrincAnneeScolaire::all()
            ->where('annee_scolaire_id',$request->migrate_year_id)
            ->where('enseignant_principal_id',$request->migrate_ensprinc_id)
            ->first();

        if(!$teacher) {
            return response()->json(['error' => 'L\'enseignant n\'existe pas dans l\'année selectionnée']);
        } else if($ensPrincYear) {
            return response()->json(['error' => 'Cette configuration existe déjà pour l\'année selectionnée']);
        } else {
            EnsPrincAnneeScolaire::create([
                'enseignant_principal_id' => $request->migrate_ensprinc_id,
                'annee_scolaire_id'=>$request->migrate_year_id,
            ]);
            return response()->json(['success' => 'Configuration migré avec succès']);
        }
    }

     /**
     * get teachers base on a specific class id
     */
    public function getTeachers($classeId) {
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $ensYearIds = EnseignementAnneeScolaire::all()
            ->where('annee_scolaire_id',$activeYear->id)->pluck('enseignement_id');
        $ensMatIds = Enseignement::all()->where('classe_id', $classeId)
            ->whereIn('id',$ensYearIds)->pluck('enseignant_matiere_id');
        $teachersIds = EnseignantMatiereModel::all()->whereIn('id',$ensMatIds)->pluck('user_id');
        $teachers = User::where('typeUser','enseignant')->whereIn('id', $teachersIds)->get();
        return response()->json($teachers);
    }
}
