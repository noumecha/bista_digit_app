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
                        <div class="row justify-content-center">
                            <div class="">
                                @if (session('success'))
                                    <div class="alert alert-success" role="alert" id="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger" role="alert" id="alert">
                                        {{ session('error') }}
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
                                    @foreach ($classes as $class)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $class->id }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $class->libelleClasse }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $class->effectifClasse }}
                                            </td>
                                            <td class="align-middle bg-transparent borer-bottom">
                                                {{ $class->cycleClasse }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $class->serieClasse }}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                <a href="#"><i class="fas fa-user-edit" aria-hidden="true"></i></a>
                                                <a href="#"><i class="fas fa-trash" aria-hidden="true"></i></a>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="">Ajouter une nouvelle Classe </h5>
                                </div>
                            </div>
                            <form  enctype="multipart/form-data" role="form" id="personnelform" class="form row" method="POST" action="{{ route('mat.store') }}">
                                @csrf
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="libelleClasse" class="form-control-label">
                                            Libellé :
                                        </label>
                                        <input type="text" id="libelleClasse" name="libelleClasse" class="form-control"
                                            placeholder="Entrez le libellé de la classe" value="{{ old("libelleClasse") }}">
                                        @error('libelleClasse')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="effectifClasse" class="form-control-label">
                                            Effectif :
                                        </label>
                                        <input type="number" id="effectifClasse" name="effectifClasse" class="form-control" value="{{old("effectifClasse")}}" aria-label="Name"
                                            aria-describedby="name-addon">
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
                                        <input type="text" id="cycleClasse" name="cycleClasse" class="form-control"
                                            placeholder="Entrez le nom du personnel" value="{{old("cycleClasse")}}" aria-label="Name"
                                            aria-describedby="name-addon">
                                        @error('cycleClasse')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="serieClasse" class="form-control-label">
                                            Serie :
                                        </label>
                                        <input type="text" id="serieClasse" name="serieClasse" class="form-control"
                                            placeholder="Entrez le nom du personnel" value="{{old("serieClasse")}}" aria-label="Name"
                                            aria-describedby="name-addon">
                                        @error('serieClasse')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-16">
                                    <input type="submit" value="Enregistrer" class="btn btn-lg btn-primary">
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
