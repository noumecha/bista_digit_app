<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="py-4 container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @if (session('deleteSuccess'))
                                <div class="row alert alert-success text-center success-message" id="">
                                    {{ session('deleteSuccess') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Envoyer de nouvelles notifications</h5>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-notification-modal"
                                    >
                                        <i class="fa-solid fa-newspaper me-2"></i> Nouvelle notification
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" id="filterNotificationForm">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" name="searchNotification" id="searchNotification"
                                            class="form-control" placeholder="Rechercher une notification (titre ou message)"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <select name="typeFilter" id="typeFilter" class="form-select">
                                            <option value="">Filtrer par type</option>
                                            <option disabled value="sms">SMS</option>
                                            <option value="email">Email</option>
                                            <option disabled value="whatsapp">WhatsApp</option>
                                            <option value="in_app">Notification simple</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mt-3">
                                        <select name="groupFilter" class="form-select" id="groupFilter">
                                            <option value="">Filtrer par groupe</option>
                                            <option value="eleve">Élèves</option>
                                            <option value="enseignant">Enseignants</option>
                                            <option value="personnel">Personnel</option>
                                            <option value="all">Tout le monde</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="notificationsTable">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal for creation new notifications -->
        <div class="modal fade" id="create-notification-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-xl">
                <form enctype="multipart/form-data" role="form" id="notificationForm" class="form row">
                    @csrf
                    <input type="hidden" name="notificationId" id="notificationId" value="">
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-notification-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    <h5 id="header-notification-text" class="text-white"></h5>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title" class="form-control-label">
                                            Titre :
                                        </label>
                                        <input
                                            type="text"
                                            id="title"
                                            name="title"
                                            class="form-control"
                                            placeholder="Entrez le titre de la notification"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="message" class="form-control-label">
                                        Contenu :
                                    </label>
                                    <textarea
                                        name="message"
                                        id="message"
                                        class="form-control"
                                        placeholder="Entrez le message de la notification"
                                        cols="12"
                                        rows="15">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="type">
                                        Type de notification
                                    </label>
                                    <select name="type" id="type" class="form-select">
                                        <option value="">Choisir le type</option>
                                        <option disabled value="sms">SMS</option>
                                        <option value="email">Email</option>
                                        <option disabled value="whatsapp">WhatsApp</option>
                                        <option value="in_app">Notification simple</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="target_group">
                                        Cible
                                    </label>
                                    <select name="target_group" class="form-select" id="target_group">
                                        <option value="">Choisir la cible</option>
                                        <option value="eleve">Élèves</option>
                                        <option value="enseignant">Enseignants</option>
                                        <option value="personnel">Personnel</option>
                                        <option value="all">Tout le monde</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group" id="receiverSelector">
                                    <label for="receiver_ids" class="form-control-label">
                                        Choisir les utilisateurs :
                                    </label>
                                    <select class="col-md-12 receiver_ids" id="receiver_ids"
                                        name="receiver_ids[]" multiple>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox"
                                            name="send_to_all" id="sendToAll">
                                        <label class="form-check-label" for="sendToAll">
                                            Envoyer à tous les membres de la cible sélectionnée
                                        </label>
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
                            <button type="button" id="submit-notification-form-button" class="spinner-submit-notification-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                <span id="submit-notification-form-button-text"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
        <script src="{{ asset('js/functions/notifications.js') }}"></script>
    @endsection
</x-app-layout>
