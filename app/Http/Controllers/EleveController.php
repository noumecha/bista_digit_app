<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\User;
use App\Models\UserAnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EleveController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        $user = User::find(Auth::id());
        $classes = Classe::all();
        $searchStudent = $request->input('searchStudent');
        $classeFilter = $request->input('classFilter');
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $migrateYears = AnneeScolaire::all()->where('created_at', '>', $activeYear->created_at);
        $studentSchoolYear = UserAnneeScolaire::all()->where('annee_scolaire_id','=', $activeYear->id);
        $studentsSchoolYearId = $studentSchoolYear->pluck('user_id');
        $classesYearsStudents = ClasseAnneeScolaireStudent::all()->where('annee_scolaire_id', '=', $activeYear->id);

        if(isset($studentSchoolYear)) {
            $query = User::where('typeUser', '=', 'eleve')->whereIn('id', $studentsSchoolYearId);
        } else {
            $query = "";
        }

        if(!empty($searchStudent) && !empty($classeFilter)) {
            $query->where(function ($q) use ($searchStudent) {
                $q->where('name', 'LIKE', "%{$searchStudent}%")
                ->orWhere('surname', 'LIKE', "%{$searchStudent}%")
                ->orWhere('email', 'LIKE', "%{$searchStudent}%")
                ->orWhere('phone', 'LIKE', "%{$searchStudent}%");
            });
            $userIdsWithYearClasse = $classesYearsStudents->where('classe_id', $classeFilter)
            ->pluck('user_id');
            $query->whereIn('id', $userIdsWithYearClasse);
        } elseif(!empty($classeFilter)) {
            $userIdsWithYearClasse = $classesYearsStudents->where('classe_id', $classeFilter)
            ->pluck('user_id');
            $query->whereIn('id', $userIdsWithYearClasse);
        } elseif (!empty($searchStudent)) {
            $query->where(function ($q) use ($searchStudent) {
                $q->where('name', 'LIKE', "%{$searchStudent}%")
                ->orWhere('surname', 'LIKE', "%{$searchStudent}%")
                ->orWhere('email', 'LIKE', "%{$searchStudent}%")
                ->orWhere('phone', 'LIKE', "%{$searchStudent}%");
            });
        }
        $query ?  $students = $query->paginate(10) : $students = [];

        if($request->ajax()) {
            return view('partials._students_table', compact(
                'classes','students',
                'user','searchStudent',
                'classeFilter','activeYear',
                'migrateYears'));
        } else {
            return view('utilisateurs.students', compact(
                'classes','students',
                'user','searchStudent',
                'classeFilter','migrateYears',
                'activeYear'));
        }
    }

    /**
    * saving students
    */
    public function store(Request $request) {

        $request->validate([
            'name' => 'required|min:3|max:255',
            'matricule' => 'required|min:3|max:255|unique:users',
            'surname' => 'required|min:3|max:255',
            'email' => 'nullable|email|max:255|unique:users',
            'password' => 'required|min:8|max:255',
            'phone' => ['required', 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/'],
            'lieuNaiss' => 'required|min:3|max:255',
            'dateNaiss' => 'required|max:255',
            'location' => 'required|min:3|max:255',
            'active_year_id' => 'required',
            'classe_id' => 'required|exists:classes,id',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
            'name.required' => 'Entrez le nom de l\'élève',
            'email.email' => 'Entrez une adresse email valide',
            'email.unique' => 'Un utilisateur avec cette adresse email existe déjà',
            'surname.required' => 'Entrez le prenom de l\'élève',
            'phone.required' => 'Entrez le numero de téléphone de l\'élève ou du parent',
            'phone.regex' => 'Le numero de téléphone doit être au format XXX-XXX-XXX',
            'profile.mimes' => 'L\'image doit à l\'un des formats (jpeg, png, gif)',
            'profile.image' => 'Le fichier doit être une image',
            'profile.max' => 'La taille du fichier ne doit pas dépasser 4Mo',
            'name.min' => 'Le nom doit contenir au moins 3 caractères',
            'surname.min' => 'Le prenom doit contenir au moins 3 caractères',
            'location.required' => 'Entrez le lieu de résidence',
            'lieuNaiss.required' => 'Entrez le lieu de naissance',
            'dateNaiss.required' => 'Entrez la date de naissance',
            'matricule.required' => 'Entrez le matricule de l\'élève',
            'matricule.unique' => 'Ce matricule existe déja dans la base de données',
            'classe_id.required' => 'Veuillez selectionnez une classe',
            'active_year_id.required' => 'Aucune annéee selectionnée',
            'sex.required' => 'Choisissez le sexe',
        ]);

        $student = User::create([
            'name' => $request->name,
            'matricule' => $request->matricule,
            'email' => $request->email,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'location' => $request->location,
            'lieuNaiss' => $request->lieuNaiss,
            'dateNaiss' => $request->dateNaiss,
            'numCni' => $request->numCni,
            'create_year_id' => $request->active_year_id,
            'classe_id' => $request->classe_id,
            'profile' => $request->hasFile('profile') ? $request->file('profile')->store('profiles', 'public') : 'profiles/default/default-avatar.png',
            'typeUser' => 'eleve',
            'password' => Hash::make($request->password),
            'sex' => $request->sex,
        ]);

        // relation between student - classe - school year
        $classeAnneeScolaireStudent = ClasseAnneeScolaireStudent::create([
            'user_id' => $student->id,
            'classe_id' => $request->classe_id,
            'annee_scolaire_id' => $request->active_year_id,
        ]);

        // add the student to the current school year
        $userAnneeScolaire = UserAnneeScolaire::create([
            'user_id' => $student->id,
            'annee_scolaire_id' => $request->active_year_id,
        ]);

        if($student && $classeAnneeScolaireStudent && $userAnneeScolaire) {
            return response()->json(['success' => 'Eleve ajouté avec succès']);
        } else {
            return response()->json(['error' => 'Erreur lors de l\'enregistrement d\'élève']);
        }
    }

    public function edit($id, $yearId) {
        $studentToEdit = User::findOrFail($id);
        $currentStudentClasseYear = ClasseAnneeScolaireStudent::where('user_id', '=', $id)
        ->where('annee_scolaire_id', '=', (int)$yearId)->first();
        return response()->json(['student' => $studentToEdit,'classe_id' => $currentStudentClasseYear->classe_id]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'matricule' => 'required|min:3|max:255',
            'surname' => 'required|min:3|max:255',
            'email' => ['nullable','email','max:255',Rule::unique('users')->ignore($id)],
            'password' => 'required|min:8|max:255',
            'phone' => ['required', 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/'],
            'lieuNaiss' => 'required|min:3|max:255',
            'dateNaiss' => 'required|max:255',
            'location' => 'required|min:3|max:255',
            'classe_id' => 'required|exists:classes,id',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
            'name.required' => 'Entrez le nom de l\'élève',
            'surname.required' => 'Entrez le prenom de l\'élève',
            'email.email' => 'Entrez une adresse email valide',
            'email.unique' => 'Un utilisateur avec cette adresse email existe déjà',
            'phone.required' => 'Entrez le numero de téléphone de l\'élève ou du parent',
            'phone.regex' => 'Le numero de téléphone doit être au format XXX-XXX-XXX',
            'profile.mimes' => 'L\'image doit à l\'un des formats (jpeg, png, gif)',
            'profile.image' => 'Le fichier doit être une image',
            'profile.max' => 'La taille du fichier ne doit pas dépasser 4Mo',
            'location.required' => 'Entrez le lieu de résidence',
            'lieuNaiss.required' => 'Entrez le lieu de naissance',
            'dateNaiss.required' => 'Entrez la date de naissance',
            'classe_id.required' => 'Veuillez selectionnez une classe',
            'matricule.required' => 'Entrez le matricule de l\'élève',
            'sex.required' => 'Choisissez le sexe',
            'classe_id.required' => 'Veuillez chosir une classe',
        ]);


        $student = User::findOrFail($id);
        $currentClasseYearStudent = ClasseAnneeScolaireStudent::where('user_id', '=', $id)
        ->where('annee_scolaire_id', '=', $request->active_year_id)->first();
        if($currentClasseYearStudent->classe_id !== (int)$request->classe_id) {
            // get the old classe_scoool_year_user of the same year && delete it.
            if($currentClasseYearStudent) {
                $currentClasseYearStudent->delete();
            }

            ClasseAnneeScolaireStudent::create([
                'user_id' => $id,
                'classe_id' => $request->classe_id,
                'annee_scolaire_id' => $request->active_year_id,
            ]);
        }

        if($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('profiles', 'public');
            if ($student->profile) {
                Storage::disk('public')->delete($student->profile);
            }
            $student->profile = $imagePath;
        }

        $student->update($request->except('profile'));

        return response()->json(['success' => 'Informations de l\'élève mis à jour avec succès']);
    }

    /**
     * delete student forever
     */
    public function destroy($id) {
        $student = User::findOrFail($id);
        $studentYears = UserAnneeScolaire::where('user_id', '=', $id);
        $classeYearStudent = ClasseAnneeScolaireStudent::where('user_id', '=', $id);
        $studentYears->delete();
        $classeYearStudent->delete();
        $student->delete();

        return redirect()->route('utilisateur.students')->with('deleteSuccess', 'Elève supprimer avec succès');
    }

    /**
     *  delete student for the current year
     */
    public function deleteUserCurrentYear(Request $request) {

        $userYear = UserAnneeScolaire::all()->where('annee_scolaire_id', '=', $request->delusyear_year_id)->where('user_id', '=', $request->delusyear_user_id)->first();
        $classeYearStudent = ClasseAnneeScolaireStudent::all()
            ->where('annee_scolaire_id', '=', $request->delusyear_year_id)
            ->where('user_id', '=',$request->delusyear_user_id)
            ->first();

        if ($userYear->delete() && $classeYearStudent->delete()) {
            return redirect()->route('utilisateur.students')->with('deleteSuccess', 'Elève supprimé avec succès pour l\'année courrante');
        } else {
            return redirect()->route('utilisateur.students')->with('errorSuccess', 'Echec de surpression du élève pour l\'année courrante');
        }

    }

    /**
     * Migrate student for a forward year to achieve application evolution
     */
    public function migrate(Request $request) {
        $request->validate([
            'migrate_year_id' => 'required|exists:annee_scolaires,id',
            'migrate_user_id' => 'required|exists:users,id',
            'migrate_current_year_id' => 'required|exists:annee_scolaires,id'
        ], [
            'migrate_year_id.required' => 'Aucune année selectionnée',
            'migrate_user_id.required' => 'Veuillez selectionnez un élève',
            'migrate_current_year_id.required' => 'Veuillez activer une année scolaire',
        ]);

        // getting data for evaluation
        $y = AnneeScolaire::findOrFail($request->migrate_year_id);
        $u = User::findOrFail($request->migrate_user_id);
        $classeAnneeScolaireStudent = ClasseAnneeScolaireStudent::where('user_id', '=', $request->migrate_user_id)
        ->where('annee_scolaire_id','=',$request->migrate_current_year_id)->first();
        $classeId = $classeAnneeScolaireStudent->classe_id;

        // checking if the user already migrated:
        $userYear = UserAnneeScolaire::all()
            ->where('annee_scolaire_id','=',$request->migrate_year_id)
            ->where('user_id', '=', $request->migrate_user_id)
            ->first();

        if($userYear) {
            return response()->json([
                'error' => 'L\'élève a déjà été inclu pour l\'année : '.$y->libelleAnneeScolaire
            ]);
        } else {
            $newStudentYear = UserAnneeScolaire::create([
                'user_id' => $request->migrate_user_id,
                'annee_scolaire_id'=>$request->migrate_year_id,
            ]);
            // relation between user - fonction - school year
            $newClasseYearStudent = ClasseAnneeScolaireStudent::create([
                'user_id' => $request->migrate_user_id,
                'classe_id' => $classeId,
                'annee_scolaire_id' => $request->migrate_year_id,
            ]);

            if( $newStudentYear && $newClasseYearStudent ) {
                return response()->json(['success' => 'Elève migré avec succès']);
            } else {
                return response()->json(['error' => 'Impossible de faire migrer l\'élève!']);
            }
        }
    }

}
