<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\User;
use App\Models\UserAnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PersonnelController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        $user = User::find(Auth::id());
        $search = $request->input('search');
        $FonctionFilter = $request->input('funcFilter');
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $migrateYears = AnneeScolaire::all()->where('created_at', '>', $activeYear->created_at);
        $userSchoolYear = UserAnneeScolaire::all()->where('annee_scolaire_id','=', $activeYear->id);

        $usersSchoolYearId = $userSchoolYear->pluck('user_id');
        //dd($usersSchoolYearId);
        if(isset($userSchoolYear)) {
            $query = User::where('typeUser', '=', 'personnel')->whereIn('id', $usersSchoolYearId);
        } else {
            $query = "";
        }
        if(!empty($search) && !empty($FonctionFilter)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('surname', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%");
            })->where('fonction', $FonctionFilter);
        } elseif(!empty($FonctionFilter)) {
            $query->where('fonction', $FonctionFilter)
            ->where('typeUser', '=', 'personnel');
        } elseif (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('surname', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        $query ?  $personnels = $query->paginate(10) : $personnels = [];
        return view('personnel.administrators',compact('user','personnels','migrateYears','activeYear','search','FonctionFilter'));
    }

     /**
     * saving administrators members
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
            'fonction' => ['required', Rule::in(['Directeur Général','Comptable','Econome','Surveillant Général','Préfet des études','Principal','Dean Of Studies','Adjoint SG'])],
            'fonction' => 'unique:users',
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
            'fonction.required' => 'Choisisssez la fonction',
            'active_year_id.required' => 'Aucune annéee selectionnée',
            'fonction.unique' => 'Cette fonction est déja occupée',
            'password.min' => 'Le mot de passe doit contenir minimum 8 caractères',
        ]);

        $user = User::create([
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
            'fonction' => $request->fonction,
            'profile' => $request->hasFile('profile') ? $request->file('profile')->store('profiles', 'public') : '',
            'typeUser' => 'personnel',
            'password' => Hash::make($request->password),
            'sex' => $request->sex,
        ]);

        UserAnneeScolaire::create([
            'user_id' => $user->id,
            'annee_scolaire_id' => $request->active_year_id,
        ]);

        return redirect()->route('utilisateur.administrators')->with('success', 'Personnel ajouté avec succès!');
    }

    /**
     *
     */
    public function edit(Request $request, $id) {
        $personnels = User::all()->where('typeUser', '=', 'personnel');
        $personnelToEdit = User::findOrFail($id);
        $search = $request->input('search');
        $FonctionFilter = $request->input('funcFilter');
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $migrateYears = AnneeScolaire::all()->where('created_at', '>', $activeYear->created_at);
        $userSchoolYear = UserAnneeScolaire::all()->where('annee_scolaire_id','=', $activeYear->id)->first();
        $usersSchoolYearId = $userSchoolYear->pluck('user_id');

        if(isset($userSchoolYear)) {
            $query = User::where('typeUser', '=', 'personnel')->whereIn('id',$usersSchoolYearId);
        } else {
            $query = "";
        }
        if(!empty($search) && !empty($FonctionFilter)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('surname', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%");
            })->where('fonction', $FonctionFilter);
        } elseif(!empty($FonctionFilter)) {
            $query->where('fonction', $FonctionFilter)
            ->where('typeUser', '=', 'personnel');
        } elseif (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('surname', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $query ?  $personnels = $query->paginate(10) : $personnels = [];
        return view('personnel.administrators', ['#personnelform'], compact('personnels','personnelToEdit','activeYear','migrateYears','search','FonctionFilter'));
    }

    /**
     *
     */
    public function update(Request $request, $id) {
        //dd($request);
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
            'fonction' => ['required', Rule::in(['Directeur Général','Comptable','Econome','Surveillant Général','Préfet des études','Principal','Dean Of Studies','Adjoint SG'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
            //'active_year_id' => 'required',
        ], [
                'name.required' => 'Entrez le nom',
                'surname.required' => 'Entrez le prenom',
                'email.email' => 'Entrez une adresse email valide',
                'email.unique' => 'Un utilisateur avec cette adresse email existe déjà',
                'phone.required' => 'Entrez le numero de téléphone',
                'phone.regex' => 'Le numero de téléphone doit être au format XXX-XXX-XXX',
                'sex.required' => 'Choisissez le sexe',
                'fonction.required' => 'Choisisssez la fonction',
                'password.min' => 'Le mot de passe doit contenir minimum 8 caractères',
                //'active_year_id.required' => 'Aucune annéee selectionnée xxcx',
        ]);

        $personnel = User::findOrFail($id);

        if($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('profiles', 'public');
            if ($personnel->profile) {
                Storage::disk('public')->delete($personnel->profile);
            }
            $personnel->profile = $imagePath;
        }

        $personnel->update($request->except('profile'));

        /*UserAnneeScolaire::create([
            'user_id' => $personnel->id,
            'annee_scolaire_id' => $request->active_year_id,
        ]);*/

        return redirect()->route('utilisateur.administrators')->with('success', 'Personnel mis à jour avec succès');
    }

    /**
     *
     */
    public function destroy($id) {
        $personnel = User::findOrFail($id);
        $userYears = UserAnneeScolaire::where('user_id', '=', $id);
        $personnel->delete();
        $userYears->delete();

        return redirect()->route('utilisateur.administrators')->with('deleteSuccess', 'Personnel supprimé avec succès');
    }

    /**
     *
     */
    public function migrate(Request $request) {
        $y = AnneeScolaire::findOrFail($request->migrate_year_id);
        $request->validate([
            'migrate_year_id' => 'required',
            'migrate_user_id' => 'required',
        ], [
            'migrate_year_id.required' => 'Aucune année selectionnée',
            'migrate_user_id.required' => 'Veuillez selectionnez un utilisateur',
        ]);

        $userYear = UserAnneeScolaire::where('annee_scolaire_id','=',$request->migrate_year_id)->where('user_id', '=', $request->migrate_user_id);
        if($userYear) {
            return response()->json(['error' => 'L\'utilisateur à déjà été défini pour l\'année : '.$y->libelleAnneeScolaire]);
        } else {
            UserAnneeScolaire::create([
                'user_id' => $request->migrate_user_id,
                'annee_scolaire_id'=>$request->migrate_year_id,
            ]);
            return response()->json(['success' => 'Personnel migré avec succès']);
        }

        //return redirect()->route('utilisateur.administrators')->with('deleteSuccess', 'Personnel migré avec succès');
    }
}
