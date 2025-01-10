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
                            <form class="form form-inline row mt-3" action="{{ route('utilisateur.students') }}" method="get">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="searchStudent" value="{{ isset($searchStudent) ? $searchStudent : '' }}" id="searchStudent" class="form-control" placeholder="Rechercher une actulaité (titre ou contenu)"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <select name="classFilter" class="form-select" id="">
                                            <option value="">Toutes les classes</option>
                                            @foreach ($classes as $classe)
                                                <option value="{{$classe->id}}" {{ request('classFilter') == $classe->id ? 'selected' : '' }}>
                                                    {{ $classe->libClasse}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" onclick="showSpinner(this)" class="btn btn-lg btn-primary">
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        Rechercher
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive" id="studentsTable" style="overflow-x: visible;">
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
                                                Téléphone (Parent) :
                                            </label>
                                            <input type="tel" id="phone" name="phone" class="form-control" pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}"
                                                placeholder="696-879-475" value="{{ isset($studentToEdit) ? $studentToEdit->phone : old("phone") }}">
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
                                            <select name="sex" id="sex" class="form-select">
                                                @foreach (\App\Sex::cases() as $sex)
                                                    <option value="{{ $sex->value }}" {{ isset($studentToEdit) && $studentToEdit->sex === $sex->value ? 'selected' : '' }}>{{ $sex->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="classe_id" class="form-control-label">
                                                Classe :
                                            </label>
                                            <select name="classe_id" id="classe_id" class="form-select">
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
                                        <button type="submit" onclick="showSpinner(this)" class="btn btn-lg {{ isset($studentToEdit) ? 'btn-success' : 'btn-primary' }}">
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            {{ isset($studentToEdit) ? 'Mettre à jour' : 'Enregistrer' }}
                                        </button>
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
