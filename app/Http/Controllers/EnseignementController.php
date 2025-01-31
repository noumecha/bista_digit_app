<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Enseignement;
use App\Models\AnneeScolaire;
use App\Models\EnseignantMatiereModel;
use App\Models\EnseignementAnneeScolaire;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;

class EnseignementController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        $classes = Classe::all();
        $enseignantsMatieres = EnseignantMatiereModel::all();
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $migrateYears = AnneeScolaire::all()->where('created_at', '>', $activeYear->created_at);
        $searchTeacher = $request->input('searchTeacher');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $enseignementsAnneeScolaire = EnseignementAnneeScolaire::all()->where('annee_scolaire_id','=', $activeYear->id);
        $enseignementsIds = $enseignementsAnneeScolaire->pluck('enseignement_id');
        $matieres = Matiere::all();
        $enseignants = User::all()->where('typeUser' , '=', 'enseignant');

        // querying
        if(isset($enseignementsAnneeScolaire)) {
            $query = Enseignement::whereIn('id', $enseignementsIds);
        } else {
            $query = "";
        }

        // filtering
        if(!empty($searchTeacher)) {
            $query->whereHas('enseignantmatiere.enseignant', function ($teacherQuery) use ($searchTeacher) {
                $teacherQuery->where('name', 'LIKE', "%{$searchTeacher}%")
                             ->orWhere('surname', 'LIKE', "%{$searchTeacher}%");
            });
        }
        if(!empty($matiereFilter)) {
            $query->whereHas('enseignantmatiere.matiere', function ($q) use ($matiereFilter) {
                $q->where('id', $matiereFilter);
            });
        }
        if(!empty($classeFilter)) {
            $query->where('classe_id',$classeFilter);
        }

        //dd($query);
        $query ?  $enseignements = $query->paginate(10) : $enseignements = [];

        if($request->ajax()) {
            return view('partials._enseignements_table', compact('activeYear','classes','matieres', 'enseignants', 'enseignements','migrateYears','enseignantsMatieres'));
        } else {
            return view('education.enseignement', compact('activeYear','matieres','classes','enseignants', 'enseignements','migrateYears','enseignantsMatieres'));
        }

        //dd($enseignements);
        return view('education.enseignement', compact('classes', 'enseignements', 'enseignantsMatieres', 'matieres','migrateYears', 'enseignants'));
    }

    public function store(Request $request) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'enseignant_matiere_id' => 'required|exists:enseignant_matiere_models,id',
            'active_year_id' => 'required',
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'enseignant_matiere_id.required' => 'Selectionnez l\'enseignant',
            'active_year_id.required' => 'Aucune annéee selectionnée',
        ]);

        $exists = Enseignement::where('classe_id', '=', $request->classe_id)->where('enseignant_matiere_id','=',$request->enseignant_matiere_id)->exists();

        if($exists) {
            return response()->json(['error'=>'Un enseignant enseigne déja cette matière dans cette classe']);
        }

        $enseignement = Enseignement::create([
            'classe_id' => $request->classe_id,
            'enseignant_matiere_id' => $request->enseignant_matiere_id,
            'create_year_id' => $request->active_year_id
        ]);

        $enseignementAnneeScolaire = EnseignementAnneeScolaire::create([
            'enseignement_id' => $enseignement->id,
            'annee_scolaire_id' => $request->active_year_id
        ]);

        if($enseignement && $enseignementAnneeScolaire) {
            return response()->json(['success' => 'Classe attribuée avec succès']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'attribution de la classe']);
        }
    }

    /**
     *
     */
    public function edit($id) {
        $enseignementToEdit = Enseignement::findOrFail($id);
        return response()->json(['enseignementToEdit' => $enseignementToEdit]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'enseignant_matiere_id' => 'required|exists:enseignant_matiere_models,id',
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'enseignant_matiere_id.required' => 'Selectionnez l\'enseignant',
        ]);

        $exists = Enseignement::where('classe_id', '=', $request->classe_id)->where('enseignant_matiere_id','=',$request->enseignant_matiere_id)->exists();
        if($exists)
            return response()->json(['error' => 'Un enseignant enseigne déjà cette matière dans cette classe']);

        $enseignement = Enseignement::findOrFail($id);
        $enseignement->update([
            'classe_id' => $request->classe_id,
            'enseignant_matiere_id' => $request->enseignant_matiere_id,
        ]);

        return response()->json(['success', 'Attributation de classe mise à jour avec succès']);
    }

    public function destroy($id) {
        $enseignement = Enseignement::findOrFail($id);
        $enseignementAnneeScolaire = EnseignementAnneeScolaire::where('enseignenemt_id','=',$id);
        $enseignement->delete();
        $enseignementAnneeScolaire->delete();

        return redirect()->route('education.enseignement')->with('deleteSuccess', 'Attributation de classe supprimée avec succès');
    }

     /**
     *  delete enseignement for the current year
     */
    public function deleteEnsCurrentYear(Request $request) {

        $enseignementYear = EnseignementAnneeScolaire::all()->where('annee_scolaire_id', '=', $request->delusyear_year_id)->where('enseignement_id', '=', $request->delusyear_ens_id)->first();
        // checking if the teacher exist in the migrate year before migrate his configuration
        dd();
        if ($enseignementYear->delete()) {
            return redirect()->route('education.enseignement')->with('deleteSuccess', 'Configuration supprimé avec succès pour l\'année courrante');
        } else {
            return redirect()->route('education.enseignement')->with('errorSuccess', 'Echec de surpression de la configuration pour l\'année courrante');
        }

    }

    /**
     * Migrate enseignement for a forward year to achieve application evolution
     */
    public function migrate(Request $request) {
        $request->validate([
            'migrate_year_id' => 'required|exists:annee_scolaires,id',
            'migrate_ens_id' => 'required|exists:enseignement,id',
            'migrate_current_year_id' => 'required|exists:annee_scolaires,id'
        ], [
            'migrate_year_id.required' => 'Aucune année selectionnée',
            'migrate_ens_id.required' => 'Veuillez selectionnez une configuration',
            'migrate_current_year_id.required' => 'Veuillez activer une année scolaire',
        ]);

        // checking if the enseignement already migrated:
        $ensYear = EnseignementAnneeScolaire::all()
            ->where('annee_scolaire_id','=',$request->migrate_year_id)
            ->where('enseignement_id', '=', $request->migrate_ens_id)
            ->first();

        if($ensYear) {
            return response()->json(['error' => 'Cette configuration existe déjà pour l\'année selectionnée']);
        } else {
            EnseignementAnneeScolaire::create([
                'enseignement_id' => $request->migrate_ens_id,
                'annee_scolaire_id'=>$request->migrate_year_id,
            ]);
            return response()->json(['success' => 'Configuration migré avec succès']);
        }
    }
}
