<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Sex;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $fillable = [
        'surname',
        'numCni',
        'sex',
        'role',
        'profile',
        'dateNaiss',
        'lieuNaiss',
        'diplome1',
        'diplome2',
        'matricule',
        'statutRedoublance',
        'typeUser',
        'name',
        'email',
        'password',
        'phone',
        'location',
        'about',
        'create_year_id',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'sex' => Sex::class,
    ];

    /**
     * a user can be on many schools years
     */
    public function anneeScolaire() {
        return $this->belongsToMany(AnneeScolaire::class);
    }
    /**
     * a user can have different fonction throw different shool year
     */
    public function fonctionAnneeScolaire() {
        return $this->belongsToMany(FonctionAnneeScolaireUser::class);
    }

    /**
     * get student classe
     */
    public function getClasse() {
        $classeId = ClasseAnneeScolaireStudent::all()->where('user_id', $this->id)
            ->where('annee_scolaire_id', getCurrentYear()->id)->pluck('classe_id');
        $classe = Classe::all()->whereIn('id', $classeId)->first();
        return $classe;
    }

    /**
     * A student have a class
     */
    public function classe() : BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * A personnel have a fonction
    */
    public function fonctions()
    {
        return $this->belongsToMany(Fonction::class, 'fonction_annee_scolaire_users')
            ->withPivot('annee_scolaire_id')->withTimestamps();
    }

     /**
     * A use is in a class
    */
    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'classe_annee_scolaire_students')
            ->withPivot('annee_scolaire_id')->withTimestamps();
    }

    /**
     * current year student classe
     */
    public function getCurrentYearClasse($activeYearId)
    {
        $studentYearClasseId = ClasseAnneeScolaireStudent::all()
            ->where('user_id', $this->id)
            ->where('annee_scolaire_id', $activeYearId)
            ->pluck('classe_id');
        $classe = Classe::where('id',$studentYearClasseId)->first();
        return $classe->id;
    }

    /**
     * current year student classe
     */
    public function studentCurrentClasse($activeYearId)
    {
        $studentYearClasseId = ClasseAnneeScolaireStudent::all()
            ->where('user_id', $this->id)
            ->where('annee_scolaire_id', $activeYearId)
            ->pluck('classe_id');
        $classe = Classe::where('id',$studentYearClasseId)->first();
        return $classe;
    }

    /**
     * current year student classe
     */
    public function getCurrentYearClasseName($activeYearId)
    {
        $studentYearClasseId = ClasseAnneeScolaireStudent::all()
            ->where('user_id', $this->id)
            ->where('annee_scolaire_id', $activeYearId)
            ->pluck('classe_id');
        $classe = Classe::where('id',$studentYearClasseId)->first();
        return $classe;
    }

    /**
     * A user must be in different classes throw years
     */
    public function classeAnneeScolaire() : BelongsTo
    {
        return $this->belongsTo(ClasseAnneeScolaireStudent::class, 'user_id');
    }

    /**
     * A teacher teach many subjects
     */
    public function matieres() : BelongsToMany
    {
        return $this->belongsToMany(Matiere::class, 'enseignant_matiere', 'user_id', 'matiere_id');
    }

    /**
     * a user can create many blog articles
     */
    public function actualites(): BelongsToMany {
        return $this->belongsToMany(Actualite::class);
    }

    /**
     * get all actualites create by a user
     */
    public function getActualites($currentMonth = false)
    {
        $query = Actualite::where('user_id', $this->id);

        if ($currentMonth) {
            $query->where('created_at', '>=', Carbon::now()->startOfMonth())
                  ->where('created_at', '<', Carbon::now()->endOfMonth());
        }

        return $query->get();
    }

    /**
     * get all epreuve create by a user
     */
    public function getEpreuves($currentMonth = false)
    {
        $query = Epreuve::where('user_id', $this->id);

        if ($currentMonth) {
            $query->where('created_at', '>=', Carbon::now()->startOfMonth())
                  ->where('created_at', '<', Carbon::now()->endOfMonth());
        }

        return $query->get();
    }

    /**
     * get user created notifications
     */
    public function getNotifications($currentMonth = false, $currentWeek = false) {
        $query = Notification::where('user_id', $this->id);
        if ($currentMonth) {
            $query->where('created_at', '>=', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth());
        }
        if($currentWeek) {
            $query->where('created_at', '>=', Carbon::now()->startOfWeek())
                ->where('created_at', '<', Carbon::now()->endOfWeek());
        }
        return $query;
    }

    /**
     * a teacher can upload many subjects
     */
    public function epreuves(): HasMany {
        return $this->hasMany(Epreuve::class);
    }

    /**
     * A user can have many notes
     */
    public function notes(): HasMany {
        return $this->hasMany(Note::class);
    }

    /**
     * A teacher can create many devoirs
     */
    public function devoirs():HasMany
    {
        return $this->hasMany(Devoir::class);
    }

    /**
     *  A Student can have many absences or many disciplines advices
     */
    public function disciplines():HasMany
    {
        return $this->hasMany(Discipline::class);
    }

    /**
     *  A Student can have many many disciplines advices
     */
    public function conseils_disciplines():HasMany
    {
        return $this->hasMany(ConseilDiscipline::class);
    }

    /**
     * teachers matieres for currents school years
     */
    public function teacherMatieres($activeYearId) {
        $ensMatsYearIds = EnsMatAnneeScolaire::all()->where('annee_scolaire_id', $activeYearId)
            ->pluck('enseignant_matiere_models_id');
        $ensMatsIds = EnseignantMatiereModel::all()
            ->where('user_id', $this->id)->whereIn('id', $ensMatsYearIds)->pluck('matiere_id');
        $matieres = Matiere::all()->whereIn('id', $ensMatsIds);
        return $matieres;
    }

    /**
     * student classes matieres
     */
    public function studentClasseMatiere($activeYearId, $classeId) {
        $coefYearIds = CoefAnneeScolaire::all()->where('annee_scolaire_id', $activeYearId)
            ->pluck('coefficient_id');
        $matsIds = Coefficient::all()
            ->where('classe_id', $classeId)->whereIn('id', $coefYearIds)->pluck('matiere_id');
        $matieres = Matiere::all()->whereIn('id', $matsIds);
        return $matieres;
    }

    /**
     * teachers classes
     */
    public function teacherClasses($activeYearId) {
        $enseignantMatieres = EnseignantMatiereModel::all()
            ->where('user_id', $this->id)->pluck('id');
        $enseignementIds = EnseignementAnneeScolaire::all()->where('annee_scolaire_id', $activeYearId)
            ->pluck('enseignement_id');
        $enseignantClassesIds = Enseignement::all()->whereIn('enseignant_matiere_id',$enseignantMatieres)
            ->whereIn('id', $enseignementIds)->pluck('classe_id');
        $classes = Classe::all()->whereIn('id', $enseignantClassesIds);
        return $classes;
    }

    /**
     * a user can make many NoteHistory
     */
    public function histories(): HasMany
    {
        return $this->hasMany(NoteHistory::class);
    }

    /**
     *
     */
    public function presidencies() {
        return $this->hasMany(Club::class, 'president_id');
    }

    /**
     * check if a user is a president of some club
     */
    public function isClubPresident() {
        return $this->club()->where('president_id', $this->id)->exists();
    }

    /**
     * check if a user is in the booster programme
     *
     */
    public function isBoosterStudent() {
        return $this->boosterStudent();
    }

    /**
     * a club must have one president
     */
    public function club() {
        return $this->hasOne(Club::class, 'president_id');
    }

    /**
     * a user could be in booster programme
     */
    public function boosterStudent() {
        return $this->hasOne(BoosterStudent::class, 'user_id');
    }

    /**
     * current user notifications
     */
    public function notifications() {
        $notificationIds = [];
        $receivers = Notification::all()->pluck('receivers');
        foreach ($receivers as $r) {
            if(in_array($this->id, $r)) {
                $notificationIds = Notification::whereJsonContains('receivers', $r)->pluck('id');
            }
        }
        $notifications = Notification::query()->whereIn('id', $notificationIds);
        return $notifications;
    }

    /**
     * to show notifications to the user
     */
    public function unreadNotifications() {
        $notifications = $this->notifications()->get();
        $unreadNotifications = [];
        foreach ($notifications as $notif) {
            if($notif->read_at == null) {
                array_push($unreadNotifications, $notif);
            }
        }
        return $unreadNotifications;
    }


    /**
     * Manage roles
     */
    public function isAdmin() {
        return $this->role === 'admin';
    }

    public function isTeacher() {
        return $this->typeUser === 'enseignant';
    }

    public function isPersonnel() {
        return $this->typeUser === 'personnel';
    }

    public function isStudent() {
        return $this->typeUser === 'eleve';
    }

    public function isDisciplineMaster() {
        return $this->role === 'surveillant';
    }

    /**
     * A user have devoir result
     */
    public function devoirResults() {
        return $this->hasMany(DevoirResult::class);
    }
}
