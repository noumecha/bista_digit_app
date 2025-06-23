<aside class="sidenav navbar overflow-y-auto navbar-vertical navbar-expand-xs border-0
    bg-slate-900 fixed-start overflow-x-hidden" id="sidenav-main">
    <div class="sidenav-header position-sticky top-0 bg-slate-900 z-index-2">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute e
            nd-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand d-flex align-items-center m-0" href="{{ route('dashboard') }}">
            <span class="font-weight-bold text-lg">POWEREDUCATION</span>
        </a>
    </div>
    <div class="collapse navbar-collapse px-4 overflow-y-auto hidden w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link  {{ is_current_route('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-home fa-sm"></i>
                    <span class="nav-link-text text-md ml-n5">Acceuil</span>
                </a>
            </li>
            @can('access-admin')
            <li class="nav-item">
                <a class="nav-link  {{ is_current_route('anneescolaire.years') ? 'active' : '' }}"
                    href="{{ route('anneescolaire.years') }}">
                    <i class="fa-solid fa-school fa-sm"></i>
                    <span class="nav-link-text text-md ml-n5">Année</span>
                </a>
            </li>
            @endcan
            @can('access-personnel')
            <ul class="navbar-nav submenu">
                <li data-submenu="actualites" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-newspaper"></i>
                        <span class="font-weight-normal text-md ml-n5">Actualités</span>
                    </div>
                </li>
                <li data-submenu="actualites" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('actualites.index') ? 'active' : '' }}"
                        href="{{ route('actualites.index') }}">
                        <span class="nav-link-text ms-1">Nouvelle actualité</span>
                    </a>
                </li>
                <li data-submenu="actualites" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('actualites.categories') ? 'active' : '' }}"
                        href="{{ route('actualites.categories') }}">
                        <span class="nav-link-text ms-1">Catégorie d'actualité</span>
                    </a>
                </li>
            </ul>
            @endcan
            @can('access-admin')
            <ul class="navbar-nav submenu">
                <li data-submenu="utilisateurs" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-users fa-sm"></i>
                        <span class="font-weight-normal text-md ml-n5">Utilisateurs</span>
                    </div>
                </li>
                <li data-submenu="utilisateurs" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('utilisateur.fonctions') ? 'active' : '' }}"
                        href="{{ route('utilisateur.fonctions') }}">
                        <span class="nav-link-text ms-1">Fonctions</span>
                    </a>
                </li>
                <li data-submenu="utilisateurs" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('utilisateur.personnels') ? 'active' : '' }}"
                        href="{{ route('utilisateur.personnels') }}">
                        <span class="nav-link-text ms-1">Personnel Administratif</span>
                    </a>
                </li>
                <li data-submenu="utilisateurs" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('utilisateur.teachers') ? 'active' : '' }}"
                        href="{{ route('utilisateur.teachers') }}">
                        <span class="nav-link-text ms-1">Personnel Enseignant</span>
                    </a>
                </li>
                <li data-submenu="utilisateurs" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('utilisateur.students') ? 'active' : '' }}"
                        href="{{ route('utilisateur.students') }}">
                        <span class="nav-link-text ms-1">Elèves</span>
                    </a>
                </li>
                <li data-submenu="utilisateurs" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('utilisateur.roles') ? 'active' : '' }}"
                        href="{{ route('utilisateur.roles') }}">
                        <span class="nav-link-text ms-1">Rôles</span>
                    </a>
                </li>
            </ul>
            @endcan
            <!-- Education on Dashboard -->
            <ul class="navbar-nav submenu">
                @can('access-education')
                <li data-submenu="education" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-book fa-sm"></i>
                        <span class="font-weight-normal text-md ml-n5">Education</span>
                    </div>
                </li>
                @endcan
                @can('access-admin')
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.enseignantMatiere') ? 'active' : '' }}"
                        href="{{ route('education.enseignantMatiere') }}">
                        <span class="nav-link-text ms-1">Attributions de matières</span>
                    </a>
                </li>
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.enseignement') ? 'active' : '' }}"
                        href="{{ route('education.enseignement') }}">
                        <span class="nav-link-text ms-1">Enseignement</span>
                    </a>
                </li>
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.enseignantprincipal') ? 'active' : '' }}"
                        href="{{ route('education.enseignantprincipal') }}">
                        <span class="nav-link-text ms-1">Enseignant(e) Principal(e)</span>
                    </a>
                </li>
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.sections') ? 'active' : '' }}"
                        href="{{ route('education.sections') }}">
                        <span class="nav-link-text ms-1">Sections</span>
                    </a>
                </li>
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.classes') ? 'active' : '' }}"
                        href="{{ route('education.classes') }}">
                        <span class="nav-link-text ms-1">Classes</span>
                    </a>
                </li>
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.matiere') ? 'active' : '' }}"
                        href="{{ route('education.matiere') }}">
                        <span class="nav-link-text ms-1">Matieres</span>
                    </a>
                </li>
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.coefficients') ? 'active' : '' }}"
                        href="{{ route('education.coefficients') }}">
                        <span class="nav-link-text ms-1">Configuration de matière</span>
                    </a>
                </li>
                @endcan
                @can('access-devoirs')
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.devoirs') ? 'active' : '' }}"
                        href="{{ route('education.devoirs') }}">
                        <span class="nav-link-text ms-1">Devoirs</span>
                    </a>
                </li>
                @endcan
                @can('access-teacher')
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.questions') ? 'active' : '' }}"
                        href="{{ route('education.questions') }}">
                        <span class="nav-link-text ms-1">Questions</span>
                    </a>
                </li>
                @endcan
                @can('access-student-devoir')
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('student.discipline') ? 'active' : '' }}"
                        href="{{ route('student.discipline') }}">
                        <span class="nav-link-text ms-1">Etat disciplinaire</span>
                    </a>
                </li>
                @endcan
                @can('access-discipline')
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.discipline') ? 'active' : '' }}"
                        href="{{ route('education.discipline') }}">
                        <span class="nav-link-text ms-1">Discipline</span>
                    </a>
                </li>
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.conseildiscipline') ? 'active' : '' }}"
                        href="{{ route('education.conseildiscipline') }}">
                        <span class="nav-link-text ms-1">Conseils de discipline</span>
                    </a>
                </li>
                @endcan
            </ul>
            <!-- Evaluation on Dashboard -->
            <ul class="navbar-nav submenu">
                @can('access-devoirs')
                <li data-submenu="evaluations" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-user-graduate fa-sm"></i>
                        <span class="font-weight-normal text-md ml-n5">Evaluation</span>
                    </div>
                </li>
                @endcan
                @can('access-admin')
                <li data-submenu="evaluations" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.trimestres') ? 'active' : '' }}"
                        href="{{ route('evaluation.trimestres') }}">
                        <span class="nav-link-text ms-1">Trimestres</span>
                    </a>
                </li>
                <li data-submenu="evaluations" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.evaluations') ? 'active' : '' }}"
                        href="{{ route('evaluation.evaluations') }}">
                        <span class="nav-link-text ms-1">Evaluations</span>
                    </a>
                </li>
                <li data-submenu="evaluations" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.remplissages') ? 'active' : '' }}"
                        href="{{ route('evaluation.remplissages') }}">
                        <span class="nav-link-text ms-1">Configuration remplissage</span>
                    </a>
                </li>
                <li data-submenu="evaluations" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.notes_controles') ? 'active' : '' }}"
                        href="{{ route('evaluation.notes_controles') }}">
                        <span class="nav-link-text ms-1">Contrôle du remplissage</span>
                    </a>
                </li>
                <li data-submenu="evaluations" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.notes_modifications') ? 'active' : '' }}"
                        href="{{ route('evaluation.notes_modifications') }}">
                        <span class="nav-link-text ms-1">Modifications(notes)</span>
                    </a>
                </li>
                @endcan
                @can('access-teacher')
                <li data-submenu="evaluations" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.notes') ? 'active' : '' }}"
                        href="{{ route('evaluation.notes') }}">
                        <span class="nav-link-text ms-1">Notes</span>
                    </a>
                </li>
                @endcan
                @can('access-student')
                <li data-submenu="evaluations" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('student.notes') ? 'active' : '' }}"
                        href="{{ route('student.notes') }}">
                        <span class="nav-link-text ms-1">Mes notes</span>
                    </a>
                </li>
                @endcan
            </ul>
            <!-- Bulletins on Dashboard -->
            @can('access-admin')
            <ul class="navbar-nav submenu">
                <li data-submenu="bulletins" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-book-open"></i>
                        <span class="font-weight-normal text-md ml-n5">Bulletins</span>
                    </div>
                </li>
                <li data-submenu="bulletins" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('bulletins.list') ? 'active' : '' }}"
                        href="{{ route('bulletins.list') }}">
                        <span class="nav-link-text ms-1">Liste des bulletins</span>
                    </a>
                </li>
            </ul>
            @endcan
            <!-- Epreuve on Dashboard -->
            <ul class="navbar-nav submenu">
                @can('access-epreuves')
                <li data-submenu="epreuves" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-book-open"></i>
                        <span class="font-weight-normal text-md ml-n5">Epreuves</span>
                    </div>
                </li>
                <li data-submenu="epreuves" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.type_epreuves') ? 'active' : '' }}"
                        href="{{ route('education.type_epreuves') }}">
                        <span class="nav-link-text ms-1">Type d'Epreuve</span>
                    </a>
                </li>
                <li data-submenu="epreuves" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.epreuves') ? 'active' : '' }}"
                        href="{{ route('education.epreuves') }}">
                        <span class="nav-link-text ms-1">Ajouter des épreuves</span>
                    </a>
                </li>
                @endcan
            </ul>
            <!-- Programme I'am a leader on Dashboard -->
            @can('access-admin')
            <ul class="navbar-nav submenu">
                <li data-submenu="leaders" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-book-open"></i>
                        <span class="font-weight-normal text-md ml-n5">I'am a Leader</span>
                    </div>
                </li>
                <li data-submenu="leaders" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('actualites.index') ? 'active' : '' }}"
                        href="{{ route('actualites.index') }}">
                        <span class="nav-link-text ms-1">Actualités</span>
                    </a>
                </li>
                <li data-submenu="leaders" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('page_configuration.leader.index') ? 'active' : '' }}"
                        href="{{ route('page_configuration.leader.index') }}">
                        <span class="nav-link-text ms-1">Configuration de page</span>
                    </a>
                </li>
            </ul>
            @endcan
            <!-- Programmes on Dashboard -->
            <ul class="navbar-nav submenu">
                @can('access-devoirs')
                <li data-submenu="programme-booster" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span class="font-weight-normal text-md ml-n5">Programme Booster</span>
                    </div>
                </li>
                @endcan
                @can('access-admin')
                <li data-submenu="programme-booster" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('booster.evaluations') ? 'active' : '' }}"
                        href="{{ route('booster.evaluations') }}">
                        <span class="nav-link-text ms-1">Evaluations</span>
                    </a>
                </li>
                <li data-submenu="programme-booster" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('booster.classes') ? 'active' : '' }}"
                        href="{{ route('booster.classes') }}">
                        <span class="nav-link-text ms-1">Classes</span>
                    </a>
                </li>
                @endcan
                @can('access-teacher')
                <li data-submenu="programme-booster" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('booster.notes') ? 'active' : '' }}"
                        href="{{ route('booster.notes') }}">
                        <span class="nav-link-text ms-1">Notes</span>
                    </a>
                </li>
                @endcan
                @can('access-student-devoir')
                <li data-submenu="programme-booster" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('student.boosternotes') ? 'active' : '' }}"
                        href="{{ route('student.boosternotes') }}">
                        <span class="nav-link-text ms-1">Mes notes</span>
                    </a>
                </li>
                @endcan
                @can('access-admin')
                <li data-submenu="programme-booster" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('booster.notes_controles') ? 'active' : '' }}"
                        href="{{ route('booster.notes_controles') }}">
                        <span class="nav-link-text ms-1">Remplissage</span>
                    </a>
                </li>
                <li data-submenu="programme-booster" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('booster.notes_modifications') ? 'active' : '' }}"
                        href="{{ route('booster.notes_modifications') }}">
                        <span class="nav-link-text ms-1">Modifications(notes)</span>
                    </a>
                </li>
                <li data-submenu="programme-booster" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('booster.matieres') ? 'active' : '' }}"
                        href="{{ route('booster.matieres') }}">
                        <span class="nav-link-text ms-1">Matieres</span>
                    </a>
                </li>
                <li data-submenu="programme-booster" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('booster.teachers') ? 'active' : '' }}"
                        href="{{ route('booster.teachers') }}">
                        <span class="nav-link-text ms-1">Enseignants</span>
                    </a>
                </li>
                <li data-submenu="programme-booster" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('booster.students') ? 'active' : '' }}"
                        href="{{ route('booster.students') }}">
                        <span class="nav-link-text ms-1">Eleves</span>
                    </a>
                </li>
                <li data-submenu="programme-booster" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('page_configuration.booster.index') ? 'active' : '' }}"
                        href="{{ route('page_configuration.booster.index') }}">
                        <span class="nav-link-text ms-1">Configuration page</span>
                    </a>
                </li>
                @endcan
            </ul>
            <!-- Notifications on Dashboard -->
            <ul class="navbar-nav submenu">
                <li data-submenu="notifications" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-message"></i>
                        <span class="font-weight-normal text-md ml-n5">Notifications</span>
                    </div>
                </li>
                @can('access-personnel')
                <li data-submenu="notifications" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('notification.create') ? 'active' : '' }}"
                        href="{{ route('notification.create') }}">
                        <span class="nav-link-text ms-1">Nouvelle Notification</span>
                    </a>
                </li>
                @endcan
                @can('access-admin')
                <li data-submenu="notifications" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('notification.controles') ? 'active' : '' }}"
                        href="{{ route('notification.controles') }}">
                        <span class="nav-link-text ms-1">Contrôles</span>
                    </a>
                </li>
                @endcan
                <li data-submenu="notifications" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('notification.index') ? 'active' : '' }}"
                        href="{{ route('notification.index') }}">
                        <span class="nav-link-text ms-1">Mes notifications</span>
                    </a>
                </li>
            </ul>
            <!-- Clubs -->
            <ul class="navbar-nav submenu">
                @can('access-student-club')
                <li data-submenu="clubs" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-kaaba"></i>
                        <span class="font-weight-normal text-md ml-n5">Clubs</span>
                    </div>
                </li>
                @endcan
                @can('access-admin')
                <li data-submenu="clubs" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('clubs.index') ? 'active' : '' }}"
                        href="{{ route('clubs.index') }}">
                        <span class="nav-link-text ms-1">Liste des clubs</span>
                    </a>
                </li>
                @endcan
                @can('access-actus')
                <li data-submenu="clubs" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('actualites.index') ? 'active' : '' }}"
                        href="{{ route('actualites.index') }}">
                        <span class="nav-link-text ms-1">Articles</span>
                    </a>
                </li>
                @endcan
                @can('access-club')
                <li data-submenu="clubs" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('club_configuration.index') ? 'active' : '' }}"
                        href="{{ route('club_configuration.index') }}">
                        <span class="nav-link-text ms-1">Configuration</span>
                    </a>
                </li>
                @endcan
            </ul>
            <!-- App configuration menu -->
            @can('access-admin')
            <ul class="navbar-nav submenu">
                <li data-submenu="app_configuration" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-gear fa-sm"></i>
                        <span class="font-weight-normal text-md ml-n5">Configuration globale</span>
                    </div>
                </li>
                <li data-submenu="app_configuration" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('app_configuration.index') ? 'active' : '' }}"
                        href="{{ route('app_configuration.index') }}">
                        <span class="nav-link-text ms-1">Informations génériques</span>
                    </a>
                </li>
                <li data-submenu="app_configuration" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('sliders.index') ? 'active' : '' }}"
                        href="{{ route('sliders.index') }}">
                        <span class="nav-link-text ms-1">Slider</span>
                    </a>
                </li>
                <li data-submenu="app_configuration" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('specialite.index') ? 'active' : '' }}"
                        href="{{ route('specialite.index') }}">
                        <span class="nav-link-text ms-1">Atouts</span>
                    </a>
                </li>
            </ul>
            @endcan
            <!-- Profile on Dashboard -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link  {{ is_current_route('profile.index') ? 'active' : '' }}"
                        href="{{ route('profile.index') }}">
                        <i class="fa-solid fa-user fa-sm"></i>
                        <span class="nav-link-text text-md ml-n5">Configuration du profil</span>
                    </a>
                </li>
            </ul>
            <!-- Statistiques on dashboard -->
            @can('access-admin')
            <ul class="navbar-nav submenu">
                <li data-submenu="statistics" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-chart-line"></i>
                        <span class="font-weight-normal text-md ml-n5">Statistiques</span>
                    </div>
                </li>
                <li data-submenu="statistics" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('statistics.index') ? 'active' : '' }}"
                        href="{{ route('statistics.index') }}">
                        <span class="nav-link-text ms-1">Trimestres</span>
                    </a>
                </li>
                <li data-submenu="statistics" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('statistics.obc') ? 'active' : '' }}"
                        href="{{ route('statistics.obc') }}">
                        <span class="nav-link-text ms-1">Classement OBC</span>
                    </a>
                </li>
            </ul>
            @endcan
        </ul>
    </div>
</aside>
