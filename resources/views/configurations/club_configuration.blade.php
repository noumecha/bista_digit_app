<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
            <div class="pt-7 pb-6 bg-cover"
                style="background-image: url('{{
                    isset($clubconfiguration) && isset($clubconfiguration->club_image) ?
                    asset('storage/' . $clubconfiguration->club_image) :
                    asset('img/header-orange-purple.jpg')
                }}'); background-position: bottom;">
            </div>
            <div class="container">
                <div class="card card-body py-2 bg-transparent shadow-none">
                    <div class="row">
                        <div class="col-auto my-auto">
                            <div class="h-100">
                                <h3 class="mb-0 text-uppercase font-weight-bold">
                                    CLUB :
                                    @if (isset($clubconfiguration) && isset($clubconfiguration->club_name))
                                        {{ $clubconfiguration->club_name }}
                                    @else
                                        Non défini
                                    @endif
                                </h3>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                            <a
                                type="button"
                                class="btn btn-lg btn-dark btn-primary text-white"
                                data-bs-toggle="modal"
                                data-action="create"
                                data-clubconfiguration-id = "{{ isset($clubconfiguration) ? $clubconfiguration->id : ''}}"
                                data-bs-target="#update-clubconfiguration-modal"
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
                                        <h6 class="mb-0 font-weight-semibold text-lg">Informations sur le club</h6>
                                        <p class="text-sm mb-1">Modifier les informations du club</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="mb-0 font-weight-semibold text-lg">Description du club</h6>
                                @if (isset($clubconfiguration) && isset($clubconfiguration->contenu))
                                    {!! Str::limit($clubconfiguration->contenu , $limit=300, $end="...") !!}
                                @else
                                    <p class="text-sm mb-4">aucune description ...</p>
                                @endif
                                <ul class="list-group">
                                    <li
                                        class="list-group-item border-0 ps-0 text-dark font-weight-semibold pt-0 pb-1 text-sm">
                                        <span class="text-secondary">Nom du club :</span>
                                        @if (isset($clubconfiguration) && isset($clubconfiguration->club_name))
                                            {{ $clubconfiguration->club_name }}
                                        @else
                                            Non défini
                                        @endif
                                    </li>
                                    <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                        <span class="text-secondary">Président :</span>
                                        @if (isset($clubconfiguration) && isset($clubconfiguration->president))
                                            {{ $clubconfiguration->president->name }}
                                            {{  $clubconfiguration->president->surname}} -
                                            {{ $clubconfiguration->president->getCurrentYearClasseName(getCurrentYear()->id)->libClasse }}
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
        <!-- modal for creating or updating a clubconfiguration datas -->
        <div class="modal fade" id="update-clubconfiguration-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="clubconfigurationForm" class="form row">
                    @csrf
                    <input
                        type="hidden"
                        name="clubconfigurationId"
                        id="clubconfigurationId"
                        value=""
                    />
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-clubconfiguration-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-clubconfiguration-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="club_name" class="form-control-label">
                                            Modifier le nom du club :
                                        </label>
                                        <input type="text" name="club_name" id="club_name"
                                            class="form-control" placeholder="nouveau nom pour le club">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="club_image" class="form-control-label">
                                            Modifier l'image de mise en avant
                                        </label>
                                        <input type="file" id="club_image" name="club_image" class="form-control"
                                            value="{{ old("club_image") }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="content" class="form-control-label">
                                            Modifier la description du club :
                                        </label>
                                        <textarea
                                            name="content"
                                            id="content"
                                            placeholder="ajouter une nouvelle description"
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
                            <button type="button" id="submit-clubconfiguration-form-button" class="spinner-submit-clubconfiguration-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-clubconfiguration-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/club-configuration.js') }}"></script>
    @endsection
</x-app-layout>
