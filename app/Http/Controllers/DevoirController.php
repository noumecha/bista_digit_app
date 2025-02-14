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
use Illuminate\Validation\Rule;

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
        //dd($devoirSchoolYears);
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
            $query->where('titre_devoir', 'LIKE', "%{$searchDevoir}%")
            ->orWhere('description_devoir', 'LIKE', "%{$searchDevoir}%");
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
     * create new devoir
     */
    public function store(Request $request) {
        $request->validate([
            'titre_devoir' => 'required|min:3|max:255|unique:devoirs,titre_devoir',
            'content' => 'required',
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'active_year_id' => 'required|exists:annee_scolaires,id',
        ], [
            'titre_devoir.required' => 'Veuillez entrez un titre pour le devoir',
            'titre_devoir.unique' => 'Ce titre de devoir existe déja',
            'content.required' => 'Veuillez entrez la description du devoir',
            'classe_id.required' => 'Veuillez selectionnez la classe',
            'matiere_id.required' => 'Veuillez selectionnez la matière',
            'active_year_id.required' => 'Veuillez selectionnez une année scolaire',
        ]);

        $devoir = Devoir::create([
            'titre_devoir' => $request->titre_devoir,
            'description_devoir' => $request->content,
            'classe_id' => $request->classe_id,
            'matiere_id' => $request->matiere_id,
            'user_id' => Auth::id(),
            'annee_scolaire_id' => $request->active_year_id,
        ]);

        $devoirYear = DevoirAnneeScolaire::create([
            'devoir_id' => $devoir->id,
            'annee_scolaire_id' => $request->active_year_id
        ]);

        if($devoir && $devoirYear) {
            return response()->json(['success' => 'Devoir ajouté avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'ajout du devoir']);
        }
    }

    /**
     * editing specific devoir
     */
    public function edit($id) {
        $devoirToEdit = Devoir::findOrFail($id);
        return response()->json([
            'devoirToEdit' => $devoirToEdit,
            'content' => $devoirToEdit->description_devoir
        ]);
    }

    /**
     * update specific devoir
     */
    public function update(Request $request, $id) {
        $request->validate([
            'titre_devoir' => 'required|min:3|max:255',Rule::unique('devoirs')->ignore($id),
            'content' => 'required',
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'active_year_id' => 'required|exists:annee_scolaires,id',
        ], [
            'titre_devoir.required' => 'Veuillez entrez un titre pour le devoir',
            'titre_devoir.unique' => 'Ce titre de devoir existe déja',
            'titre_devoir.min' => 'Le titre doit contenir minimum 3 caractères',
            'titre_devoir.max' => 'Le titre doit contenir maximum 255 cractères',
            'content.required' => 'Veuillez entrez la description du devoir',
            'classe_id.required' => 'Veuillez selectionnez la classe',
            'matiere_id.required' => 'Veuillez selectionnez la matière',
            'active_year_id.required' => 'Veuillez selectionnez une année scolaire',
        ]);

        $devoir = Devoir::findOrFail($id);
        $devoir->update([
            'titre_devoir' => $request->titre_devoir,
            'description_devoir' => $request->content,
            'classe_id' => $request->classe_id,
            'matiere_id' => $request->matiere_id
        ]);

        return response()->json(['success' => 'Devoir mis à jour avec succès']);
    }

    /**
     * delete a specific devoir
     */
    public function destroy($id) {
        $devoir = Devoir::findOrFail($id);
        $devoirYears = DevoirAnneeScolaire::all()->where('coeffiecient_id', $devoir->id);
        foreach ($devoirYears as $devoirYear) {
            $devoirYear->delete();
        }
        $devoir->delete();
        return redirect()->route('education.devoirs')->with('deleteSuccess', 'Devoir supprimé avec succès !');
    }

    /**
     * migrate a devoir to next year
     */
    public function migrate(Request $request) {
        $request->validate([
            'migrate_year_id' => 'required|exists:annee_scolaires,id',
            'migrate_devoir_id' => 'required|exists:coefficients,id',
        ], [
            'migrate_year_id.required' => 'Aucune année selectionnée',
            'migrate_devoir_id.required' => 'Veuillez selectionnez un devoir',
        ]);

        // getting data for evaluation
        $y = AnneeScolaire::findOrFail($request->migrate_year_id);

        // checking if the devoir already migrated:
        $devoirYear = DevoirAnneeScolaire::all()
            ->where('annee_scolaire_id',$request->migrate_year_id)
            ->where('devoir_id',$request->migrate_devoir_id)
            ->first();

        if($devoirYear) {
            return response()->json([
                'error' => 'Le devoir a déjà été inclu pour l\'année : '.$y->libelleAnneeScolaire
            ]);
        } else {
            $newDevoirYear = DevoirAnneeScolaire::create([
                'devoir_id' => $request->migrate_devoir_id,
                'annee_scolaire_id' => $request->migrate_year_id,
            ]);

            if($newDevoirYear) {
                return response()->json(['success' => 'Devoir migré avec succès']);
            } else {
                return response()->json(['error' => 'Impossible de faire migrer le devoir']);
            }
        }
    }

    /**
     * delete a devoir in a current year
     */
    public function deleteInCurrentYear(Request $request) {
        $devoirYear = DevoirAnneeScolaire::all()->where('annee_scolaire_id',$request->delusyear_year_id)->where('devoir_id',$request->delusyear_devoir_id)->first();

        if ($devoirYear->delete()) {
            return redirect()->route('education.devoirs')->with('deleteSuccess', 'Devoir supprimé avec succès pour l\'année courrante');
        } else {
            return redirect()->route('education.devoirs')->with('errorSuccess', 'Echec de surpression du devoir pour l\'année courrante');
        }
    }
}
