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
                                    <h5 class="">Liste des Matières</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les matières (Ajouter, Supprimer, Mettre à jour ...etc)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <a href="#personnelform" class="btn btn-lg btn-dark btn-primary">
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </a>
                                </div>
                            </div>
                        </div>
                        @if (session('deleteSuccess'))
                            <div class="row alert alert-success text-center" id="success-message">
                                {{ session('deleteSuccess') }}
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table text-secondary text-center">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            ID</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Libellé</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Code
                                        </th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($matieres as $mat)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $mat->id }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $mat->libelleMatiere }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $mat->codeMatiere }}
                                            </td>
                                            <td class="
                                                align-middle
                                                d-flex
                                                align-items-center
                                                justify-content-center
                                                bg-transparent
                                                list-unstyled
                                                border-bottom"
                                            >
                                                <li>
                                                    <a class="btn btn-secondary p-2 mb-0" href="{{ route('matiere.edit', $mat->id) }}">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <form role="form" class="ml-2 form" method="POST" action="{{ route('matiere.destroy', $mat->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            id="delete-matiere-button"
                                                            type="submit"
                                                            class="delete-note btn btn-danger p-2 mb-0"
                                                        >
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </li>
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
                                    @if (isset($matiereToEdit))
                                        <h5 class="">Modifier la maitère de {{ $matiereToEdit->libelleMatiere }} </h5>
                                    @else
                                        <h5 class="">Ajouter une nouvelle Matière</h5>
                                    @endif
                                </div>
                            </div>
                            <form  enctype="multipart/form-data" role="form" id="personnelform" class="form row" method="POST" action="{{ isset($matiereToEdit) ? route('matiere.update', $matiereToEdit->id) : route('matiere.store') }}">
                                @csrf
                                @if (isset($matiereToEdit))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="libelleMatiere" class="form-control-label">
                                                Libellé :
                                            </label>
                                            <input type="text" id="libelleMatiere" name="libelleMatiere" class="form-control"
                                                placeholder="Entrez le libellé de la matière"
                                                value="{{ isset($matiereToEdit) ? $matiereToEdit->libelleMatiere : old("libelleMatiere") }}">
                                            @error('libelleMatiere')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="codeMatiere" class="form-control-label">
                                                Code :
                                            </label>
                                            <input
                                                type="text"
                                                id="codeMatiere"
                                                name="codeMatiere"
                                                class="form-control"
                                                placeholder="Entrez le nom du personnel"
                                                value="{{ isset($matiereToEdit) ? $matiereToEdit->codeMatiere : old("codeMatiere") }}">
                                            @error('codeMatiere')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-16">
                                        <input type="submit" value="{{ isset($matiereToEdit) ? 'Mettre à jour' : 'Enregistrer'}}" class="btn btn-lg {{ isset($matiereToEdit) ? 'btn-success' :  'btn-primary'}}">
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
