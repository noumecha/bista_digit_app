<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @if (session('deleteSuccess'))
                                <div class="row alert alert-success text-center success-message">
                                    {{ session('deleteSuccess') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Configuration globale de l'application</h5>
                                    <p class="text-sm">
                                        Définition des paramètres génériques de l'application (nom établissement , dévise établissement, date de création, localisation , contact etc ..)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#update-appconfiguration-modal"
                                    >
                                        <i class="fas fa-user-plus me-2"></i> Modifier
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creating or updating a appconfiguration datas -->
        <div class="modal fade" id="update-appconfiguration-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <form enctype="multipart/form-data" role="form" id="appconfigurationForm" class="form row">
                    @csrf
                    <input type="hidden" name="configurationId" id="configurationId" value="">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_name" class="form-control-label">
                                            Nom de l'établissement :
                                        </label>
                                        <input type="text" name="school_name" id="school_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_motor" class="form-control-label">
                                            Devise de l'établissement :
                                        </label>
                                        <input type="text" name="school_motor" id="school_motor" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_town" class="form-control-label">
                                            Ville :
                                        </label>
                                        <input type="text" name="school_town" id="school_town" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_location" class="form-control-label">
                                            Quartier :
                                        </label>
                                        <input type="text" name="school_location" id="school_location" class="form-control">
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
                                            placeholder="Entrez l'adresse email de l'établissement" value="{{ old("school_email") }}">
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
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="school_logo" class="form-control-label">
                                            Logo de l'établissement
                                        </label>
                                        <input type="file" id="school_logo" name="school_logo" class="form-control"
                                            placeholder="Entrez le lieu de résidence" value="{{ old("school_logo") }}">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_fin" class="form-control-label">
                                            Date de fin du appconfiguration :
                                        </label>
                                        <input type="date" id="date_fin" name="date_fin" class="form-control"
                                            placeholder="Entrez la date de la date fin de appconfiguration de note" value="{{ old("date_fin") }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="openDays" class="form-control-label">
                                            Inclure les weekends :
                                        </label>
                                        <div class="form-check">
                                            <input type="hidden" name="openDays" value="0">
                                            <input class="form-check-input" type="checkbox" name="openDays" value="1" id="openDays">
                                            <label class="custom-control-label" for="openDays">oui</label>
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
