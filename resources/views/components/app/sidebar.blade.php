<aside class="sidenav navbar overflow-hidden navbar-vertical navbar-expand-xs border-0 bg-slate-900 fixed-start " id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand d-flex align-items-center m-0" href="{{ route('dashboard') }}">
            <span class="font-weight-bold text-lg">BISTA-SCHOOL</span>
        </a>
    </div>
    <div class="collapse navbar-collapse px-4 overflow-hidden w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link  {{ is_current_route('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-home fa-sm"></i>
                    <span class="nav-link-text text-md ml-n5">Acceuil</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link  {{ is_current_route('annee_scolaire.show') ? 'active' : '' }}"
                    href="{{ route('annee_scolaire.show') }}">
                    <i class="fa-solid fa-school fa-sm"></i>
                    <span class="nav-link-text text-md ml-n5">Année</span>
                </a>
            </li>
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
            <ul class="navbar-nav submenu">
                <li data-submenu="utilisateurs" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-users fa-sm"></i>
                        <span class="font-weight-normal text-md ml-n5">Utilisateurs</span>
                    </div>
                </li>
                <li data-submenu="utilisateurs" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('utilisateur.administrators') ? 'active' : '' }}"
                        href="{{ route('utilisateur.administrators') }}">
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
            </ul>
            <!-- Education on Dashboard -->
            <ul class="navbar-nav submenu">
                <li data-submenu="education" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-book fa-sm"></i>
                        <span class="font-weight-normal text-md ml-n5">Education</span>
                    </div>
                </li>
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
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.devoir') ? 'active' : '' }}"
                        href="{{ route('education.devoir') }}">
                        <span class="nav-link-text ms-1">Devoirs</span>
                    </a>
                </li>
                <li data-submenu="education" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.discipline') ? 'active' : '' }}"
                        href="{{ route('education.discipline') }}">
                        <span class="nav-link-text ms-1">Discipline</span>
                    </a>
                </li>
            </ul>
            <!-- Evaluation on Dashboard -->
            <ul class="navbar-nav submenu">
                <li data-submenu="evaluations" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-user-graduate fa-sm"></i>
                        <span class="font-weight-normal text-md ml-n5">Evaluation</span>
                    </div>
                </li>
                <li data-submenu="evaluations" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.evaluations') ? 'active' : '' }}"
                        href="{{ route('evaluation.evaluations') }}">
                        <span class="nav-link-text ms-1">Nouvelle Evaluation</span>
                    </a>
                </li>
                <li data-submenu="evaluations" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.trimestres') ? 'active' : '' }}"
                        href="{{ route('evaluation.trimestres') }}">
                        <span class="nav-link-text ms-1">Trimestres</span>
                    </a>
                </li>
                <li data-submenu="evaluations" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.remplissages') ? 'active' : '' }}"
                        href="{{ route('evaluation.remplissages') }}">
                        <span class="nav-link-text ms-1">Remplissage des notes</span>
                    </a>
                </li>
                <li data-submenu="evaluations" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.notes') ? 'active' : '' }}"
                        href="{{ route('evaluation.notes') }}">
                        <span class="nav-link-text ms-1">Notes</span>
                    </a>
                </li>
                <li data-submenu="evaluations" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.bulletins') ? 'active' : '' }}"
                        href="{{ route('evaluation.bulletins') }}">
                        <span class="nav-link-text ms-1">Bulletins</span>
                    </a>
                </li>
            </ul>
            <!-- Epreuve on Dashboard -->
            <ul class="navbar-nav submenu">
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
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.epreuve') ? 'active' : '' }}"
                        href="{{ route('education.epreuves') }}">
                        <span class="nav-link-text ms-1">Ajouter des épreuves</span>
                    </a>
                </li>
            </ul>
            <!-- Programme I'am a leader on Dashboard -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ is_current_route('programme.leader') ? 'active' : '' }}"
                        href="{{ route('programme.leader') }}">
                        <i class="fa-solid fa-gear fa-sm"></i>
                        <span class="nav-link-text text-md ml-n5">I'AM A LEADER</span>
                    </a>
                </li>
            </ul>
            <!-- Programmes on Dashboard -->
            <ul class="navbar-nav submenu">
                <li data-submenu="programme-booster" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span class="font-weight-normal text-md ml-n5">Programme Booster</span>
                    </div>
                </li>
                <li data-submenu="programme-booster" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('programme.booster') ? 'active' : '' }}"
                        href="{{ route('programme.booster') }}">
                        <span class="nav-link-text ms-1">Evaluations</span>
                    </a>
                </li>
                <li data-submenu="programme-booster" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('programme.leader') ? 'active' : '' }}"
                        href="{{ route('programme.leader') }}">
                        <span class="nav-link-text ms-1">Notes</span>
                    </a>
                </li>
            </ul>
            <!-- Notifications on Dashboard -->
            <ul class="navbar-nav submenu">
                <li data-submenu="notifications" class="submenu-click-link nav-item mt-2">
                    <div class="d-flex align-items-center nav-link">
                        <i class="fa-solid fa-message"></i>
                        <span class="font-weight-normal text-md ml-n5">Notifications</span>
                    </div>
                </li>
                <li data-submenu="notifications" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('programme.booster') ? 'active' : '' }}"
                        href="{{ route('programme.booster') }}">
                        <span class="nav-link-text ms-1">Nouvelle Notification</span>
                    </a>
                </li>
                <li data-submenu="notifications" class="submenu-click-item nav-item border-start my-0 pt-2">
                    <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('programme.leader') ? 'active' : '' }}"
                        href="{{ route('programme.leader') }}">
                        <span class="nav-link-text ms-1">Type de Notification</span>
                    </a>
                </li>
            </ul>
            <!-- Profile on Dashboard -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link  {{ is_current_route('dashboard') ? 'active' : '' }}"
                        href="{{ route('users.profile') }}">
                        <i class="fa-solid fa-gear fa-sm"></i>
                        <span class="nav-link-text text-md ml-n5">Configuration du profil</span>
                    </a>
                </li>
            </ul>
        </ul>
    </div>
</aside>
