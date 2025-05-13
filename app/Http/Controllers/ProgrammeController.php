<?php

namespace App\Http\Controllers;

use App\Models\BoosterMatiere;
use App\Models\BoosterStudent;
use App\Models\BoosterTeacher;
use App\Models\Classe;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\EnseignantMatiereModel;
use App\Models\EnsMatAnneeScolaire;
use App\Models\Matiere;
use App\Models\Specialite;
use App\Models\User;
use App\Models\UserAnneeScolaire;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProgrammeController extends Controller
{
    /**
     * configuration page
     */
    public function index(Request $request)
    {
        $page = Specialite::all()->where("type","booster-page")->last();
        return view('programme.booster', compact('page'));
    }

    /**
     * teachers of the programme booster
     */
    public function teachers(Request $request) {
        $query = BoosterTeacher::query()->where('annee_scolaire_id',getCurrentYear()->id);
        $classes = Classe::all();
        $matieres = BoosterMatiere::all();
        $teacherSchoolYearIds = UserAnneeScolaire::all()->where('annee_scolaire_id',getCurrentYear()->id)
            ->pluck('user_id');
        $teachers = User::all()->where('typeUser','enseignant')->whereIn('id', $teacherSchoolYearIds);
        // filters
        $searchText = $request->input('searchText');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        // filtering
        if(!empty($searchText)) {
            $query->whereHas('teacher', function ($q) use ($searchText) {
                $q->where('name', 'LIKE', "%{$searchText}%")
                    ->orWhere('surname', 'LIKE', "%{$searchText}%");
            });
        }
        if(!empty($classeFilter)) {
            $query->where('classe_id',$classeFilter);
        }
        if(!empty($matiereFilter)) {
            $query->where('booster_matiere_id',$matiereFilter);
        }
        $boosterteachers = $query->paginate(10);
        if($request->ajax()) {
            return view('partials._booster_teachers_table', compact('boosterteachers','classes','matieres','teachers'));
        } else {
            return view('programme.booster_teachers', compact('boosterteachers','classes','matieres','teachers'));
        }
    }

    /**
     * getting corresponding teacher matieres that is in the programmes
     */
    public function getTeacherMatieres($userId) {
        $ensMatYearIds = EnsMatAnneeScolaire::all()->where('annee_scolaire_id', getCurrentYear()->id)
            ->pluck('enseignant_matiere_models_id');
        $matiereIds = EnseignantMatiereModel::all()->whereIn('id',$ensMatYearIds)
            ->where('user_id', $userId)
            ->pluck('matiere_id');
        $matieres = BoosterMatiere::where('annee_scolaire_id', getCurrentYear()->id)
            ->whereIn('matiere_id', $matiereIds)->get();
        $datas = [];
        foreach ($matieres as $matiere) {
            array_push($datas, [
                'id' => $matiere->id,
                'name' => $matiere->matiere->libelleMatiere
            ]);
        }
        return response()->json($datas);
    }

    /**
     * matieres of the programme booster
     */
    public function matieres(Request $request) {
        $query = BoosterMatiere::query()->where('annee_scolaire_id',getCurrentYear()->id);
        $matieres = Matiere::all();
        $searchText = $request->input('searchText');
        // filtering
        if(!empty($searchText)) {
            $query->whereHas('matiere', function ($q) use ($searchText) {
                $q->where('libelleMatiere', 'LIKE', "%{$searchText}%")
                    ->orWhere('codeMatiere', 'LIKE', "%{$searchText}%");
            });
        }
        $boostermatieres = $query->paginate(10);
        if($request->ajax()) {
            return view('partials._booster_matieres_table', compact('boostermatieres', 'matieres'));
        } else {
            return view('programme.booster_matieres', compact('boostermatieres', 'matieres'));
        }
    }

    /**
     * students of the programme booster
     */
    public function students(Request $request) {
        $query = BoosterStudent::query()->where('annee_scolaire_id',getCurrentYear()->id);
        $classes = Classe::all();
        // usefull vars
        $studentSchoolYear = UserAnneeScolaire::all()->where('annee_scolaire_id',getCurrentYear()->id);
        $studentsSchoolYearId = $studentSchoolYear->pluck('user_id');
        $students = User::all()->where('typeUser', '=', 'eleve')->whereIn('id', $studentsSchoolYearId);
        $classesYearsStudents = ClasseAnneeScolaireStudent::all()->where('annee_scolaire_id', getCurrentYear()->id);
        // filters vars
        $searchText = $request->input('searchText');
        $classeFilter = $request->input('classeFilter');
        // filtering
        if(!empty($searchText)) {
            $query->whereHas('student', function ($q) use ($searchText) {
                $q->where('name', 'LIKE', "%{$searchText}%")
                ->orWhere('surname', 'LIKE', "%{$searchText}%");
            });
        }
        if(!empty($classeFilter)) {
            $userIdsWithYearClasse = $classesYearsStudents->where('classe_id', $classeFilter)
                ->pluck('user_id');
            $query->whereIn('user_id', $userIdsWithYearClasse);
        }
        $boosterstudents = $query->paginate(10);
        if($request->ajax()) {
            return view('partials._booster_students_table', compact('boosterstudents', 'students', 'classes'));
        } else {
            return view('programme.booster_students', compact('boosterstudents', 'students', 'classes'));
        }
    }

    /**
     * saving teacher of the programme
     */
    public function teacherSave(Request $request) {
        $request->validate([
            'user_id' => 'required',
            'classe_id' => 'required',
            'booster_matiere_id' => 'required'
        ], [
            'user_id.required' => 'Selectionnez un enseignant',
            'classe_id.required' => 'Selectionnez une classe',
            'booster_matiere_id.required' => 'Selectionnez une matiere'
        ]);
        try {
            # check if confgiuration already exits
            if(BoosterTeacher::where('classe_id', $request->classe_id)
                ->where('user_id',$request->user_id)
                ->where('booster_matiere_id', $request->booster_matiere_id)->exists())
            {
                return response()->json([
                    'error' => 'Cette configuration existe déjà!'
                ]);
            }
            if(BoosterTeacher::where('classe_id', $request->classe_id)
                ->where('booster_matiere_id', $request->booster_matiere_id)->exists()) {
                return response()->json([
                    'error' => 'Un enseignant gère déjà cette classe pour cette matière'
                ]);
            }
            # then save
            $evaluation = BoosterTeacher::create([
                'user_id' => $request->user_id,
                'classe_id' => $request->classe_id,
                'booster_matiere_id' => $request->booster_matiere_id,
                'annee_scolaire_id' => getCurrentYear()->id
            ]);
            if ($evaluation) {
                return response()->json(['success' => 'Enseignant ajouté avec succès!']);
            }
        } catch (\Throwable $ex) {
            return response()->json([
                'error' => 'Erreur : '.$ex->getMessage()
            ]);
        }

    }

    /**
     * saving matiere in the programme
     */
    public function matiereSave(Request $request) {
        $request->validate([
            'matiere_id' => 'required',
        ], [
            'matiere_id.required' => 'Selectionnez une matiere',
        ]);
        try {
            # check if confgiuration already exits
            if(BoosterMatiere::where('annee_scolaire_id',getCurrentYear()->id)
                ->where('matiere_id', $request->matiere_id)->exists()) {
                return response()->json([
                    'error' => 'Cette configuration existe déjà!'
                ]);
            }
            # then save
            $matiere = BoosterMatiere::create([
                'matiere_id' => $request->matiere_id,
                'annee_scolaire_id' => getCurrentYear()->id
            ]);
            if ($matiere) {
                return response()->json(['success' => 'Matière ajoutée avec succès!']);
            }
        } catch (Throwable $ex) {
            return response()->json([
                'error' => 'Erreur : '.$ex->getMessage
            ]);
        }
    }

    /**
     * saving students in the programme
     */
    public function studentSave(Request $request) {
        $request->validate([
            'user_id' => 'required',
        ], [
            'user_id.required' => 'Selectionnez une user',
        ]);
        try {
            # check if confgiuration already exits
            if(BoosterStudent::where('annee_scolaire_id',getCurrentYear()->id)
                ->where('user_id', $request->user_id)->exists()) {
                return response()->json([
                    'error' => 'Cette configuration existe déjà!'
                ]);
            }
            # then save
            $student = BoosterStudent::create([
                'user_id' => $request->user_id,
                'annee_scolaire_id' => getCurrentYear()->id
            ]);
            if ($student) {
                return response()->json(['success' => 'Elève ajouté avec succès!']);
            }
        } catch (Throwable $ex) {
            return response()->json([
                'error' => 'Erreur : '.$ex->getMessage()
            ]);
        }
    }

    /**
     * Delete teachers of the programme
     */
    public function teacherDelete($id) {
        $boosterTeacher = BoosterTeacher::findOrFail($id);
        $boosterTeacher->delete();
        return redirect()->route('booster.teachers')->with('deleteSuccess', 'Enseignant supprimé du pogramme');
    }

    /**
     * Delete matiere of the programme
     */
    public function matiereDelete($id) {
        $boosterMatiere = BoosterMatiere::findOrFail($id);
        $boosterMatiere->delete();
        return redirect()->route('booster.matieres')->with('deleteSuccess', 'Matière supprimé du pogramme');
    }

    /**
     * Delete students of the programme
     */
    public function studentDelete($id) {
        $boosterStudent = BoosterStudent::findOrFail($id);
        $boosterStudent->delete();
        return redirect()->route('booster.students')->with('deleteSuccess', 'Elève supprimé du pogramme');
    }

    /**
     * update or create page configuration
     */
    public function update(Request $request, $action) {
        $customAttributes = [];
        $imagePath = null;
        # entry var
        if (isset($request->pageconfigurationId)) {
            $specialite = Specialite::findOrFail($request->pageconfigurationId);
            $existingSliders = $specialite->sliders;
        } else {
            $existingSliders = [];
        }
        # validate form datas
        $rules = [
            'specialite_title' => 'required|min:3|max:255',
            'content' => 'required',
            'specialite_image' => 'image|mimes:jpg,jpeg,png,gif|max:4096',
            'sliders' => 'required|array',
            'sliders.*.title' => 'required|string',
            'sliders.*.image' => 'image|mimes:jpg,jpeg,png,gif|max:4096',
        ];
        $validators = [
            'specialite_title.required' => 'Veuillez entrez le titre de la specialite',
            'specialite_title.unique' => 'Ce titre de specialite existe déja',
            'specialite_title.min' => 'Le titre doit contenir minimum 3 caractères',
            'specialite_title.max' => 'Le titre contenir maximum 255 caractères',
            'content.required' => 'Veuillez remplir la description',
            'specialite_image.mimes' => 'L\'image doit être du type (jpg, jpeg, png, gif)',
            'sliders.required' => 'Veuillez ajouter au moins une image + descripton',
            'sliders.*.title.required' => 'Ajouter un titre ou mini description au :attribute',
            #'sliders.*.image.required' => 'Ajouter une image au :attribute',
            'sliders.*.image.mimes' => 'L\'image du :attribute doit être du type (jpg, jpeg, png, gif)',
        ];
        # checking data before processing
        if($request->has('sliders')) {
            foreach ($request->sliders as $index => $slider) {
                $sliderId = $index + 1;
                $customAttributes["sliders.$index.title"] = "slider $sliderId";
                $customAttributes["sliders.$index.image"] = "slider $sliderId";
                // checking titles
                $rules["sliders.$index.title"] = 'required|string';
                $validators["sliders.$index.title.required"] = "Ajouter un titre ou mini description au slider $sliderId";
                // checking images
                if(!isset($existingSliders[$index]['image'])) {
                    $rules["sliders.$index.image"] = 'required|image|mimes:jpg,jpeg,png,gif|max:4096';
                    $validators["sliders.$index.image.required"] = "Ajouter une image au slider $sliderId";
                    $validators["sliders.$index.image.mimes"] = "L'image du slider $sliderId doit être du type (jpg, jpeg, png, gif)";
                } else {
                    $rules["sliders.$index.image"] = 'nullable|image|mimes:jpg,jpeg,png,gif|max:4096';
                    $validators["sliders.$index.image.mimes"] = "L'image du slider $sliderId doit être du type (jpg, jpeg, png, gif)";
                }
            }
        }
        // adding new sliders
        $sliderData = [];
        if ($request->has('sliders')) {
            foreach ($request->sliders as $index => $slider) {
                $path = null;
                // update in case that a new image is upload
                if (isset($slider['image'])) {
                    $path = $slider['image']->store('sliders', 'public');
                    // delete the old image file
                    if (!empty($existingSliders[$index]['image'])) {
                        Storage::disk('public')->delete($existingSliders[$index]['image']);
                    }
                } else {
                    // keeping old image
                    $path = $existingSliders[$index]['image'] ?? null;
                }
                $sliderData[] = [
                    'title' => $slider['title'] ?? '',
                    'image' => $path,
                ];
            }
        }
        # if the action is create make image required
        if($action === 'create') {
            # image
            $rules['specialite_image'] = 'required|image|mimes:jpg,jpeg,png,gif|max:4096';
            $validators['specialite_image.required'] = 'Veuillez selectionner une image de mise en avant';
            # slider images
            if($request->has('sliders')) {
                foreach ($request->sliders as $index => $slider) {
                    $rules["sliders.$index.image"] = 'required|image|mimes:jpg,jpeg,png,gif|max:4096';
                    $validators["sliders.$index.image.required"] = "Ajouter une image au slider $sliderId";
                    $validators["sliders.$index.image.mimes"] = "L'image du slider $sliderId doit être du type (jpg, jpeg, png, gif)";
                }
            }
            # on create setting image
            if($request->hasFile('specialite_image')) {
                $imagePath = $request->file('specialite_image')->store('specialites', 'public');
            }
        }
        if($action === "update" && isset($specialite) && !isset($specialite->specialite_image)) {
            # image
            $rules['specialite_image'] = 'required|image|mimes:jpg,jpeg,png,gif|max:4096';
            $validators['specialite_image.required'] = 'Veuillez selectionner une image de mise en avant';
        }
        $request->validate($rules, $validators, $customAttributes);
        try {
            if($action === 'update' && isset($request->pageconfigurationId)) {
                $leaderPage = Specialite::findOrFail($request->pageconfigurationId);
                $leaderPage->update([
                    'specialite_title' => $request->specialite_title,
                    'contenu' => $request->content,
                    'sliders' => $sliderData
                ]);
                if($request->hasFile('specialite_image')) {
                    $imagePath = $request->file('specialite_image')->store('specialites', 'public');
                    if ($leaderPage->specialite_image) {
                        Storage::disk('public')->delete($leaderPage->specialite_image);
                    }
                    $leaderPage->update([
                        'specialite_image' => $imagePath
                    ]);
                }
                return response()->json(['success' => 'Configuration de la page booster mise à jour avec succès']);
            }
            if ($action === 'create') {
                $leaderPage = Specialite::create([
                    'specialite_title' => $request->specialite_title,
                    'contenu' => $request->content,
                    'specialite_image' => $imagePath,
                    'sliders' => $sliderData,
                    'type' => 'booster-page',
                ]);
                if ($leaderPage) {
                    return response()->json(['success' => 'Configuration de la page booster enregistrée avec succès']);
                }
            }
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    /**
     * edit page configuration
     */
    public function edit($id) {
        $pageconfiguration = Specialite::findOrFail($id);
        return response()->json([
            'pageconfiguration' => $pageconfiguration,
            'content' => $pageconfiguration->contenu
        ]);
    }
}
