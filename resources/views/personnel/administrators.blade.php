<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            @if (session('deleteSuccess'))
                                <div class="row alert alert-success text-center success-message" id="">
                                    {{ session('deleteSuccess') }}
                                </div>
                            @endif
                            @if (session('errorSuccess'))
                                <div class="row alert alert-danger text-center success-message" id="">
                                    {{ session('errorSuccess') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Liste du personnel Administratif</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer le personnel (Ajouter, Supprimer, Mettre à jour ...etc)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <button
                                        type="button"
                                        class="btn btn-lg btn-dark btn-primary text-white"
                                        data-bs-toggle="modal"
                                        data-action="create"
                                        id="add-button"
                                        data-bs-target="#create-modal"
                                    >
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                            <form class="form form-inline row mb-3 mt-3" action="{{ route('utilisateur.personnels') }}" method="get">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="search" value="{{ isset($search) ? $search : '' }}" id="search" class="form-control" placeholder="Rechercher par (nom, prenom, téléphone, email)"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <select name="funcFilter" class="form-select" id="funcFilter">
                                            <option value="">Toutes les fonctions</option>
                                            @foreach (\App\Fonction::cases() as $f)
                                                <option value="{{ $f->value }}" {{ request('funcFilter') == $f->value ? 'selected' : '' }}>{{ $f->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn spinner-submit-button mb-0 btn-lg btn-primary">
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        Rechercher
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" style="overflow-x: visible;">
                            <table class="table text-secondary text-center">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            ID</th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Photo</th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Nom</th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Téléphone</th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Fonction</th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (empty($personnels->items()))
                                        <!-- empty($personnels->items()) -->
                                        <td colspan="6" class="text">
                                            Aucune donnée disponible
                                        </td>
                                    @else
                                        @foreach ($personnels as $personnel)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $personnel->id }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <img
                                                        src="{{ isset($personnel->profile) ? asset('storage/' . $personnel->profile) : asset('front/images/logo.png') }}"
                                                        class="rounded-circle mr-2"
                                                        alt="user1" style="height: 36px; width: 36px;"
                                                    />
                                                </div>
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $personnel->name }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $personnel->phone }}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                {{ $personnel->fonction }}
                                            </td>
                                            <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                                                <a
                                                    data-bs-toggle="modal"
                                                    id="edit-button"
                                                    data-bs-target="#create-modal"
                                                    data-action="edit"
                                                    data-url="{{ route('utilisateur.personnelStore', $personnel->id) }}"
                                                    class="btn btn-primary mt-3 p-2"
                                                    href="{{ route('personnel.edit', $personnel->id) }}"
                                                >
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <button
                                                    type="button"
                                                    class="btn btn-danger ml-2 mt-3 p-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#confirmDelete-{{ $personnel->id }}"
                                                >
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                                <div onclick="showDropdown(this)" id="ddown-menu" class="ddown-menu d-flex btn btn-transparent ml-2 mt-3 p-2">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                    <div class="ddown-items-container d-none p-2 bg-dark">
                                                        <button
                                                            type="button"
                                                            class="mb-0 p-2 btn text-white"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#confirmMigrate-{{ $personnel->id }}"
                                                        >
                                                            Migrer
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="mb-0 p-2 btn text-white"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#confirmDeleteYear-{{ $personnel->id }}"
                                                            {{ $activeYear->id === $personnel->create_year_id ? 'disabled' : '' }}
                                                        >
                                                            Supprimer pour l'année
                                                        </button>
                                                        <a class="mb-0 p-2 btn text-white" href="#">
                                                            Statistiques
                                                        </a>
                                                    </div>
                                                </div>
                                                <!-- modal for migrate user to annother year -->
                                                <div class="modal fade" data-form-id="{{ $personnel->id }}" id="confirmMigrate-{{ $personnel->id }}" tabindex="-1" aria-labelledby="migrateModal" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form role="form" class="form" method="POST" action="{{ route('personnel.migrate') }}">
                                                            @csrf
                                                            <div class="modal-content p-0">
                                                                <div class="modal-header bg-dark">
                                                                    <h5 class="modal-title text-white" id="migrateModal">Confirmation de migration</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                                    </button>
                                                                </div>
                                                                <div class="alert alert-success text-center" style="display: none;" id="modal-alert-success-{{ $personnel->id }}">
                                                                </div>
                                                                <div class="alert alert-danger text-center" style="display: none;" id="modal-alert-errors-{{ $personnel->id }}">
                                                                </div>
                                                                <div class="modal-body text-wrap text-justify">
                                                                    <h5>Vous êtes sur le point d'ajouter le personnel {{ $personnel->name }} à une année ultérieure!</h5>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <input type="hidden" name="migrate_user_id" id="migrate_user_id" value="{{ $personnel->id }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="sex" class="form-control-label">
                                                                                Selectionnez l'année :
                                                                            </label>
                                                                            <select name="migrate_year_id" id="migrate_year_id" class="form-control form-select">
                                                                                <!-- option value="0">Selectionner une année</!-->
                                                                                @foreach ($migrateYears as $myear)
                                                                                    <option value="{{ $myear->id }}">{{ $myear->libelleAnneeScolaire }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer flex-row-reverse">
                                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
                                                                    <button type="button" data-personnel-id="{{ $personnel->id }}" class="spinner-submit-modal-button btn btn-dark">
                                                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                                        Confirmer
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- modal for delete user  in the current year -->
                                                <div class="modal fade" data-form-id="{{ $personnel->id }}" id="confirmDeleteYear-{{ $personnel->id }}" tabindex="-1" aria-labelledby="migrateModal" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form role="form" class="form" method="POST" action="{{ route('personnel.deleteusercurrentyear') }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="modal-content p-0">
                                                                <div class="modal-header bg-danger">
                                                                    <h5 class="modal-title text-white" id="exampleModalLabel">Suppresion de l'année scolaire</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <input type="hidden" name="delusyear_year_id" value="{{ $activeYear->id }}">
                                                                <input type="hidden" name="delusyear_user_id" value="{{ $personnel->id }}">
                                                                <div class="modal-body text-wrap text-justify">
                                                                    Voulez-vous vraiment supprimér le personnel {{ $personnel->name }}
                                                                    pour l'année {{ $activeYear->libelleAnnneeScolaire }} ?
                                                                </div>
                                                                <div class="modal-footer flex-row-reverse">
                                                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</button>
                                                                    <button type="submit" class="spinner-submit-button btn btn-danger">
                                                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                                        Confirmer
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- modal for delete confirmation -->
                                                <div class="modal fade" id="confirmDelete-{{ $personnel->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form role="form" id="delete-form" class="form" method="POST" action="{{ route('personnel.destroy', $personnel->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="modal-content p-0">
                                                                <div class="modal-header bg-danger">
                                                                    <h5 class="modal-title text-white" id="exampleModalLabel">Supprimer définitivement</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-wrap text-justify">
                                                                    Voulez-vous vraiment supprimée le personnel :
                                                                    {{ $personnel->name }} ? (Cette action est irreversible)
                                                                </div>
                                                                <div class="modal-footer flex-row-reverse">
                                                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</button>
                                                                    <button type="submit" class="btn spinner-submit-button btn-danger">
                                                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                                        Confirmer
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {{ !empty($personnels) ? $personnels->appends(request()->query())->links() : '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- create or update modal form -->
        <div class="modal fade" id="create-modal" style="z-index: 30000" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <form  enctype="multipart/form-data" role="form" id="createEditForm" class="form row" method="POST" action="{{ isset($personnelToEdit) ? route('personnel.update', $personnelToEdit->id) : route('personnel.store') }}">
                    @csrf
                    <div class="modal-content p-0">
                        <div class="modal-header" id="modal-header">
                            <div class="modal-title row">
                                <div class="col-12">
                                    @if (isset($personnelToEdit))
                                        <h5 class="text-white">Modifier les informations du personnel {{ $personnelToEdit->name }} </h5>
                                    @else
                                        <h5 class="text-white">Ajouter un nouveau membre du personnel</h5>
                                    @endif
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-success" style="display: none;" id="modal-form-alert-success">
                            </div>
                            <div class="alert alert-danger" style="display: none;" id="modal-form-alert-errors">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">
                                            Nom :
                                        </label>
                                        <input
                                            type="text" id="name" name="name"
                                            class="form-control"
                                            placeholder="Entrez le nom du personnel"
                                            value="{{old("name")}}"
                                            aria-label="Name"
                                            aria-describedby="name-addon"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="surname" class="form-control-label">
                                            Prenom :
                                        </label>
                                        <input type="text" id="surname" name="surname" class="form-control"
                                            placeholder="Entrez le prénom du personnel" value="{{old("surname")}}" aria-label="Name"
                                            aria-describedby="name-addon">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-control-label">
                                            Téléphone :
                                        </label>
                                        <input type="tel" id="phone" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}" class="form-control"
                                            placeholder="696-879-475" value="{{old("phone")}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sex" class="form-control-label">
                                            Sexe :
                                        </label>
                                        <select name="sex" id="sex" class="form-select">
                                            @foreach (\App\Sex::cases() as $sex)
                                            <option value="{{ $sex->value }}">{{ $sex->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-control-label">
                                            Email :
                                        </label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            placeholder="Entrez l'adresse email" value="{{old("email")}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lieuNaiss" class="form-control-label">
                                            Lieu de naissance :
                                        </label>
                                        <input type="text" id="lieuNaiss" name="lieuNaiss" class="form-control"
                                            placeholder="Entrez le lieu de naissance" value="{{old("lieuNaiss")}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dateNaiss" class="form-control-label">
                                            Date de naissance :
                                        </label>
                                        <input type="datetime-local" id="dateNaiss" name="dateNaiss" class="form-control"
                                        placeholder="Entrez la date de naissance" value="{{old("dateNaiss")}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="diplome1" class="form-control-label">
                                            Diplome 1 :
                                        </label>
                                        <input type="text" id="diplome1" name="diplome1" class="form-control"
                                            placeholder="Entrez le Diplôme 1" value="{{old("diplome1")}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="diplome2" class="form-control-label">
                                            Diplome 2 :
                                        </label>
                                        <input type="text" id="diplome2" name="diplome2" class="form-control"
                                            placeholder="Entrez le Diplôme 2 " value="{{old("diplome2")}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="numCni" class="form-control-label">
                                            Numero CNI :
                                        </label>
                                        <input type="text" id="numCni" name="numCni" class="form-control"
                                            placeholder="Entrez le lieu de résidence" value="{{old("numCni")}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <img src="" id="profile-image" alt="Profile Image"
                                                style="max-width: 150px; max-height: 150px; display: block; margin-bottom: 10px;">
                                        </div>
                                        <label for="profile" class="form-control-label">
                                            Photo :
                                        </label>
                                        <input type="file" id="profile" name="profile" class="form-control"
                                            placeholder="Entrez le lieu de résidence" value="{{ old("profile") }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location" class="form-control-label">
                                            Lieu de résidence :
                                        </label>
                                        <input type="text" id="location" name="location" class="form-control"
                                            placeholder="Entrez le lieu de résidence" value="{{ old("location") }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fonction" class="form-control-label">
                                            Fonction :
                                        </label>
                                        <select name="fonction" id="fonction" class="form-select">
                                            @foreach (\App\Fonction::cases() as $f)
                                                <option value="{{ $f->value }}">{{ $f->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-control-label">
                                            Mot de passe générer :
                                        </label>
                                        <input type="text" id="password" name="password" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="active_year_id" class="form-control-label d-none">
                                            Anneé :
                                        </label>
                                        <input type="hidden" class="form-control" id="active_year_id" name="active_year_id" value="{{ $activeYear->id }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex-row-reverse">
                            <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" id="submit-form-button" class="spinner-submit-form-button btn btn-lg">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            @if (session('success'))
                                <div class="row alert alert-success text-center success-message" id="">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    @if (isset($personnelToEdit))
                                        <h5 class="">Modifier les informations du personnel {{ $personnelToEdit->name }} </h5>
                                    @else
                                        <h5 class="">Ajouter un nouveau membre du personnel</h5>
                                    @endif
                                </div>
                            </div>
                            <form  enctype="multipart/form-data" role="form" id="personnelform" class="form row" method="POST" action="{{ isset($personnelToEdit) ? route('personnel.update', $personnelToEdit->id) : route('personnel.store') }}">
                                @csrf
                                @if(isset($personnelToEdit))
                                    @method('PUT')
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-control-label">
                                                Nom :
                                            </label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                placeholder="Entrez le nom du personnel" value="{{ isset($personnelToEdit) ? $personnelToEdit->name : old("name") }}" aria-label="Name"
                                                aria-describedby="name-addon">
                                            @error('name')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="surname" class="form-control-label">
                                                Prenom :
                                            </label>
                                            <input type="text" id="surname" name="surname" class="form-control"
                                                placeholder="Entrez le prénom du personnel" value="{{isset($personnelToEdit) ? $personnelToEdit->surname : old("surname")}}" aria-label="Name"
                                                aria-describedby="name-addon">
                                            @error('surname')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-control-label">
                                                Téléphone :
                                            </label>
                                            <input type="tel" id="phone" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}" class="form-control"
                                                placeholder="696-879-475" value="{{isset($personnelToEdit) ? $personnelToEdit->phone : old("phone")}}">
                                            @error('phone')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sex" class="form-control-label">
                                                Sexe :
                                            </label>
                                            <select name="sex" id="sex" class="form-control">
                                                @foreach (\App\Sex::cases() as $sex)
                                                <option value="{{ $sex->value }}" {{ isset($personnelToEdit) && $personnelToEdit->sex === $sex->value ? 'selected' : '' }}>{{ $sex->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-control-label">
                                                Email :
                                            </label>
                                            <input type="email" id="email" name="email" class="form-control"
                                                placeholder="Entrez l'adresse email" value="{{isset($personnelToEdit) ? $personnelToEdit->email : old("email")}}">
                                            @error('email')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lieuNaiss" class="form-control-label">
                                                Lieu de naissance :
                                            </label>
                                            <input type="text" id="lieuNaiss" name="lieuNaiss" class="form-control"
                                                placeholder="Entrez le lieu de naissance" value="{{isset($personnelToEdit) ? $personnelToEdit->lieuNaiss : old("lieuNaiss")}}">
                                            @error('lieuNaiss')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dateNaiss" class="form-control-label">
                                                Date de naissance :
                                            </label>
                                            <input type="date" id="dateNaiss" name="dateNaiss" class="form-control"
                                            placeholder="Entrez la date de naissance" value="{{ old("dateNaiss" , isset($personnelToEdit) ? \Carbon\Carbon::parse($personnelToEdit->dateNaiss)->format('Y-m-d') : '') }}">
                                            @error('dateNaiss')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="diplome1" class="form-control-label">
                                                Diplome 1 :
                                            </label>
                                            <input type="text" id="diplome1" name="diplome1" class="form-control"
                                                placeholder="Entrez le Diplôme 1" value="{{isset($personnelToEdit) ? $personnelToEdit->diplome1 : old("diplome1")}}">
                                            @error('diplome1')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="diplome2" class="form-control-label">
                                                Diplome 2 :
                                            </label>
                                            <input type="text" id="diplome2" name="diplome2" class="form-control"
                                                placeholder="Entrez le Diplôme 2 " value="{{isset($personnelToEdit) ? $personnelToEdit->diplome2 : old("diplome2")}}">
                                            @error('diplome2')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="numCni" class="form-control-label">
                                                Numero CNI :
                                            </label>
                                            <input type="text" id="numCni" name="numCni" class="form-control"
                                                placeholder="Entrez le lieu de résidence" value="{{isset($personnelToEdit) ? $personnelToEdit->numCni : old("numCni")}}">
                                            @error('numCni')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="profile" class="form-control-label">
                                                Photo :
                                            </label>
                                            <input type="file" id="profile" name="profile" class="form-control"
                                                placeholder="Entrez le lieu de résidence" value="{{ old("profile") }}">
                                            @error('profile')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="location" class="form-control-label">
                                                Lieu de résidence :
                                            </label>
                                            <input type="text" id="location" name="location" class="form-control"
                                                placeholder="Entrez le lieu de résidence" value="{{isset($personnelToEdit) ? $personnelToEdit->location : old("location")}}">
                                            @error('location')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fonction" class="form-control-label">
                                                Fonction :
                                            </label>
                                            <select name="fonction" id="fonction" class="form-select">
                                                @foreach (\App\Fonction::cases() as $f)
                                                    <option value="{{ $f->value }}" {{ isset($personnelToEdit) && $personnelToEdit->fonction === $f->value ? 'selected' : '' }}>{{ $f->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="form-control-label">
                                                Mot de passe générer :
                                            </label>
                                            <input type="text" id="password" name="password" class="form-control">
                                            @error('password')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="active_year_id" class="form-control-label d-none">
                                                Anneé :
                                            </label>
                                            <input type="hidden" class="form-control" id="active_year_id" name="active_year_id" value="{{ $activeYear->id }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-16">
                                        <button type="submit" class="spinner-submit-button btn btn-lg {{ isset($personnelToEdit) ? 'btn-success' : 'btn-primary' }}">
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            {{ isset($personnelToEdit) ? 'Mettre à jour' : 'Enregistrer' }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>

</x-app-layout>
