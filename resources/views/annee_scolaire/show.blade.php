<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Liste des années scolaires</h5>
                                    <p class="text-sm">
                                        liste des années scolaire gérées dans l'application
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <a href="#schoolyearform" class="btn btn-lg btn-dark btn-primary">
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">
                                @if (session('listSuccess'))
                                    <div class="alert alert-success success-message" role="alert" id="">
                                        {{ session('listSuccess') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table text-secondary text-center">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            ID
                                        </th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Libellé
                                        </th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Statut
                                        </th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($years as $year)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $year->id }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $year->libelleAnneeScolaire }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                <span class="badge rounded-pill {{ $year->statut ? 'bg-success' : 'bg-danger'}}">
                                                    {{ $year->statut ? 'Activé' : 'Désactivé'}}
                                                </span>
                                            </td>
                                            <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                                                <a class="btn btn-primary mt-3 p-2" href="{{ route('annee_scolaire.edit', $year->id) }}">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger ml-2 mt-3 p-2" data-bs-toggle="modal" data-bs-target="#confirmDelete-{{ $year->id }}">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                                <form role="form" class="activation-form" method="POST" action="{{ !$year->statut ? route('annee_scolaire.activate', $year->id) : route('annee_scolaire.desactivate', $year->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" onclick="showSpinner(this)" class="btn {{ !$year->statut ? 'btn-success' : 'btn-danger'}} mt-3 p-2">
                                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                        {{ !$year->statut ? 'Activer' : 'Désactiver' }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <!-- modal for delete confirmation -->
                                        <div class="modal fade" id="confirmDelete-{{ $year->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Année scolaire : {{ $year->libelleAnneeScolaire }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Faut-il vraiment supprimé l'année scolaire {{ $year->libelleAnneeScolaire }}
                                                        avec toutes ses données ? (Cette action est irreversible)
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Annuler</button>
                                                        <form role="form" class="form" method="POST" action="{{ route('annee_scolaire.destroy', $year->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="showSpinner(this)" class="btn btn-success">
                                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                                Confirmer
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            @if (session('success'))
                                <div class="row alert alert-success text-center success-message">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <!--div class="toast-block" id="toast-block">
                                <div id="liveToast" class="toast-card bg-success text-center text-white">
                                    <button type="button" class="toast-close" id="toast-close">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    <div class="toast-body">
                                    </div>
                                </div>
                            </div -->
                            <div class="row">
                                <div class="col-md-6">
                                    @if (isset($yearToEdit))
                                        <h5 class="">Modifier l'année scolaire : {{ $yearToEdit->libelleAnneeScolaire }}</h5>
                                    @else
                                        <h5 class="">Ajouter une nouvelle année scolaire</h5>
                                    @endif
                                </div>
                            </div>
                            <form  enctype="multipart/form-data" role="form" id="schoolyearform" class="form row" method="POST" action="{{ isset($yearToEdit) ? route('annee_scolaire.update', $yearToEdit->id) : route('annee_scolaire.store') }}">
                                @csrf
                                @if (isset($yearToEdit))
                                    @method('PUT')
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger success-message">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <!--div class="col-md-8 col-lg-8"-->
                                    <div class="input-group input-group-lg mb-3 {{ $errors->has('libelleAnneeScolaire') ? 'has-danger' : '' }}">
                                        <input type="text" id="libelleAnneeScolaire" name="libelleAnneeScolaire"
                                            class="col-md-8 col-lg-8 form-control {{ $errors->has('libelleAnneeScolaire') ? 'is-invalid' : '' }}"
                                            placeholder="{{ $errors->has('libelleAnneeScolaire') ? $errors->first('libelleAnneeScolaire') : 'exemple : 2024/2025' }}"
                                            value="{{ isset($yearToEdit) ? $yearToEdit->libelleAnneeScolaire : old("libelleAnneeScolaire")}}"
                                            >
                                        <button type="submit" onclick="showSpinner(this)" class="col-md-4 col-lg-4 btn mb-0 btn-lg {{ isset($yearToEdit) ? 'btn-outline-success' : 'btn-outline-primary' }}">
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            {{ isset($yearToEdit) ? 'Mettre à jour' : 'Enregistrer' }}
                                        </button>
                                    </div>
                                <!--/div-->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>

</x-app-layout>
