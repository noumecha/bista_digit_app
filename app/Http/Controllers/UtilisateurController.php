<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UtilisateurController extends Controller
{
    /**
     *
     */
    public function administrators () {
        $user = User::find(Auth::id());
        $personnels = User::all()->where('typeUser', '=', 'personnel');
        //dd($administrators);
        return view('personnel.administrators',['personnels'=> $personnels] ,compact('user'));
    }

    /**
     *
     */
    public function students () {
        $user = User::find(Auth::id());
        $students = User::all()->where('typeUser', '=', 'eleve');
        return view('personnel.students', ['students'=> $students], compact('user'));
    }
}
