<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\EnsMatAnneeScolaire;
use App\Models\EnseignantMatiereModel;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;

class EnseignantMatiereModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $matieres = Matiere::all();
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $enseignants = User::all()->where('typeUser','=','enseignant');
        $searchTeacherSubjects = $request->input('searchTeacherSubjects');
        $matiereFilter = $request->input('matiereFilter');
        $enseignantsMatieresAnneeScolaire = EnsMatAnneeScolaire::all()->where('annee_scolaire_id','=', $activeYear->id);
        $enseignantsMatieresAnneeScolaireIds = $enseignantsMatieresAnneeScolaire->pluck('enseignant_matiere_models_id');

        // Querying
        if(isset($enseignantsMatieresAnneeScolaire)) {
            $query = EnseignantMatiereModel::whereIn('id', $enseignantsMatieresAnneeScolaireIds);
        } else {
            $query = "";
        }

        // filtering
        if(!empty($searchTeacherSubjects)) {
            $query->where(function ($q) use ($searchTeacherSubjects) {
                $q->whereHas('enseignant', function ($teacherQuery) use ($searchTeacherSubjects) {
                    $teacherQuery->where('name', 'LIKE', "%{$searchTeacherSubjects}%")
                                 ->orWhere('surname', 'LIKE', "%{$searchTeacherSubjects}%");
                });
            });
        }
        if(!empty($matiereFilter)) {
            $query->where('matiere_id',$matiereFilter);
        }
        if (!empty($searchTeacherSubjects)) {
            $query->where(function ($q) use ($searchTeacherSubjects) {
                $q->whereHas('enseignant', function ($teacherQuery) use ($searchTeacherSubjects) {
                    $teacherQuery->where('name', 'LIKE', "%{$searchTeacherSubjects}%")
                                 ->orWhere('surname', 'LIKE', "%{$searchTeacherSubjects}%");
                });
            });
        }

        //dd($query);
        $query ?  $enseignantsMatieres = $query->paginate(10) : $enseignantsMatieres = [];

        if($request->ajax()) {
            return view('partials._teachers_subjects_table', compact('activeYear','matieres', 'enseignants', 'enseignantsMatieres'));
        } else {
            return view('education.enseignantMatiere', compact('activeYear','matieres', 'enseignants', 'enseignantsMatieres'));
        }
    }

    /**
     * Store a newly created enseignantMatiereModel in DB
     */
    public function store(Request $request)
    {
        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'user_id' => 'required|exists:users,id',
            'active_year_id' => 'required',
        ], [
            'matiere_id.required' => 'Selectionnez la classe',
            'user_id.required' => 'Selectionnez l\'enseignant',
            'active_year_id.required' => 'Aucune annéee selectionnée',
        ]);

        $exists = EnseignantMatiereModel::where('matiere_id', '=', $request->matiere_id)->where('user_id','=',$request->user_id)->exists();

        if($exists) {
            return response()->json(['error' => 'Cette matière est déjà attribuer à cet enseignant']);
        }

        $enseignantMatiereModel = EnseignantMatiereModel::create([
            'user_id' => $request->user_id,
            'matiere_id' => $request->matiere_id
        ]);

        // relation between enseignantMatiere && school year
        $enseignatMatiereSchoolYear = EnsMatAnneeScolaire::create([
            'enseignant_matiere_models_id' => $enseignantMatiereModel->id,
            'annee_scolaire_id' => $request->active_year_id,
        ]);

        // the case when the user is migrate and we need to add a new subject to him,
        // in that case we need to create the relation EnsMatAnneeScolaire for all the
        // years where the teacher was migrated
        $enseignantMatieres = EnseignantMatiereModel::all()->where('user_id', $request->user_id)->pluck('id');
        $ensMatSchoolYears = EnsMatAnneeScolaire::all()->where('annee_scolaire_id','<>',$request->active_year_id)->whereIn('enseignant_matiere_models_id', $enseignantMatieres);
        foreach ($ensMatSchoolYears as $ensMatSchoolYear) {
            $existEnsMatAnneeScolaire = EnsMatAnneeScolaire::where('enseignant_matiere_models_id','=',$enseignantMatiereModel->id)->where('annee_scolaire_id','=',$ensMatSchoolYear->annee_scolaire_id)->exists();
            //dd(!$existEnsMatAnneeScolaire);
            if(!$existEnsMatAnneeScolaire) {
                EnsMatAnneeScolaire::create([
                    'enseignant_matiere_models_id' => $enseignantMatiereModel->id,
                    'annee_scolaire_id' => $ensMatSchoolYear->annee_scolaire_id,
                ]);
            }
        }
        //dd();

        if($enseignantMatiereModel && $enseignatMatiereSchoolYear) {
            return response()->json(['success' => 'Matiere attribuer à l\'enseignant avec succès']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'attribution de la matière']);
        }
    }

    /**
     * Show the form for editing the specified enseignantMatiereModel.
     */
    public function edit($id)
    {
        $enseignantMatiereToEdit = EnseignantMatiereModel::findOrFail($id);
        return response()->json(['enseignantMatiere' => $enseignantMatiereToEdit]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'user_id' => 'required|exists:users,id',
        ], [
            'matiere_id.required' => 'Selectionnez la matiere',
            'user_id.required' => 'Selectionnez l\'enseignant',
        ]);

        $exists = EnseignantMatiereModel::where('matiere_id', '=', $request->matiere_id)->where('user_id','=',$request->user_id)->exists();

        if($exists) {
            return response()->json(['error' => 'Cet enseignant enseigne déja cette matière']);
        }

        $enseignantMatiere = EnseignantMatiereModel::findOrFail($id);
        $enseignantMatiere->update([
            'user_id' => $request->user_id,
            'matiere_id' => $request->matiere_id,
        ]);

        return response()->json(['success' => 'Attribution de matière mis à jour avec succès']);
    }

    /**
     * Remove the specified enseignantMatiereModel from storage.
     */
    public function destroy($id)
    {
        $enseignantMatiere = EnseignantMatiereModel::findOrFail($id);
        $EnsMatAnneeScolaires = EnsMatAnneeScolaire::where('enseignant_matiere_models_id', '=', $id);
        $EnsMatAnneeScolaires->delete();
        $enseignantMatiere->delete();

        return redirect()->route('education.enseignantMatiere')->with('deleteSuccess', 'Attributation de matière supprimée avec succès');
    }
}
