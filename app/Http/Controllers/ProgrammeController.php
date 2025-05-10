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

        return view('programme.booster', compact('user'));
    }

    /**
     * Programme controller : leader
     */
    public function leader()
    {
        $user = User::find(Auth::id());

        return view('programme.booster', compact('user'));
    }

    /**
     * programme controller : configuration
     */
    public function configuration() {

    }
}
