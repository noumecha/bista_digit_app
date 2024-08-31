<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    /**
     *
     */
    public function index()
    {
        $user = User::find(Auth::id());

        return view('evaluation.notes', compact('user'));
    }
}
