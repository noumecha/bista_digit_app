<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
            <div class="pt-7 pb-6 bg-cover"
                style="background-image: url('{{ asset('img/header-blue-purple.jpg') }}'); background-position: bottom;">
            </div>
            <div class="container">
                <div class="card card-body py-2 bg-transparent shadow-none">
                    <div class="row">
                        <div class="col-auto">
                            <div
                                class="overflow-hidden avatar avatar-2xl bg-white rounded-circle position-relative mt-n7 border border-2 border-dark">
                                <img
                                    @if (isset($user) && isset($user->profile))
                                        src="{{ asset('storage/' . $user->profile) }}"
                                    @else
                                        src="{{ asset('img/default-avatar.png') }}"
                                    @endif
                                    alt="school_logo" class="w-100"
                                />
                            </div>
                        </div>
                        <div class="col-auto my-auto">
                            <div class="h-100">
                                <h3 class="mb-0 font-weight-bold">
                                    @if (isset($user) && isset($user->name))
                                        {{ $user->name }}
                                    @else
                                        Non défini
                                    @endif
                                </h3>
                                <p class="mb-0">
                                    @if (isset($user) && isset($user->surname))
                                        {{ $user->surname }}
                                    @else
                                        Non défini
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                            <a
                                type="button"
                                class="btn btn-lg btn-dark btn-primary text-white"
                                data-bs-toggle="modal"
                                data-action="create"
                                data-userconfiguration-id = "{{ isset($user) ? $user->id : ''}}"
                                data-bs-target="#update-userpassword-modal"
                                class="btn btn-sm btn-white"
                            >
                                <i class="fas fa-lock me-2"></i> Mot de passe
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                            <a
                                type="button"
                                class="btn btn-lg btn-dark btn-primary text-white"
                                data-bs-toggle="modal"
                                data-action="create"
                                data-userconfiguration-id = "{{ isset($user) ? $user->id : ''}}"
                                data-bs-target="#update-userconfiguration-modal"
                                class="btn btn-sm btn-white"
                            >
                                <i class="fas fa-pen me-2"></i> Modifier
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container my-3 py-3">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4">
                        <div class="card border shadow-xs h-100">
                            <div class="card-header pb-0 p-3">
                                <div class="row">
                                    <div class="col-md-8 col-9">
                                        <h6 class="mb-0 font-weight-semibold text-lg">Vos informations</h6>
                                        <p class="text-sm mb-1">Modifier vos informations</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="mb-0 font-weight-semibold text-lg">A propos de vous</h6>
                                <p class="text-sm mb-4">aucune description ...</p>
                                <ul class="list-group">
                                    <li
                                        class="list-group-item border-0 ps-0 text-dark font-weight-semibold pt-0 pb-1 text-sm">
                                        <span class="text-secondary">Nom :</span>
                                        @if (isset($user) && isset($user->name))
                                            {{ $user->name }}
                                        @else
                                            Non défini
                                        @endif
                                    </li>
                                    <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                        <span class="text-secondary">Prénom :</span>
                                        @if (isset($user) && isset($user->surname))
                                            {{ $user->surname}}
                                        @else
                                            Non défini
                                        @endif
                                    </li>
                                    <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                        <span class="text-secondary">Contact :</span>
                                        @if (isset($user) && isset($user->phone))
                                            {{ $user->phone }}
                                        @else
                                            Non défini
                                        @endif
                                    </li>
                                    <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                        <span class="text-secondary">Email :</span>
                                        @if (isset($user) && isset($user->email))
                                            {{ $user->email }}
                                        @else
                                            Non défini
                                        @endif
                                    </li>
                                    <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                        <span class="text-secondary">Localisation :</span>
                                        @if (isset($user) && isset($user->location))
                                            {{ $user->location }}
                                        @else
                                            Non défini
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 mb-4">
                        <div class="card border shadow-xs h-100">
                            <div class="card-header pb-0 p-3">
                                <div class="row mb-sm-0 mb-2">
                                    <div class="col-md-8 col-9">
                                        <h6 class="mb-0 font-weight-semibold text-lg">Notifications</h6>
                                        <p class="text-sm mb-0">espace notifications</p>
                                    </div>
                                    <div class="col-md-4 col-3 text-end">
                                        <button type="button" class="btn btn-white btn-icon px-2 py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10.5 6a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zm0 6a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zm0 6a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3 pt-0">
                                <ul class="list-group">
                                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-1">
                                        <div class="avatar avatar-sm rounded-circle me-2">
                                            <img src="{{ asset('front/images/logo.png') }}" alt="kal" class="w-100">
                                        </div>
                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm font-weight-semibold">M. Noumecha</h6>
                                            <p class="mb-0 text-sm text-secondary">
                                                en maintennace
                                            </p>
                                        </div>
                                        <span class="p-1 bg-success rounded-circle ms-auto me-3">
                                            <span class="visually-hidden">Online</span>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- user password modal -->

        <div class="modal fade" id="update-userpassword-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="userPasswordForm" class="form row">
                    @csrf
                    <div class="modal-content p-0">
                        <div class="modal-header bg-primary bg-success">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 class="text-white">
                                        Modifier votre mot de passe
                                    </h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="password" class="form-control-label">
                                            Nouveau mot de passe :
                                        </label>
                                        <div class="mb-3" id="eye-password-container">
                                            <input autocomplete="mot-de-passe" type="password" id="password" name="password" class="form-control">
                                                <span toggle="#password" id="icon-pwd" class="fa-solid fa-eye field-icon toggle-eye"
                                                onclick="togglePasswordVisibility()"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="confirmPassword" class="form-control-label">
                                            Confirmer le mot de passe :
                                        </label>
                                        <div class="mb-3" id="eye-password-container">
                                            <input type="password" autocomplete="mot-de-passe" id="confirmPassword" name="confirmPassword" class="form-control">
                                                <span toggle="#confirmPassword" id="icon-confirm" class="fa-solid fa-eye field-icon toggle-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-userpassword-form-button"
                                class="spinner-submit-userpassword-form-button btn btn-lg btn-outline-primary btn-outline-success">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-form-button-text">
                                    Modifier
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- modal for creating or updating a userconfiguration datas -->
        <div class="modal fade" id="update-userconfiguration-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="userconfigurationForm" class="form row">
                    @csrf
                    <input
                        type="hidden"
                        name="userId"
                        id="userId"
                        value=""
                    />
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-userconfiguration-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-userconfiguration-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">
                                            Nom :
                                        </label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="POWER EDUCATION">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="surname" class="form-control-label">
                                            Prénom :
                                        </label>
                                        <input type="text" name="surname" id="surname" class="form-control"
                                            placeholder="power education">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location" class="form-control-label">
                                            Adresse :
                                        </label>
                                        <input type="text" name="location" id="location" class="form-control"
                                            placeholder="Yaoundé">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-control-label">
                                            Contact :
                                        </label>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                            pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}"
                                            placeholder="696-879-475" value="{{ old("phone") }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-control-label">
                                            Email :
                                        </label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            placeholder="noumechaivan@mail.com" value="{{ old("email") }}">
                                    </div>
                                </div>
                                <!--
                                    à gérer
                                    l'élève ne peut pas modifier sa photo de profil
                                    car elle sera utilisé dans le bulletin
                                -->
                                <!--div class="col-md-6">
                                    <div class="form-group">

                                        <label for="profile" class="form-control-label">
                                            Image de profile :
                                        </label>
                                        <input type="file" id="profile" name="profile"
                                            class="form-control"
                                            value="{ { old("profile") }}">
                                    </div>
                                </div -->
                            </div>
                            <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-userconfiguration-form-button" class="spinner-submit-userconfiguration-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-userconfiguration-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/profile-configuration.js') }}"></script>
        <script src="{{ asset('js/functions/user-password.js') }}"></script>
    @endsection
</x-app-layout>
