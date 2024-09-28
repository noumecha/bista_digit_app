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
                                    <h5 class="">Liste des Catégories d'actualités</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les categories(Ajouter, Supprimer, Mettre à jour ...etc)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <a href="#personnelform" class="btn btn-lg btn-dark btn-primary">
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </a>
                                </div>
                            </div>
                            <form class="form form-inline row mt-3" action="{{ route('actualites.categories') }}" method="get">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="search" value="{{ isset($search) ? $search : '' }}" id="search" class="form-control" placeholder="Rechercher une catégorie d'actulaité"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-lg btn-primary" type="submit">Rechercher</button>
                                </div>
                            </form>
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
                                            Libellé
                                        </th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $categorie)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $categorie->id }}
                                            </td>
                                            <td class="align-middle bg-transparent borer-bottom">
                                                {{ $categorie->libelleCategorie }}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('categorie.edit', $categorie->id) }}">
                                                                <i class="fas fa-user-edit" aria-hidden="true"></i>
                                                                modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <div class="dropdown-item">
                                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                                                <form role="form" class="form" method="POST" action="{{ route('categorie.destroy', $categorie->id) }}">
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
                            <div class="d-flex justify-content-center">
                                {{ $categories->appends(request()->query())->links() }}
                            </div>
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
                                    @if (isset($categorieToEdit))
                                        <h5 class="">Modifier la Catégoire{{ $categorieToEdit->libelleCategorie}} </h5>
                                    @else
                                        <h5 class="">Ajouter une nouvelle Categorie d'Actaulité</h5>
                                    @endif
                                </div>
                            </div>
                            <form enctype="multipart/form-data" role="form" id="personnelform" class="form row" method="POST" action="{{ isset($categorieToEdit) ? route('categorie.update', $categorieToEdit->id) : route('categorie.store') }}">
                                @csrf
                                @if (isset($categorieToEdit))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="libelleCategorie" class="form-control-label">
                                                Libellé :
                                            </label>
                                            <input
                                                type="text"
                                                id="libelleCategorie"
                                                name="libelleCategorie"
                                                class="form-control"
                                                placeholder="Entrez le libellé de la categorie"
                                                value="{{ isset($categorieToEdit) ? $categorieToEdit->libelleCategorie : old("libelleCategorie") }}"
                                            />
                                            @error('libelleCategorie')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-16">
                                        <input type="submit" value="{{ isset($categorieToEdit) ? 'Mettre à jour' : 'Enregistrer'}}" class="btn btn-lg {{ isset($categorieToEdit) ? 'btn-success' :  'btn-primary'}}">
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
