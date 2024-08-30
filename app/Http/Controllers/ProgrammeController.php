<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgrammeController extends Controller
{
    /**
     * Education controller implmentation
     */
    public function index()
    {
        $user = User::find(Auth::id());

        return view('programme.programme-booster', compact('user'));
    }
}
