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
                                    @foreach ($enseignants as $enseignant)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $enseignant->id }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $enseignant->name }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                @foreach ($enseignant->classes as $classes)
                                                    {{ $classe->libelleClasse }},
                                                @endforeach
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                <!--div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item primary" href="#">
                                                            <i class="bx bx-edit-alt me-1"></i>Modifier
                                                        </a>
                                                        <a href="#" class="dropdown-item btn-success" data-toggle="modal" data-target="#exampleModal">
                                                            <i class="bx bx-trash me-1"></i>Supprimer
                                                        </a>
                                                    </div>
                                                </div-->
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="fas fa-user-edit" aria-hidden="true"></i>
                                                                modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <div class="dropdown-item">
                                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                                                <form role="form" class="form" method="POST" action="">
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
                                        Attribution des classes aux enseignants
                                    </h5>
                                </div>
                            </div>
                            <form role="form" id="" class="form row" method="POST" action="{{ route('enseignement.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="enseignant_id" class="form-control-label">
                                                Selectionner l'enseignant :
                                            </label>
                                            <select name="enseignant_id" id="enseignant_id" class="form-control">
                                            @foreach ($enseignants as $enseignant)
                                                <option value="{{ $enseignant->id }}" class="">{{ $enseignant->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="classes" class="form-control-label">
                                                Selectionner les classes :
                                            </label>
                                            <select name="classes[]" id="classes" class="form-control" multiple>
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->id }}" class="">{{ $classe->libelleClasse }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-16">
                                        <input type="submit" value="Enregistrer" class="btn btn-lg btn-primary">
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
