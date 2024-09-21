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
                                    <h5 class="">Liste des Actualités</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les epreuves(Ajouter, Supprimer, Mettre à jour ...etc)
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
                                            Titre</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Matière
                                        </th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Classe
                                        </th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Type
                                        </th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($epreuves as $epreuve)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ Str::limit($epreuve->libelleEpreuve , $limit=10, $end="...") }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $epreuve->matiere->libelleMatiere }}
                                            </td>
                                            <td class="align-middle bg-transparent borer-bottom">
                                                {{ $epreuve->classe->libClasse }}
                                            </td>
                                            <td class="align-middle bg-transparent borer-bottom">
                                                @foreach ($typeEpreuves as $typeEpreuve )
                                                    {{ $epreuve->type_epreuve_id === $typeEpreuve->id ? $typeEpreuve->libelleTypeEpreuve : '' }}
                                                @endforeach
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('epreuve.edit', $epreuve->id) }}">
                                                                <i class="fas fa-user-edit" aria-hidden="true"></i>
                                                                modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <div class="dropdown-item">
                                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                                                <form role="form" class="form" method="POST" action="{{ route('epreuve.destroy', $epreuve->id) }}">
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
                                    @if (isset($epreuveToEdit))
                                        <h5 class="">Modifier l'actualité de {{ $epreuveToEdit->libelleEpreuve}} </h5>
                                    @else
                                        <h5 class="">Ajouter une nouvelle Actualité</h5>
                                    @endif
                                </div>
                            </div>
                            <form enctype="multipart/form-data" role="form" id="personnelform" class="form row" method="POST" action="{{ isset($epreuveToEdit) ? route('epreuve.update', $epreuveToEdit->id) : route('epreuve.store') }}">
                                @csrf
                                @if (isset($epreuveToEdit))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="libelleEpreuve" class="form-control-label">
                                                Titre:
                                            </label>
                                            <input
                                                type="text"
                                                id="libelleEpreuve"
                                                name="libelleEpreuve"
                                                class="form-control"
                                                placeholder="Entrez le titre de la epreuve"
                                                value="{{ isset($epreuveToEdit) ? $epreuveToEdit->libelleEpreuve : old("libelleEpreuve") }}"
                                            />
                                            @error('libelleEpreuve')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="anneeEpreuve" class="form-control-label">
                                                Année :
                                            </label>
                                            <input
                                                type="text"
                                                id="anneeEpreuve"
                                                name="anneeEpreuve"
                                                class="form-control"
                                                maxlength="9"
                                                placeholder="Exemple (2023/2024)"
                                                value="{{ isset($epreuveToEdit) ? $epreuveToEdit->anneeEpreuve : old("anneeEpreuve") }}"
                                            />
                                            @error('anneeEpreuve')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fichier" class="form-control-label">
                                                Fichier :
                                            </label>
                                            <input type="file" id="fichier" name="fichier" accept=".pdf,image/*" class="form-control"
                                                placeholder="Selectionner le fichier" value="">
                                            @error('fichier')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type_epreuve_id" class="form-control-label">
                                                Type d'épreuve :
                                            </label>
                                            <select name="type_epreuve_id" id="type_epreuve_id" class="form-control">
                                                @foreach ($typeEpreuves as $typeEpreuve)
                                                    <option value="{{ $typeEpreuve->id }}" {{ isset($epreuveToEdit) && $epreuveToEdit->type_epreuve_id === $typeEpreuve->id ? 'selected' : '' }}>
                                                        {{ $typeEpreuve->libelleTypeEpreuve }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('libelleEpreuve')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="matiere_id" class="form-control-label">
                                                Matière :
                                            </label>
                                            <select name="matiere_id" id="matiere_id" class="form-control">
                                                @foreach ($matieres as $matiere)
                                                    <option value="{{ $matiere->id }}" {{ isset($epreuveToEdit) && $epreuveToEdit->matiere_id === $matiere->id ? 'selected' : '' }}>
                                                        {{ $matiere->libelleMatiere }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('libelleEpreuve')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="classe_id" class="form-control-label">
                                                Classe :
                                            </label>
                                            <select name="classe_id" id="classe_id" class="form-control">
                                                @foreach ($classes as $classe)
                                                    <option value="{{ $classe->id }}" {{ isset($epreuveToEdit) && $epreuveToEdit->classe_id === $classe->id ? 'selected' : '' }}>
                                                        {{ $classe->libClasse }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('libelleEpreuve')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-16">
                                        <input type="submit" value="{{ isset($epreuveToEdit) ? 'Mettre à jour' : 'Enregistrer'}}" class="btn btn-lg {{ isset($epreuveToEdit) ? 'btn-success' :  'btn-primary'}}">
                                    </div>
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
