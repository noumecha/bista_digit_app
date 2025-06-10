<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        $user = User::find(Auth::id());
        $userYearIds = UserAnneeScolaire::where('annee_scolaire_id', getCurrentYear()->id)->pluck('user_id');
        $users = User::all()->whereIn('id', $userYearIds);//->where('typeUser', 'enseignant')
            //->orWhere('typeUser', 'personnel');
        $searchFilter = $request->input('searchFilter');
        $roleFilter = $request->input('roleFilter');
        $typeFilter = $request->input('typeFilter');
        $query = User::query();
        if(!empty($searchFilter) ) {
            $query->where('name','LIKE',"%{$searchFilter}%");
        }
        if(!empty($roleFilter)) {
            $query->where('role',$roleFilter);
        }
        if(!empty($typeFilter)) {
            $query->where('typeUser',$typeFilter);
        }
        $roles = $query->paginate(10);
        if($request->ajax()) {
            return view('partials._roles_table', compact('user', 'roles', 'users'));
        } else {
            return view('utilisateurs.roles', compact('user', 'roles', 'users'));
        }
    }

     /**
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) {
        $request->validate([
            'role' => 'required|in:admin,user,surveillant',
            'user_id' => 'required',
        ], [
            'role.required' => 'Selectionnez un rôle',
            'user_id.required' => 'Selectionnez un utilisateur',
        ]);
        $user = User::findOrFail($request->user_id);
        $user->update([
            'role' => $request->role
        ]);
        return response()->json(['success' => 'Nouveau rôle attribuer avec succès']);
    }

    /**
     *
     */
    public function edit($id) {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user]);
    }

    /**
     * updating fonction
     */
    public function update(Request $request, $id) {
        $request->validate([
            'role' => 'required|in:admin,user,surveillant',
        ], [
            'role.required' => 'Selectionnez un rôle',
        ]);
        $user = User::findOrFail($id);
        $user->update([
            'role' => $request->role
        ]);
        return response()->json(['success' => 'Le rôle a été mis à jour avec succès']);
    }
}
