<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\EnseignantMatiereModel;
use App\Models\Enseignement;
use App\Models\FonctionAnneeScolaireUser;
use App\Models\Matiere;
use App\Models\User;
use App\Models\UserAnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EnseignantController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        $user = User::find(Auth::id());
        $matieres = Matiere::all();
        $teachers = User::all()->where('typeUser', '=', 'enseignant');
        $searchTeacher = $request->input('searchTeacher');
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $migrateYears = AnneeScolaire::all()->where('created_at', '>', $activeYear->created_at);
        $teacherSchoolYear = UserAnneeScolaire::all()->where('annee_scolaire_id','=', $activeYear->id);
        $teacherSchoolYearIds = $teacherSchoolYear->pluck('user_id');
        //dd($teacherSchoolYear);
        if(isset($teacherSchoolYear)) {
            $query = User::where('typeUser', '=', 'enseignant')->whereIn('id', $teacherSchoolYearIds);
        } else {
            $query = "";
        }
        if(!empty($searchTeacher)) {
            $query->where(function($q) use ($searchTeacher) {
                $q->where('name', 'LIKE', "%{$searchTeacher}%")
                ->orWhere('surname', 'LIKE', "%{$searchTeacher}%")
                ->orWhere('email', 'LIKE', "%{$searchTeacher}%")
                ->orWhere('phone', 'LIKE', "%{$searchTeacher}%");
            });
        }

        $query ? $teachers = $query->paginate(10) : $teachers = [];

        if($request->ajax()) {
            return view('partials._teachers_table', compact('matieres','teachers','user','searchTeacher','activeYear','migrateYears'));
        } else {
            return view('utilisateurs.teachers', compact('matieres','teachers','user','searchTeacher','activeYear','migrateYears'));
        }
    }

     /**
     * saving administrators members
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'matricule' => 'max:255|unique:users,id',
            'surname' => 'min:3|max:255',
            'email' => 'nullable|email|max:255|unique:users',
            'password' => 'required|min:8|max:255',
            'phone' => ['required', 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/'],
            'diplome1' => 'required|min:3|max:255',
            'diplome2' => 'max:255',
            'lieuNaiss' => 'max:255',
            'dateNaiss' => 'max:255',
            'location' => 'max:255',
            'active_year_id' => 'required',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
            'name.required' => 'Entrez le nom',
            'name.min' => 'Le nom doit contenir au moins 3 caractères',
            'email.email' => 'Entrez une adresse email valide',
            'email.unique' => 'Un utilisateur avec cette adresse email existe déjà',
            'surname.min' => 'Le prenom doit contenir au moins 3 caractères',
            'matricule.unique' => 'Le matricule existe déja dans la base de données',
            'phone.required' => 'Entrez le numero de téléphone',
            'phone.regex' => 'Le numero de téléphone doit être au format XXX-XXX-XXX',
            'profile.mimes' => 'L\'image doit à l\'un des formats (jpeg, png, gif)',
            'profile.image' => 'Le fichier doit être une image',
            'profile.max' => 'La taille du fichier ne doit pas dépasser 4Mo',
            'diplome1.required' => 'Entrez l\'intitulté du diplome 1',
            'active_year_id.required' => 'Aucune annéee selectionnée',
            'numCni.required' => 'Entrez le numero de la CNI',
            'sex.required' => 'Choisissez le sexe',
        ]);

        $teacher = User::create([
            'name' => $request->name,
            'matricule' => $request->matricule,
            'email' => $request->email,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'location' => $request->location,
            'lieuNaiss' => $request->lieuNaiss,
            'dateNaiss' => $request->dateNaiss,
            'diplome1' => $request->diplome1,
            'diplome2' => $request->diplome2,
            'numCni' => $request->numCni,
            'profile' => $request->hasFile('profile') ? $request->file('profile')->store('profiles', 'public') : 'profiles/default/default-avatar.png',
            'typeUser' => 'enseignant',
            'password' => Hash::make($request->password),
            'sex' => $request->sex,
            'create_year_id' => $request->active_year_id,
        ]);

        // add the teacher to the current school year
        $userAnneeScolaire = UserAnneeScolaire::create([
            'user_id' => $teacher->id,
            'annee_scolaire_id' => $request->active_year_id,
        ]);

        if($teacher && $userAnneeScolaire) {
            return response()->json(['success' => 'Enseignant ajouté avec succès']);
        } else {
            return response()->json(['error' => 'Erreur lors de l\'enregistrement du nouvel enseignant']);
        }
    }


    public function edit($id) {
        $teacherToEdit = User::findOrFail($id);
        return response()->json(['user' => $teacherToEdit]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'matricule' => 'max:255',
            'surname' => 'min:3|max:255',
            'email' => ['nullable','email','max:255',Rule::unique('users')->ignore($id)],
            'password' => 'required|min:8|max:255',
            'phone' => 'required|min:9|max:255',
            'phone' => ['required', 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/'],
            'diplome1' => 'required|min:3|max:255',
            'diplome2' => 'max:255',
            'lieuNaiss' => 'max:255',
            'dateNaiss' => 'max:255',
            'location' => 'max:255',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
                'name.required' => 'Entrez votre nom',
                'surname.required' => 'Entrez votre prenom',
                'phone.required' => 'Entrez le numero de téléphone',
                'phone.regex' => 'Le numero de téléphone doit être au format XXX-XXX-XXX',
                'profile.mimes' => 'L\'image doit à l\'un des formats (jpeg, png, gif)',
                'profile.image' => 'Le fichier doit être une image',
                'profile.max' => 'La taille du fichier ne doit pas dépasser 4Mo',
                'diplome1.required' => 'Entrez l\'intitulté du diplome 1',
                'sex.required' => 'Choisissez le sexe',
         ]);

        $teacher = User::findOrFail($id);

        if($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('profiles', 'public');
            if ($teacher->profile) {
                Storage::disk('public')->delete($teacher->profile);
            }
            $teacher->profile = $imagePath;
        }
        $teacher->update($request->except('profile'));
        return response()->json(['success' => 'Informations de l\'enseignant mis à jour avec succès']);
    }

    public function destroy($id) {
        $teacher = User::findOrFail($id);
        $teacherYears = UserAnneeScolaire::where('user_id', '=', $id);
        $teacherSubjects = EnseignantMatiereModel::where('user_id', $teacher->id);
        foreach ($teacherSubjects as $teacherSubject) {
            $enseignements = Enseignement::where('enseignant_matiere_id', $teacherSubject->id);
            foreach ($enseignements as $enseignement) {
                $enseignement->delete();
            }
        }
        $teacher->delete();
        $teacherYears->delete();
        $teacherSubjects->delete();

        return redirect()->route('utilisateur.teachers')->with('deleteSuccess', 'Enseignant supprimer avec succès');
    }
    /**
     *  delete user for the current year
     */
    public function deleteUserCurrentYear(Request $request) {

        $userYear = UserAnneeScolaire::all()->where('annee_scolaire_id', '=', $request->delusyear_year_id)->where('user_id', '=', $request->delusyear_user_id)->first();

        if ($userYear->delete()) {
            return redirect()->route('utilisateur.teachers')->with('deleteSuccess', 'Enseignant supprimé avec succès pour l\'année courrante');
        } else {
            return redirect()->route('utilisateur.teachers')->with('errorSuccess', 'Echec de surpression de l\'enseignant pour l\'année courrante');
        }

    }

    /**
     * Migrate teacher for a forward year to achieve application evolution
     */
    public function migrate(Request $request) {
        $request->validate([
            'migrate_year_id' => 'required|exists:annee_scolaires,id',
            'migrate_user_id' => 'required|exists:users,id',
            'migrate_current_year_id' => 'required|exists:annee_scolaires,id'
        ], [
            'migrate_year_id.required' => 'Aucune année selectionnée',
            'migrate_user_id.required' => 'Veuillez selectionnez un utilisateur',
            'migrate_current_year_id.required' => 'Veuillez activer une année scolaire',
        ]);

        // getting data for evaluation
        $y = AnneeScolaire::findOrFail($request->migrate_year_id);
        $u = User::findOrFail($request->migrate_user_id);

        // checking if the user already migrated:
        $userYear = UserAnneeScolaire::all()
            ->where('annee_scolaire_id','=',$request->migrate_year_id)
            ->where('user_id', '=', $request->migrate_user_id)
            ->first();

        if($userYear) {
            return response()->json(['error' => 'L\'enseignant '.$u->name.' à déjà été inclu pour l\'année : '.$y->libelleAnneeScolaire]);
        } else {
            UserAnneeScolaire::create([
                'user_id' => $request->migrate_user_id,
                'annee_scolaire_id'=>$request->migrate_year_id,
            ]);

            return response()->json(['success' => 'Enseignant migré avec succès']);
        }
    }
}
