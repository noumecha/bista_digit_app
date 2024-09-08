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
                                            ID
                                        </th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Libellé
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
                            @if (session('success'))
                                <div class="row alert alert-success text-center" id="success-message">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="">Ajouter une nouvelle année scolaire</h5>
                                </div>
                            </div>
                            <form  enctype="multipart/form-data" role="form" id="schoolyearform" class="form row" method="POST" action="{{ route('annee_scolaire.store') }}">
                                @csrf
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="libelle" class="form-control-label">
                                            Libellé :
                                        </label>
                                        <input type="text" id="libelle" name="libelle" class="form-control"
                                            placeholder="exemple : 2024/2025" value="{{old("libelle")}}" aria-label="Name"
                                            aria-describedby="name-addon">
                                        @error('libelle')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-12">
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
