<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class LoginController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.signin');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //$credentials = $request->only('email', 'password');

        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Entrez votre adresse email ou votre matricule',
            'password.required' => 'Entrez votre mot de passe',
        ]);

        $loginInput = $request->input('login');
        $password = $request->input('password');
        $rememberMe = $request->rememberMe ? true : false;

        $loginType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'matricule' ;

        if (Auth::attempt([$loginType => $loginInput, 'password' => $password], $rememberMe)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        /*if (Auth::attempt($credentials, $rememberMe)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }*/

        return back()->withErrors([
            'message' => 'Identifiants incorrect. Veuillez vérifier votre adresse email, votre matricule ou votre mot de passe',
        ])->withInput($request->only('login'));
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/sign-in');
    }
}
