<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Fonction;
use App\Models\FonctionAnneeScolaireUser;
use App\Models\User;
use App\Models\UserAnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PersonnelController extends Controller
{
    /**
     * load personnel informations or querying
     */
    public function index(Request $request) {
        // define variables
        $user = User::find(Auth::id());
        $searchPersonnel = $request->input('searchPersonnel');
        $fonctions = Fonction::all();
        $FonctionFilter = $request->input('funcFilter');
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $migrateYears = AnneeScolaire::all()->where('created_at', '>', $activeYear->created_at);
        $userSchoolYear = UserAnneeScolaire::all()->where('annee_scolaire_id','=', $activeYear->id);
        $usersSchoolYearId = $userSchoolYear->pluck('user_id');
        $fonctionsUserYears = FonctionAnneeScolaireUser::all()->where('annee_scolaire_id', '=', $activeYear->id);

        // starting filtering
        if(isset($userSchoolYear)) {
            $query = User::where('typeUser', '=', 'personnel')->whereIn('id', $usersSchoolYearId);
        } else {
            $query = "";
        }
        if(!empty($searchPersonnel) && !empty($FonctionFilter)) {
            $query->where(function($q) use ($searchPersonnel) {
                $q->where('name', 'LIKE', "%{$searchPersonnel}%")
                ->orWhere('surname', 'LIKE', "%{$searchPersonnel}%")
                ->orWhere('email', 'LIKE', "%{$searchPersonnel}%")
                ->orWhere('phone', 'LIKE', "%{$searchPersonnel}%");
            });
            $userIdsWithFonction = $fonctionsUserYears->where('fonction_id', $FonctionFilter)
            ->pluck('user_id');
            $query->whereIn('id', $userIdsWithFonction);
        } elseif(!empty($FonctionFilter)) {
            $userIdsWithFonction = $fonctionsUserYears->where('fonction_id', $FonctionFilter)
            ->pluck('user_id');
            $query->whereIn('id', $userIdsWithFonction);
        } elseif (!empty($searchPersonnel)) {
            $query->where(function($q) use ($searchPersonnel) {
                $q->where('name', 'LIKE', "%{$searchPersonnel}%")
                ->orWhere('surname', 'LIKE', "%{$searchPersonnel}%")
                ->orWhere('email', 'LIKE', "%{$searchPersonnel}%")
                ->orWhere('phone', 'LIKE', "%{$searchPersonnel}%");
            });
        }
        $query ?  $personnels = $query->paginate(10) : $personnels = [];
        if($request->ajax()) {
            return view('partials._personnels_table', compact('user','personnels','migrateYears','activeYear','searchPersonnel','FonctionFilter','fonctions'));
        } else {
            return view('utilisateurs.personnels', compact('user','personnels','migrateYears','activeYear','searchPersonnel','FonctionFilter','fonctions'));
        }
    }

     /**
     * create a new personnel member
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'surname' => 'required|min:3|max:255',
            'email' => 'nullable|email|max:255|unique:users',
            'password' => 'required|min:8|max:255',
            'phone' => ['required', 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/'],
            'diplome1' => 'max:255',
            'diplome2' => 'max:255',
            'lieuNaiss' => 'max:255',
            'dateNaiss' => 'max:255',
            'location' => 'max:255',
            'active_year_id' => 'required',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'fonction_id' => [
                'required',
                'exists:fonctions,id',
                function ($attributes, $value, $fail) use ($request) {
                    $exists = FacadesDB::table('fonction_annee_scolaire_users')
                    ->where('fonction_id', $value)
                    ->where('annee_scolaire_id',$request->active_year_id)
                    ->exists();

                    if($exists) {
                        $fail('La fonction sélectionnée est déjà occupée pour l\'année scolaire actuelle.');
                    }
                }
            ],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
            'name.required' => 'Entrez le nom',
            'email.email' => 'Entrez une adresse email valide',
            'email.unique' => 'Un utilisateur avec cette adresse email existe déjà',
            'surname.required' => 'Entrez le prenom',
            'phone.required' => 'Entrez le numero de téléphone',
            'phone.regex' => 'Le numero de téléphone doit être au format XXX-XXX-XXX',
            'name.min' => 'Le nom doit contenir au moins 3 caractères',
            'surname.min' => 'Le prenom doit contenir au moins 3 caractères',
            'numCni.unique' => 'Ce numéro de CNI est déjà dans le système',
            'sex.required' => 'Choisissez le sexe',
            'fonction_id.required' => 'Choisisssez la fonction',
            'active_year_id.required' => 'Aucune annéee selectionnée',
            'password.min' => 'Le mot de passe doit contenir minimum 8 caractères',
        ]);

        $personnel = User::create([
            'name' => $request->name,
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
            'typeUser' => 'personnel',
            'password' => Hash::make($request->password),
            'sex' => $request->sex,
            'create_year_id' => $request->active_year_id,
        ]);

        // relation between user - fonction - school year
        $fonctionAnneeScolaireUser = FonctionAnneeScolaireUser::create([
            'user_id' => $personnel->id,
            'fonction_id' => $request->fonction_id,
            'annee_scolaire_id' => $request->active_year_id,
        ]);

        // add the user to the current school year
        $userAnneeScolaire = UserAnneeScolaire::create([
            'user_id' => $personnel->id,
            'annee_scolaire_id' => $request->active_year_id,
        ]);

        if($personnel && $fonctionAnneeScolaireUser && $userAnneeScolaire) {
            return response()->json(['success' => 'Personnel ajouté avec succès']);
        } else {
            return response()->json(['error' => 'Erreur lors de l\'enregistrement du personnel']);
        }

    }

    /**
     * click to edit a personnel
     */
    public function edit($id, $yearId) {
        $personnelToEdit = User::findOrFail($id);
        $currentUserFonction = FonctionAnneeScolaireUser::where('user_id', '=', $id)
        ->where('annee_scolaire_id', '=', (int)$yearId)->first();
        return response()->json(['personnel' => $personnelToEdit,'fonction_id' => $currentUserFonction->fonction_id]);
    }

    /**
     * update user information
     */
    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'surname' => 'required|min:3|max:255',
            'email' => ['nullable','email','max:255',Rule::unique('users')->ignore($id)],
            'password' => 'required|min:8|max:255',
            'phone' => ['required', 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/'],
            'diplome1' => 'max:255',
            'diplome2' => 'max:255',
            'lieuNaiss' => 'max:255',
            'dateNaiss' => 'max:255',
            'location' => 'max:255',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'fonction_id' => [
                'required',
                'exists:fonctions,id',
                function ($attributes, $value, $fail) use ($request, $id) {
                    $exists = FacadesDB::table('fonction_annee_scolaire_users')
                    ->where('user_id','!=',$id)
                    ->where('fonction_id', $value)
                    ->where('annee_scolaire_id',$request->active_year_id)
                    ->exists();

                    if($exists) {
                        $fail('La fonction sélectionnée est déjà occupée pour l\'année scolaire actuelle.');
                    }
                }
            ],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
            'name.required' => 'Entrez le nom',
            'surname.required' => 'Entrez le prenom',
            'email.email' => 'Entrez une adresse email valide',
            'email.unique' => 'Un utilisateur avec cette adresse email existe déjà',
            'phone.required' => 'Entrez le numero de téléphone',
            'phone.regex' => 'Le numero de téléphone doit être au format XXX-XXX-XXX',
            'sex.required' => 'Choisissez le sexe',
            'fonction_id.required' => 'Choisisssez la fonction',
            'password.min' => 'Le mot de passe doit contenir minimum 8 caractères',
        ]);

        $personnel = User::findOrFail($id);
        $currentUserFonction = FonctionAnneeScolaireUser::where('user_id', '=', $id)
        ->where('annee_scolaire_id', '=', $request->active_year_id)->first();
        if($currentUserFonction->fonction_id !== (int)$request->fonction_id) {
            // get the old fonction_scoool_year_user of the same year && delete it.
            if($currentUserFonction) {
                $currentUserFonction->delete();
            }

            FonctionAnneeScolaireUser::create([
                'user_id' => $id,
                'fonction_id' => $request->fonction_id,
                'annee_scolaire_id' => $request->active_year_id,
            ]);
        }

        if($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('profiles', 'public');
            if ($personnel->profile) {
                Storage::disk('public')->delete($personnel->profile);
            }
            $personnel->profile = $imagePath;
        }

        $personnel->update($request->except('profile'));

        return response()->json(['success' => 'Informations du personnel mis à jour avec succès']);
    }

    /**
     * delete user definitely
     */
    public function destroy($id) {
        $personnel = User::findOrFail($id);
        $userYears = UserAnneeScolaire::where('user_id', '=', $id);
        $userFonctionYear = FonctionAnneeScolaireUser::where('user_id', '=', $id);
        $personnel->delete();
        $userYears->delete();
        $userFonctionYear->delete();

        return redirect()->route('utilisateur.personnels')->with('deleteSuccess', 'Personnel supprimé définitivement avec succès');
    }

    /**
     *  delete user for the current year
     */
    public function deleteUserCurrentYear(Request $request) {

        $userYear = UserAnneeScolaire::all()->where('annee_scolaire_id', '=', $request->delusyear_year_id)->where('user_id', '=', $request->delusyear_user_id)->first();
        $userFonctionYear = FonctionAnneeScolaireUser::all()
            ->where('annnee_scolaire_id', '=', $request->delusyear_year_id)
            ->where('user_id', '=',$request->delusyear_user_id)
            ->first();

        if ($userYear->delete() && $userFonctionYear->delete()) {
            return redirect()->route('utilisateur.personnels')->with('deleteSuccess', 'Personnel supprimé avec succès pour l\'année courrante');
        } else {
            return redirect()->route('utilisateur.personnels')->with('errorSuccess', 'Echec de surpression du personnel pour l\'année courrante');
        }

    }

    /**
     * Migrate personnel for a forward year to achieve application evolution
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
        $fonctionAnneeScolaireUser = FonctionAnneeScolaireUser::where('user_id', '=', $request->migrate_user_id)
        ->where('annee_scolaire_id','=',$request->migrate_current_year_id)->first();
        $fonctionId = $fonctionAnneeScolaireUser->fonction_id;
        $f = Fonction::findOrFail($fonctionId);

        // checking if the user already migrated:
        $userYear = UserAnneeScolaire::all()
            ->where('annee_scolaire_id','=',$request->migrate_year_id)
            ->where('user_id', '=', $request->migrate_user_id)
            ->first();
        // checking if a user in the migrate year already have the user fonction:
        $userYearFonction = FonctionAnneeScolaireUser::all()
            ->where('annee_scolaire_id','=',$request->migrate_year_id)
            ->where('fonction_id','=',$fonctionId)
            ->first();

        if($userYear) {
            return response()->json(['error' => 'L\'utilisateur à déjà été inclu pour l\'année : '.$y->libelleAnneeScolaire]);
        } else if ($userYearFonction) {
            return response()->json(['error' => 'Un utilisateur avec la fonction '.$f->libelleFonction.' existe déjà pour l\'année '.$y->libelleAnneeScolaire]);
        } else {
            UserAnneeScolaire::create([
                'user_id' => $request->migrate_user_id,
                'annee_scolaire_id'=>$request->migrate_year_id,
            ]);
            // relation between user - fonction - school year
            FonctionAnneeScolaireUser::create([
                'user_id' => $request->migrate_user_id,
                'fonction_id' => $fonctionId,
                'annee_scolaire_id' => $request->migrate_year_id,
            ]);
            return response()->json(['success' => 'Personnel migré avec succès']);
        }
    }
}
