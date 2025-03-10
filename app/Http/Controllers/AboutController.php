<?php

namespace App\Http\Controllers;

use App\Models\AppConfiguration;

class AboutController extends Controller
{
    /**
     * index function
     */
    public function index () {
        $appconfiguration = AppConfiguration::all()->first();
        return view('front.about',compact('appconfiguration'));
    }
}
