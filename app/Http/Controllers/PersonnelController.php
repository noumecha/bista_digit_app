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
    public function index() {
        $user = User::find(Auth::id());
        $personnels = User::all()->where('typeUser', '=', 'personnel');
        return view('personnel.administrators',['personnels'=> $personnels] ,compact('user'));
    }

     /**
     * saving administrators members
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'surname' => 'required|min:3|max:255',
            'email' => 'email|max:255|unique:users',
            'password' => 'required|min:8|max:255',
            'phone' => 'required|min:9|max:255',
            'diplome1' => 'required|min:3|max:255',
            'diplome2' => 'required|min:3|max:255',
            'lieuNaiss' => 'required|min:3|max:255',
            'dateNaiss' => 'required|max:255',
            'location' => 'required|min:3|max:255',
            'numCni' => 'required|max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'fonction' => ['required', Rule::in(['SG','DE','Principale','DET','DEC'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
                'name.required' => 'Entrez votre nom',
                'surname.required' => 'Entrez votre prenom',
                'email' => 'Entrez l\'adresse email',
                'email.unique' => 'L\'adresse email est déjà prise',
                'phone.required' => 'Entrez le numero de téléphone',
                'location.required' => 'Entrez le lieu de résidence',
                'lieuNaiss.required' => 'Entrez le lieu de naissance',
                'dateNaiss.required' => 'Entrez la date de naissance',
                'diplome1.required' => 'Entrez l\'intitulté du diplome 1',
                'diplome2.required' => 'Entrez l\'intitulté du diplome 2',
                'numCni.required' => 'Entrez le numero de la CNI',
                'sex.required' => 'Choisissez le sexe',
                'fonction.required' => 'Choisisssez la fonction',
         ]);

        $imagePath = $request->file('profile')->store('profiles', 'public');

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'location' => $request->location,
            'lieuNaiss' => $request->lieuNaiss,
            'dateNaiss' => $request->dateNaiss,
            'diplome1' => $request->diplone1,
            'diplome2' => $request->diplome2,
            'numCni' => $request->numCni,
            'profile' => $imagePath,
            'fonction' => $request->fonction,
            'typeUser' => 'personnel',
            'password' => Hash::make($request->password),
            'sex' => $request->sex,
        ]);

        return redirect()->route('utilisateur.administrators')->with('success', 'Personnel ajouté avec succès!');
    }

    /**
     *
     */
    public function edit($id) {
        $personnels = User::all()->where('typeUser', '=', 'personnel');
        $personnelToEdit = User::findOrFail($id);

        return view('personnel.administrators', compact('personnels','personnelToEdit'));
    }

    /**
     *
     */
    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'surname' => 'required|min:3|max:255',
            'email' => 'email|max:255',
            'password' => 'required|min:8|max:255',
            'phone' => 'required|min:9|max:255',
            'diplome1' => 'required|min:3|max:255',
            'diplome2' => 'min:3|max:255',
            'lieuNaiss' => 'required|min:3|max:255',
            'dateNaiss' => 'required|max:255',
            'location' => 'required|min:3|max:255',
            'numCni' => 'required|max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'fonction' => ['required', Rule::in(['SG','DE','Principale','DET','DEC'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
                'name.required' => 'Entrez votre nom',
                'surname.required' => 'Entrez votre prenom',
                'phone.required' => 'Entrez le numero de téléphone',
                'location.required' => 'Entrez le lieu de résidence',
                'lieuNaiss.required' => 'Entrez le lieu de naissance',
                'dateNaiss.required' => 'Entrez la date de naissance',
                'diplome1.required' => 'Entrez l\'intitulté du diplome 1',
                'numCni.required' => 'Entrez le numero de la CNI',
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
