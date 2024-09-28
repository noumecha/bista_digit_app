<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PersonnelController extends Controller
{
        /**
     *
     */
    public function index(Request $request) {
        $user = User::find(Auth::id());
        $search = $request->input('search');
        $FonctionFilter = $request->input('funcFilter');

        $query = User::where('typeUser', '=', 'personnel');
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
        $personnels = $query->paginate(7);
        return view('personnel.administrators',compact('user','personnels','search','FonctionFilter'));
    }

     /**
     * saving administrators members
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'surname' => 'required|min:3|max:255',
            'email' => 'max:255|unique:users',
            'password' => 'required|min:8|max:255',
            'phone' => 'required|min:9|max:255',
            'diplome1' => 'max:255',
            'diplome2' => 'max:255',
            'lieuNaiss' => 'max:255',
            'dateNaiss' => 'max:255',
            'location' => 'max:255',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'fonction' => ['required', Rule::in(['Directeur Général','Comptable','Econome','Surveillant Général','Préfet des études','Principal','Dean Of Studies','Adjoint SG'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
            'name.required' => 'Entrez votre nom',
            'surname.required' => 'Entrez votre prenom',
            'email.unique' => 'L\'adresse email est déjà prise',
            'phone.required' => 'Entrez le numero de téléphone',
            'phone.max' => 'Le numero de téléphone doit contenir au moins 9 caractères',
            'numCni.unique' => 'Ce numéro de CNI est déjà dans le système',
            'sex.required' => 'Choisissez le sexe',
            'fonction.required' => 'Choisisssez la fonction',
        ]);

        User::create([
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

        $query = User::where('typeUser', '=', 'personnel');
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

        $personnels = $query->paginate(7);

        return view('personnel.administrators', compact('personnels','personnelToEdit','search','FonctionFilter'));
    }

    /**
     *
     */
    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'surname' => 'required|min:3|max:255',
            'email' => 'max:255',
            'password' => 'required|min:8|max:255',
            'phone' => 'required|min:9|max:255',
            'diplome1' => 'max:255',
            'diplome2' => 'max:255',
            'lieuNaiss' => 'max:255',
            'dateNaiss' => 'max:255',
            'location' => 'max:255',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'fonction' => ['required', Rule::in(['SG','DE','Principale','DET','DEC'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
                'name.required' => 'Entrez votre nom',
                'surname.required' => 'Entrez votre prenom',
                'phone.required' => 'Entrez le numero de téléphone',
                'sex.required' => 'Choisissez le sexe',
                'fonction.required' => 'Choisisssez la fonction',
        ]);

        $personnel = User::findOrFail($id);

        if($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('profiles', 'public');
            if ($personnel->profile) {
                Storage::disk('public')->delete($personnel->profile);
            }
            $personnel->profile = $imagePath;
        }
        //dd($request);
        $personnel->update($request->except('profile'));

        return redirect()->route('utilisateur.administrators')->with('success', 'Personnel mis à jour avec succès');
    }

    /**
     *
     */
    public function destroy($id) {
        $personnel = User::findOrFail($id);
        $personnel->delete();

        return redirect()->route('utilisateur.administrators')->with('deleteSuccess', 'Personnel supprimé avec succès');
    }
}
