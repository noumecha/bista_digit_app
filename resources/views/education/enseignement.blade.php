<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            @if (session('deleteSuccess'))
                            <div class="row alert alert-success text-center" id="success-message">
                                {{ session('deleteSuccess') }}
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <h5 class="">Liste des Enseignants et leur classes</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer l'attribution des classes aux enseignant
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <a href="#personnelform" class="btn btn-lg btn-dark btn-primary">
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table text-secondary text-center">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            ID</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Enseignants</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Classes
                                        </th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enseignements as $enseignement)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $enseignement->id }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                @foreach ($enseignants as $enseignant)
                                                    {{ $enseignement->user_id === $enseignant->id ? $enseignant->name : ''}}
                                                @endforeach
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $enseignement->classe->libClasse }}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('enseignement.edit', $enseignement->id) }}">
                                                                <i class="fas fa-user-edit" aria-hidden="true"></i>
                                                                modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <div class="dropdown-item">
                                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                                                <form role="form" class="form" method="POST" action="{{ route('enseignement.destroy', $enseignement->id) }}" action="">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="submit" value="Supprimer">
                                                                </form>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
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
                                <div class="row alert alert-success text-center" id="success-message">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    @if (isset($enseignementToEdit))
                                        <h5 class="">
                                            Modifier l'attributation de la classe de {{ $enseignementToEdit->classe->libClasse }}
                                            pour la matière {{ $enseignementToEdit->matiere->libelleMatiere }}
                                        </h5>
                                    @else
                                        <h5 class="">
                                            Attribution des classes aux enseignants
                                        </h5>
                                    @endif
                                </div>
                            </div>
                            <form role="form" id="" class="form row" method="POST" action="{{ isset($enseignementToEdit) ? route('enseignement.update', $enseignementToEdit->id) : route('enseignement.store') }}">
                                @csrf
                                @if (isset($enseignementToEdit))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="user_id" class="form-control-label">
                                                Selectionner l'enseignant :
                                            </label>
                                            <select name="user_id" id="user_id" class="form-control">
                                            @foreach ($enseignants as $enseignant)
                                                <option value="{{ $enseignant->id }}" {{ isset($enseignementToEdit) && $enseignementToEdit->user_id == $enseignant->id ? 'selected' : '' }}>{{ $enseignant->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="classe_id" class="form-control-label">
                                                Selectionner la classe :
                                            </label>
                                            <select name="classe_id" id="classe_id" class="form-control">
                                                @foreach ($classes as $classe)
                                                    <option value="{{ $classe->id }}" {{ isset($enseignementToEdit) && $enseignementToEdit->classe_id == $classe->id ? 'selected' : '' }}>{{ $classe->libClasse }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-16">
                                        <input type="submit" value="{{ isset($enseignementToEdit) ? 'Mettre à jour' : 'Enregistrer'}}" class="btn btn-lg {{ isset($enseignementToEdit) ? 'btn-success' :  'btn-primary'}}">
                                    </div
                                </div>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>

</x-app-layout>
