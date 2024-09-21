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
                                        D'ici vous pouvez gérer les actualites(Ajouter, Supprimer, Mettre à jour ...etc)
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
                                            Image
                                        </th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Catégorie
                                        </th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Contenu
                                        </th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($actualites as $actualite)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ Str::limit($actualite->titre , $limit=5, $end="...") }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <img src="{{ asset('storage/' . $actualite->image) }}" class="rounded-circle mr-2"
                                                        alt="user1" style="height: 36px; width: 36px;">
                                                </div>
                                            </td>
                                            <td class="align-middle bg-transparent borer-bottom">
                                                @foreach ($categories as $categorie)
                                                    {{ $actualite->categorie_actualites_id === $categorie->id ? $categorie->libelleCategorie : '' }}
                                                @endforeach
                                            </td>
                                            <td class="align-middle bg-transparent borer-bottom">
                                                {!! Str::limit($actualite->contenu , $limit=10, $end="...") !!}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('actualite.edit', $actualite->id) }}">
                                                                <i class="fas fa-user-edit" aria-hidden="true"></i>
                                                                modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <div class="dropdown-item">
                                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                                                <form role="form" class="form" method="POST" action="{{ route('actualite.destroy', $actualite->id) }}">
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
                                    @if (isset($actualiteToEdit))
                                        <h5 class="">Modifier l'actualité de {{ $actualiteToEdit->titre}} </h5>
                                    @else
                                        <h5 class="">Ajouter une nouvelle Actualité</h5>
                                    @endif
                                </div>
                            </div>
                            <form enctype="multipart/form-data" role="form" id="personnelform" class="form row" method="POST" action="{{ isset($actualiteToEdit) ? route('actualite.update', $actualiteToEdit->id) : route('actualite.store') }}">
                                @csrf
                                @if (isset($actualiteToEdit))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="titre" class="form-control-label">
                                                Titre :
                                            </label>
                                            <input
                                                type="text"
                                                id="titre"
                                                name="titre"
                                                class="form-control"
                                                placeholder="Entrez le tire de la actualite"
                                                value="{{ isset($actualiteToEdit) ? $actualiteToEdit->titre : old("titre") }}"
                                            />
                                            @error('titre')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            @if(isset($actualiteToEdit) && $actualiteToEdit->image)
                                                <div>
                                                    <img src="{{ asset('storage/' . $actualiteToEdit->image) }}" alt="Profile Image"
                                                        style="max-width: 150px; max-height: 150px; display: block; margin-bottom: 10px;">
                                                </div>
                                            @endif
                                            <label for="image" class="form-control-label">
                                                Image de mise en Avant :
                                            </label>
                                            <input type="file" id="image" name="image" class="form-control"
                                                placeholder="Selectionner une image de mise en avant" value="">
                                            @error('image')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="categorie_actualites_id" class="form-control-label">
                                                Categorie :
                                            </label>
                                            <select name="categorie_actualites_id" id="categorie_actualites_id" class="form-control">
                                                @foreach ($categories as $categorie)
                                                    <option value="{{ $categorie->id }}" {{ isset($actualiteToEdit) && $actualiteToEdit->categorie_actualites_id === $categorie->id ? 'selected' : '' }}">
                                                        {{ $categorie->libelleCategorie }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('titre')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="contenu" class="form-control-label">
                                            Contenu :
                                        </label>
                                        <textarea
                                            name="contenu"
                                            id="content"
                                            placeholder="Entrez le contenu de l'actualité"
                                            cols="12"
                                            rows="30">
                                            {{ isset($actualiteToEdit) ? $actualiteToEdit->contenu : old("contenu") }}
                                        </textarea>
                                        @error('contenu')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-16">
                                        <input type="submit" value="{{ isset($actualiteToEdit) ? 'Mettre à jour' : 'Enregistrer'}}" class="btn btn-lg {{ isset($actualiteToEdit) ? 'btn-success' :  'btn-primary'}}">
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

