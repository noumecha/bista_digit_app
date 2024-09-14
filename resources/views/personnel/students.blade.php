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
                                    <h5 class="">Liste des Elèves</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les élèves (Ajouter, Supprimer, Mettre à jour ...etc)
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
                                            Matricule</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Photo</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Nom</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Email</th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Telephone</th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Numero CNI</th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $student->matricule }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <img src="{{ asset('storage/' . $student->profile) }}" class="rounded-circle mr-2"
                                                        alt="user1" style="height: 36px; width: 36px;">
                                                </div>
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $student->name }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $student->email }}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                {{ $student->phone }}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                {{ $student->numCni }}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('student.edit', $student->id) }}">
                                                                <i class="fas fa-user-edit" aria-hidden="true"></i>
                                                                modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <div class="dropdown-item">
                                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                                                <form role="form" class="form" method="POST" action="{{ route('student.destroy', $student->id) }}">
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
                                    @if (isset($studentToEdit))
                                        <h5 class="">Modifier les informations de l'élève {{ $studentToEdit->name }} </h5>
                                    @else
                                        <h5 class="">Ajouter un nouvel élève </h5>
                                    @endif
                                </div>
                            </div>
                            <form  enctype="multipart/form-data" role="form" id="personnelform" class="form row" method="POST" action="{{ isset($studentToEdit) ? route('student.update', $studentToEdit->id) : route('student.store') }}">
                                @csrf
                                @if (isset($studentToEdit))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="matricule" class="form-control-label">
                                                Matricule :
                                            </label>
                                            <input type="text" id="matricule" name="matricule" class="form-control"
                                                placeholder="Entrez le matricule" value="{{ isset($studentToEdit) ? $studentToEdit->matricule : old("matricule") }}">
                                            @error('matricule')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-control-label">
                                                Nom :
                                            </label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                placeholder="Entrez le nom du personnel" value="{{ isset($studentToEdit) ? $studentToEdit->name : old("name")}}" aria-label="Name"
                                                aria-describedby="name-addon">
                                            @error('name')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="surname" class="form-control-label">
                                                Prenom :
                                            </label>
                                            <input type="text" id="surname" name="surname" class="form-control"
                                                placeholder="Entrez le prénom du personnel" value="{{ isset($studentToEdit) ? $studentToEdit->surname :  old("surname") }}" aria-label="Name"
                                                aria-describedby="name-addon">
                                            @error('surname')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-control-label">
                                                Téléphone :
                                            </label>
                                            <input type="tel" id="phone" name="phone" class="form-control"
                                                placeholder="Entrez le numero de téléphone" value="{{ isset($studentToEdit) ? $studentToEdit->phone : old("phone") }}">
                                            @error('phone')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sex" class="form-control-label">
                                                Sexe :
                                            </label>
                                            <select name="sex" id="sex" class="form-control">
                                                @foreach (\App\Sex::cases() as $sex)
                                                    <option value="{{ $sex->value }}" {{ isset($studentToEdit) && $studentToEdit->sex === $sex->value ? 'selected' : '' }}>{{ $sex->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="matiere_id" class="form-control-label">
                                                Classe :
                                            </label>
                                            <select name="matiere_id" id="matiere_id" class="form-control">
                                                @foreach ($classes as $classe)
                                                    <option value="{{ $classe->id }}" {{ isset($studentToEdit) && $studentToEdit->classe_id === $classe->id ? 'selected' : '' }}>{{ $classe->libClasse }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-control-label">
                                                Email :
                                            </label>
                                            <input type="email" id="email" name="email" class="form-control"
                                                placeholder="Entrez l'adresse email" value="{{ isset($studentToEdit) ? $studentToEdit->email : old("email") }}">
                                            @error('email')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lieuNaiss" class="form-control-label">
                                                Lieu de naissance :
                                            </label>
                                            <input type="text" id="lieuNaiss" name="lieuNaiss" class="form-control"
                                                placeholder="Entrez le lieu de naissance" value="{{ isset($studentToEdit) ? $studentToEdit->lieuNaiss : old("lieuNaiss") }}">
                                            @error('lieuNaiss')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dateNaiss" class="form-control-label">
                                                Date de naissance :
                                            </label>
                                            <input type="date" id="dateNaiss" name="dateNaiss" class="form-control"
                                                placeholder="Entrez la date de naissance" value="{{ old("dateNaiss" , isset($studentToEdit) ? \Carbon\Carbon::parse($studentToEdit->dateNaiss)->format('Y-m-d') : '') }}">
                                            @error('dateNaiss')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="numCni" class="form-control-label">
                                                Numero CNI :
                                            </label>
                                            <input type="text" id="numCni" name="numCni" class="form-control"
                                                placeholder="Entrez le lieu de résidence" value="{{ isset($studentToEdit) ? $studentToEdit->numCni : old("numCni") }}">
                                            @error('numCni')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            @if(isset($studentToEdit) && $studentToEdit->profile)
                                                <div>
                                                    <img src="{{ asset('storage/' . $studentToEdit->profile) }}" alt="Profile Image"
                                                        style="max-width: 150px; max-height: 150px; display: block; margin-bottom: 10px;">
                                                </div>
                                            @endif
                                            <label for="profile" class="form-control-label">
                                                Photo :
                                            </label>
                                            <input type="file" id="profile" name="profile" class="form-control"
                                                placeholder="Entrez le lieu de résidence" value="{{ isset($studentToEdit) ? $studentToEdit->profile : old("profile") }}">
                                            @error('profile')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="location" class="form-control-label">
                                                Lieu de résidence :
                                            </label>
                                            <input type="text" id="location" name="location" class="form-control"
                                                placeholder="Entrez le lieu de résidence" value="{{ isset($studentToEdit) ? $studentToEdit->location : old("location") }}">
                                            @error('location')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="form-control-label">
                                                Mot de passe générer :
                                            </label>
                                            <input type="text" id="password" name="password" class="form-control">
                                            @error('password')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-16">
                                        <input type="submit" value="{{ isset($studentToEdit) ? 'Mettre à jour' : 'Enregistrer' }}" class="btn btn-lg {{ isset($studentToEdit) ? 'btn-success' : 'btn-primary' }}">
                                    </div>
                                    <div class="col-md-6 col-lg-16">
                                        <a href="{{ route('utilisateur.students') }}" class="btn btn-danger btn-lg">
                                            Annuler
                                        </a>
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
