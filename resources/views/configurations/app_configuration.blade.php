<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
            <div class="pt-7 pb-6 bg-cover"
                style="background-image: url('{{ asset('img/image-sign-in.jpg') }}'); background-position: bottom;">
            </div>
            <div class="container">
                <div class="card card-body py-2 bg-transparent shadow-none">
                    <div class="row">
                        <div class="col-auto">
                            <div
                                class="overflow-hidden avatar avatar-2xl bg-white rounded-circle position-relative mt-n7 border border-2 border-dark">
                                <img src="{{ asset('front/images/logo.png') }}" alt="profile_image" class="w-100">
                            </div>
                        </div>
                        <div class="col-auto my-auto">
                            <div class="h-100">
                                <h3 class="mb-0 font-weight-bold">
                                    Noah Mclaren
                                </h3>
                                <p class="mb-0">
                                    noah_mclaren@mail.com
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                            <a
                                type="button"
                                class="btn btn-lg btn-dark btn-primary text-white"
                                data-bs-toggle="modal"
                                data-action="create"
                                data-bs-target="#update-appconfiguration-modal"
                                class="btn btn-sm btn-white">
                                Modifier
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
                                        <h6 class="mb-0 font-weight-semibold text-lg">Informations sur l'établissement</h6>
                                        <p class="text-sm mb-1">Modifier les informations de l'établissement</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <p class="text-sm mb-4">
                                    {!! Str::limit($appconfiguration->description , $limit=50, $end="...") !!}
                                </p>
                                <ul class="list-group">
                                    <li
                                        class="list-group-item border-0 ps-0 text-dark font-weight-semibold pt-0 pb-1 text-sm">
                                        <span class="text-secondary">Nom de l'établissement :</span> Noah
                                    </li>
                                    <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                        <span class="text-secondary">Dévise :</span> Mclaren
                                    </li>
                                    <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                        <span class="text-secondary">Telephone 1 :</span> +(44) 123 1234 123
                                    </li>
                                    <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                        <span class="text-secondary">Telephone 2 :</span> +(44) 123 1234 123
                                    </li>
                                    <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                        <span class="text-secondary">Email :</span> +(44) 123 1234 123
                                    </li>
                                    <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                        <span class="text-secondary">Code Postal :</span> Manager - Organization
                                    </li>
                                    <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                        <span class="text-secondary">Localisation :</span> [school_town] - [school_location]
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
        <!-- modal for creating or updating a appconfiguration datas -->
        <div class="modal fade" id="update-appconfiguration-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="appconfigurationForm" class="form row">
                    @csrf
                    <input type="hidden" name="appconfigurationId" id="appconfigurationId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-appconfiguration-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-appconfiguration-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="school_name" class="form-control-label">
                                            Nom de l'établissement :
                                        </label>
                                        <input type="text" name="school_name" id="school_name" class="form-control" placeholder="POWER EDUCATION">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_motor" class="form-control-label">
                                            Devise de l'établissement :
                                        </label>
                                        <input type="text" name="school_motor" id="school_motor" class="form-control" placeholder="paix-travail-patrie">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_town" class="form-control-label">
                                            Ville :
                                        </label>
                                        <input type="text" name="school_town" id="school_town" class="form-control" placeholder="Yaoundé">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_location" class="form-control-label">
                                            Quartier :
                                        </label>
                                        <input type="text" name="school_location" id="school_location" class="form-control" placeholder="Emana">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_phone_1" class="form-control-label">
                                            Contact 1 :
                                        </label>
                                        <input type="tel" id="contact_phone_1" name="contact_phone_1" class="form-control" pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}"
                                            placeholder="696-879-475" value="{{ old("contact_phone_1") }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_phone_2" class="form-control-label">
                                            Contact 2 :
                                        </label>
                                        <input type="tel" id="contact_phone_2" name="contact_phone_2" class="form-control" pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}"
                                            placeholder="696-879-475" value="{{ old("contact_phone_2") }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_email" class="form-control-label">
                                            Email :
                                        </label>
                                        <input type="email" id="school_email" name="school_email" class="form-control"
                                            placeholder="powereducation@mail.com" value="{{ old("school_email") }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_postal_box" class="form-control-label">
                                            Boite postal :
                                        </label>
                                        <input
                                            type="number"
                                            id="school_postal_box"
                                            name="school_postal_box"
                                            class="form-control"
                                            placeholder="40"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_logo" class="form-control-label">
                                            Logo de l'établissement
                                        </label>
                                        <input type="file" id="school_logo" name="school_logo" class="form-control"
                                            value="{{ old("school_logo") }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="content" class="form-control-label">
                                            Description de l'établissement :
                                        </label>
                                        <textarea
                                            name="content"
                                            id="content"
                                            placeholder="Entrez la description de l'établissement"
                                            cols="12"
                                            rows="30">
                                        </textarea>
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
                            <button type="button" id="submit-appconfiguration-form-button" class="spinner-submit-appconfiguration-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-appconfiguration-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/app-configuration.js') }}"></script>
    @endsection
</x-app-layout>
