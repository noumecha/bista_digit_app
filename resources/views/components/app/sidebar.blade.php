<aside class="sidenav navbar overflow-hidden navbar-vertical navbar-expand-xs border-0 bg-slate-900 fixed-start " id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand d-flex align-items-center m-0"
            href=" https://demos.creative-tim.com/corporate-ui-dashboard/pages/dashboard.html " target="_blank">
            <span class="font-weight-bold text-lg">BISTA-GESTION</span>
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
            <li class="nav-item mt-2">
                <div class="d-flex align-items-center nav-link">
                    <i class="fa-solid fa-users fa-sm"></i>
                    <span class="font-weight-normal text-md ml-n5">Actualités</span>
                </div>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('actualites.index') ? 'active' : '' }}"
                    href="{{ route('actualites.index') }}">
                    <span class="nav-link-text ms-1">Nouvelle actualité</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('actualites.categories') ? 'active' : '' }}"
                    href="{{ route('actualites.categories') }}">
                    <span class="nav-link-text ms-1">Catégorie d'actualité</span>
                </a>
            </li>
            <li class="nav-item mt-2">
                <div class="d-flex align-items-center nav-link">
                    <i class="fa-solid fa-users fa-sm"></i>
                    <span class="font-weight-normal text-md ml-n5">Utilisateurs</span>
                </div>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('utilisateur.administrators') ? 'active' : '' }}"
                    href="{{ route('utilisateur.administrators') }}">
                    <span class="nav-link-text ms-1">Personnel Administratif</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('utilisateur.teachers') ? 'active' : '' }}"
                    href="{{ route('utilisateur.teachers') }}">
                    <span class="nav-link-text ms-1">Personnel Enseignant</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('utilisateur.students') ? 'active' : '' }}"
                    href="{{ route('utilisateur.students') }}">
                    <span class="nav-link-text ms-1">Elèves</span>
                </a>
            </li>
            <!-- Education on Dashboard -->
            <li class="nav-item mt-2">
                <div class="d-flex align-items-center nav-link">
                    <i class="fa-solid fa-book fa-sm"></i>
                    <span class="font-weight-normal text-md ml-n5">Education</span>
                </div>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.enseignantMatiere') ? 'active' : '' }}"
                    href="{{ route('education.enseignantMatiere') }}">
                    <span class="nav-link-text ms-1">Attributions de matières</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.enseignement') ? 'active' : '' }}"
                    href="{{ route('education.enseignement') }}">
                    <span class="nav-link-text ms-1">Enseignement</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.classes') ? 'active' : '' }}"
                    href="{{ route('education.classes') }}">
                    <span class="nav-link-text ms-1">Classes</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.matiere') ? 'active' : '' }}"
                    href="{{ route('education.matiere') }}">
                    <span class="nav-link-text ms-1">Matieres</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.coefficients') ? 'active' : '' }}"
                    href="{{ route('education.coefficients') }}">
                    <span class="nav-link-text ms-1">Coefficient</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.devoir') ? 'active' : '' }}"
                    href="{{ route('education.devoir') }}">
                    <span class="nav-link-text ms-1">Devoirs</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.type_epreuves') ? 'active' : '' }}"
                    href="{{ route('education.type_epreuves') }}">
                    <span class="nav-link-text ms-1">Type d'Epreuve</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.epreuve') ? 'active' : '' }}"
                    href="{{ route('education.epreuves') }}">
                    <span class="nav-link-text ms-1">Epreuves</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('education.discipline') ? 'active' : '' }}"
                    href="{{ route('education.discipline') }}">
                    <span class="nav-link-text ms-1">Discipline</span>
                </a>
            </li>
            <!-- Evaluation on Dashboard -->
            <li class="nav-item mt-2">
                <div class="d-flex align-items-center nav-link">
                    <i class="fa-solid fa-user-graduate fa-sm"></i>
                    <span class="font-weight-normal text-md ml-n5">Evaluation</span>
                </div>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.trimestres') ? 'active' : '' }}"
                    href="{{ route('evaluation.trimestres') }}">
                    <span class="nav-link-text ms-1">Trimestres</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.notes') ? 'active' : '' }}"
                    href="{{ route('evaluation.notes') }}">
                    <span class="nav-link-text ms-1">Notes</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('evaluation.bulletins') ? 'active' : '' }}"
                    href="{{ route('evaluation.bulletins') }}">
                    <span class="nav-link-text ms-1">Bulletins</span>
                </a>
            </li>
            <!-- Programmes on Dashboard -->
            <li class="nav-item mt-2">
                <div class="d-flex align-items-center nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="ms-2"
                        viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd"
                            d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-weight-normal text-md ms-2">Programmes</span>
                </div>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('programme.booster') ? 'active' : '' }}"
                    href="{{ route('programme.booster') }}">
                    <span class="nav-link-text ms-1">Programme Booster</span>
                </a>
            </li>
            <li class="nav-item border-start my-0 pt-2">
                <a class="nav-link position-relative ms-0 ps-2 py-2 {{ is_current_route('programme.leader') ? 'active' : '' }}"
                    href="{{ route('programme.leader') }}">
                    <span class="nav-link-text ms-1">I'AM A LEADER</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
