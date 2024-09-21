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
                                    <h5 class="">Liste des Classes</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les classes (Ajouter, Supprimer, Mettre à jour ...etc)
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
                                            Libellé</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Effectif
                                        </th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Cycle
                                        </th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classes as $classe)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $classe->id }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $classe->libClasse }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $classe->effectifClasse }}
                                            </td>
                                            <td class="align-middle bg-transparent borer-bottom">
                                                {{ $classe->cycleClasse }}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('classe.edit', $classe->id) }}">
                                                                <i class="fas fa-user-edit" aria-hidden="true"></i>
                                                                modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <div class="dropdown-item">
                                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                                                <form role="form" class="form" method="POST" action="{{ route('classe.destroy', $classe->id) }}">
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
                                    @if (isset($classToEdit))
                                        <h5 class="">Modifier la classe de {{ $classToEdit->libClasse }} </h5>
                                    @else
                                        <h5 class="">Ajouter une nouvelle Classe </h5>
                                    @endif
                                </div>
                            </div>
                            <form  role="form" id="personnelform" class="form row" method="POST" action="{{ isset($classToEdit) ? route('classe.update', $classToEdit->id) : route('classe.store') }}">
                                @csrf
                                @if (isset($classToEdit))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="libClasse" class="form-control-label">
                                                Libellé :
                                            </label>
                                            <input
                                                type="text"
                                                id="libClasse"
                                                name="libClasse"
                                                class="form-control"
                                                placeholder="Entrez le libellé de la classe"
                                                value="{{ isset($classToEdit) ? $classToEdit->libClasse : old("libClasse") }}"
                                            />
                                            @error('libClasse')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="effectifClasse" class="form-control-label">
                                                Effectif :
                                            </label>
                                            <input type="number" id="effectifClasse" name="effectifClasse" class="form-control" value="{{ isset($classToEdit) && $classToEdit->effectifClasse ? $classToEdit->effectifClasse : old("effectifClasse") }}"
                                            >
                                            @error('effectifClasse')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cycleClasse" class="form-control-label">
                                                Cycle :
                                            </label>
                                            <select name="cycleClasse" id="cycleClasse" class="form-control">
                                                <option value="2nd Cycle" {{ isset($classToEdit) && $classToEdit->cycleClasse == '2nd Cycle' ? 'selected' : '' }}>2<sup>nd</sup> Cycle</option>
                                                <option value="1er Cycle" {{ isset($classToEdit) && $classToEdit->cycleClasse == '1er Cycle' ? 'selected' : '' }}>1<sup>er</sup> Cycle</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-16">
                                        <input type="submit" value="{{ isset($classToEdit) ? 'Mettre à jour' : 'Enregistrer'}}" class="btn btn-lg {{ isset($classToEdit) ? 'btn-success' :  'btn-primary'}}">
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
