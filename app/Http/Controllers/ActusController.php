<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActusController extends Controller
{
    /**
     * index function
     */
    public function index () {
        return view('front.actus.show');
    }
}
