<?php

namespace App\Http\Controllers;

use App\Models\UserAnneeScolaire;
use Illuminate\Http\Request;

class UserAnneeScolaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'migrate_year_id' => 'required',
            'migrate_user_id' => 'required',
        ], [
            'migrate_year_id.required' => 'Aucune année selectionnée',
            'migrate_user_id.required' => 'Veuillez selectionnez un utilisateur',
        ]);

        UserAnneeScolaire::create([
            'user_id' => $request->migrate_user_id,
            'annee_scolaire_id'=>$request->migrate_year_id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserAnneeScolaire $userAnneeScolaire)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserAnneeScolaire $userAnneeScolaire)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserAnneeScolaire $userAnneeScolaire)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAnneeScolaire $userAnneeScolaire)
    {
        //
    }
}
