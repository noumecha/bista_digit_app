<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * index function
     */
    public function index () {
        return view('front.about');
    }
}
