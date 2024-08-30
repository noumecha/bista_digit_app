<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;

class RegisterController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.signup');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([

            'name' => 'required|min:3|max:255',
            'surname' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:7|max:255',
            'terms' => 'accepted',
        ], [
            'name.required' => 'Entrez votre nom',
            'surname.required' => 'Entrez votre prenom',
            'email.required' => 'Entrez l\'adresse email',
            'password.required' => 'Entrez un mot de passe',
            'terms.accepted' => 'You must accept the terms and conditions'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'surname' => $request->surname,
        ]);


        Auth::login($user);


        return redirect(RouteServiceProvider::HOME);
    }
}
