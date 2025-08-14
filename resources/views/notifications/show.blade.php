<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Toutes mes notifications</h5>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button id="mark-all-read" class="btn btn-primary">
                                        <i class="fa-solid fa-circle-check me-2"></i> Tout marquer comme lu
                                    </button>
                                </div>
                            </div>
                            <div class="alert text-wrap alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert text-wrap alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                            <form class="form form-inline row mt-3" id="filterUserNotifsForm">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="searchNotification" id="searchNotification"
                                            class="form-control" placeholder="Rechercher une notification (titre ou message)"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="statutFilter" class="form-select" id="statutFilter">
                                            <option value="">Tout les statuts</option>
                                            <option value="0">Non Lue(s)</option>
                                            <option value="1">Lue(s)</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="userNotificationsTable" style="overflow-x: visible;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
</x-app-layout>