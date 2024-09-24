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
                                    <h5 class="">Liste des matières et de leur configuration par classe</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer la configuration des matières (Ajouter, Supprimer, Mettre à jour ...etc)
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
                                            Classe</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Matière
                                        </th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Coefficient
                                        </th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Groupe
                                        </th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($coefficients as $coef)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $coef->id }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $coef->classe->libClasse }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $coef->matiere->libelleMatiere }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $coef->coefficient }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $coef->groupe_matiere }}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('coefficient.edit', $coef->id) }}">
                                                                <i class="fas fa-user-edit" aria-hidden="true"></i>
                                                                modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <div class="dropdown-item">
                                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                                                <form role="form" class="form" method="POST" action="{{ route('coefficient.destroy', $coef->id) }}">
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
                                    <h5 class="">
                                        @if (isset($coefficient))
                                            Modifier la configuration de {{ $coefficient->matiere->libelleMatiere }} en {{ $coefficient->classe->libClasse }}
                                        @else
                                            Définir la configuration de la matière pour la classe
                                        @endif
                                    </h5>
                                </div>
                            </div>
                            <form  enctype="multipart/form-data" role="form" id="personnelform" class="form row" method="POST" action="{{ isset($coefficient) ? route('coefficient.update', $coefficient->id) : route('coefficient.store') }}">
                                @csrf
                                @if (isset($coefficient))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="matiere_id" class="form-control-label">
                                                Matiere :
                                            </label>
                                            <select name="matiere_id" id="matiere_id" class="form-control">
                                            @foreach ($matieres as $mat)
                                                <option value="{{ $mat->id }}" {{ isset($coefficient) && $coefficient->matiere_id == $mat->id ? 'selected' : '' }} class="">{{ $mat->libelleMatiere }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="classe_id" class="form-control-label">
                                                Classe :
                                            </label>
                                            <select name="classe_id" id="classe_id" class="form-control">
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}" {{ isset($coefficient) && $coefficient->classe_id == $classe->id ? 'selected' : ''}} class="">{{ $classe->libClasse }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="groupe_matiere" class="form-control-label">
                                                Groupe de la matière :
                                            </label>
                                            <select name="groupe_matiere" id="groupe_matiere" class="form-control">
                                                @foreach (\App\GroupeMatiere::cases() as $groupe)
                                                    <option value="{{ $groupe->value }}" {{ isset($coefficient) && $coefficient->groupe_matiere === $groupe->value ? 'selected' : '' }}>{{ $groupe->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="coefficient" class="form-control-label">
                                                Coefficient :
                                            </label>
                                            <input type="number" id="coefficient" name="coefficient" class="form-control" value="{{ isset($coefficient) && $coefficient->coefficient ? $coefficient->coefficient : old("coefficient") }}" aria-label="Name"
                                                aria-describedby="name-addon">
                                            @error('coefficient')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-16">
                                        <input type="submit" value="{{ isset($coefficient) ? 'Mettre à jour' : 'Enregistrer' }}" class="btn btn-lg {{ isset($coefficient) ? 'btn-success' : 'btn-primary' }}">
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
