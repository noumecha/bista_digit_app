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
                                            <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                                                <a class="btn btn-primary mt-3 p-2" href="{{ route('categorie.edit', $categorie->id) }}">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger ml-2 mt-3 p-2" data-bs-toggle="modal" data-bs-target="#confirmDelete-{{ $categorie->id }}">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- modal for delete confirmation -->
                                        <div class="modal fade" id="confirmDelete-{{ $categorie->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Confirmation de suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Voulez-vous vraiment supprimée la catégorie d'actualité :
                                                        {{ $categorie->libelleCategorie }} (Cette action est irreversible)
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Annuler</button>
                                                        <form role="form" class="form" method="POST" action="{{ route('categorie.destroy', $categorie->id) }}">
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
                                <div class="row alert alert-success text-center success-message" id="">
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
