<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Sex;
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
     * get the user name
     */
    public function getName() {
        return $this->name;
    }

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
     * a user can be student
     */
    public function isEleve() {
        return $this->typeUser === 'eleve';
    }

    /**
     * a user can be a teacher
     */
    public function isEnseignant() {
        return $this->typeUser === 'enseignant';
    }

    /**
     * a user can be a personnel member
     */
    public function isPersonnel() {
        return $this->typeUser === 'personnel';
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
    public function classeAnneeScolaire()
    {
        return $this->hasMany(ClasseAnneeScolaireStudent::class, 'user_id');
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
     * teachers classes
     */
    public function teacherClasses($activeYearId) {
        $enseignantMatieres = EnseignantMatiereModel::all()
            ->where('enseignant_id', $this->id)->pluck('enseignant_id');
        $ensMatYearIds = EnseignementAnneeScolaire::all()->where('annee_scolaire_id', $activeYearId)
            ->whereIn('enseignat_id', $enseignantMatieres)->pluck('enseignant_matiere_id');
        $enseignantClassesIds = Enseignement::all()->whereIn('enseignant_matiere_id',$ensMatYearIds)
            ->pluck('classe_id');
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
        return $this->clubs()->where('president_id', $this->id)->exists();
    }

    /**
     * a club must have one president
     */
    public function club() {
        return $this->hasOne(Club::class, 'president_id');
    }

    /**
     * to show notifications to the user
     */
    public function unreadNotifications() {
        return $this->notifications()->whereNull('read_at')->get();
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

    public function isStudent() {
        return $this->typeUser === 'eleve';
    }

    public function isDisciplineMaster() {
        return $this->role === 'discipline_master';
    }


}
