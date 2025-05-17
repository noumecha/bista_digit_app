<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     *
     */
    public function index()
    {
        $user = User::find(Auth::id());

        return view('configurations.profil_configuration', compact('user'));
    }

    /**
     * udpate password
     */
    public function updatePassword(Request $request) {
        $request->validate([
            'password' => 'required|min:8|max:255',
            'confirmPassword' => 'required|min:8|max:255',
        ], [
            'password.required' => 'Entrez le mot de passe',
            'password.min' => 'Le mot de passe ne peut pas dépasser 255 caractère',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'confirmPassword.max' => 'La confirmation ne peut pas dépasser 255 caractères',
            'confirmPassword.min' => 'La confirmation doit contenir au moins 8 caractères',
            'confirmPassword.required' => 'Confirmez le mot de passe',
        ]);
        if($request->password != $request->confirmPassword) {
            return response()->json([
                'error' => 'Les mots de passes ne sont pas identiques'
            ]);
        }
        $user = User::find(Auth::id());
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'success' => 'Mot de passe mis à jour avec succès!'
        ]);
    }

    /**
     * Update user informations
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'location' => 'max:255',
            'phone' => ['required', 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/'],
            'about' => 'max:255',
        ], [
            'name.required' => 'Entrez le nom',
            'email.required' => 'Entrez l\'adresse email',
            'phone.regex' => 'Le numero de téléphone doit être au format XXX-XXX-XXX',
            'phone.required' => 'Renseignez le numéro de téléphone'
        ]);
        $user = User::find(Auth::id());
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'location' => $request->location,
            'phone' => $request->phone,
            'about' => $request->about,
        ]);
        return back()->with('success', 'Informations du profil mises à jour avec succès!');
    }

    /**
     * edit user informations
     */
    public function edit($id) {
        $userToEdit = User::findOrFail($id);
        return response()->json([
            'userToE$userToEdit' =>$userToEdit,
        ]);
    }
}
