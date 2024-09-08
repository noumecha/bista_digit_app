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
                                    <h5 class="">Liste du personnel Enseignant</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les enseignants (Ajouter, Supprimer, Mettre à jour ...etc)
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
                                            Photo</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Nom</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Email</th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Fonction</th>
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
                                    @foreach ($teachers as $teacher)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $teacher>id }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <img src="{{ asset('storage/' . $teacher>profile) }}" class="rounded-circle mr-2"
                                                        alt="user1" style="height: 36px; width: 36px;">
                                                </div>
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $teacher>name }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $teacher>email }}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                {{ $teacher>fonction }}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                {{ $teacher>numCni }}
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
                                    <h5 class="">Ajouter un nouvel enseignant </h5>
                                </div>
                            </div>
                            <form  enctype="multipart/form-data" role="form" id="personnelform" class="form row" method="POST" action="{{ route('teacher.store') }}">
                                @csrf
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="matricule" class="form-control-label">
                                            Matricule :
                                        </label>
                                        <input type="text" id="matricule" name="matricule" class="form-control"
                                            placeholder="Entrez le matricule" value="{{ old("matricule") }}">
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
                                            placeholder="Entrez le nom du personnel" value="{{old("name")}}" aria-label="Name"
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
                                            placeholder="Entrez le prénom du personnel" value="{{old("surname")}}" aria-label="Name"
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
                                            placeholder="Entrez le numero de téléphone" value="{{old("phone")}}">
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
                                                <option value="{{ $sex->value }}">{{ $sex->name }}</option>
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
                                            placeholder="Entrez l'adresse email" value="{{old("email")}}">
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
                                            placeholder="Entrez le lieu de naissance" value="{{old("lieuNaiss")}}">
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
                                            placeholder="Entrez la date de naissance" value="{{old("dateNaiss")}}">
                                        @error('dateNaiss')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="diplome1" class="form-control-label">
                                            Diplome 1 :
                                        </label>
                                        <input type="text" id="diplome1" name="diplome1" class="form-control"
                                            placeholder="Entrez le Diplôme 1" value="{{old("diplome1")}}">
                                        @error('diplome1')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="diplome2" class="form-control-label">
                                            Diplome 2 :
                                        </label>
                                        <input type="text" id="diplome2" name="diplome2" class="form-control"
                                            placeholder="Entrez le Diplôme 2 " value="{{old("diplome2")}}">
                                        @error('diplome2')
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
                                            placeholder="Entrez le lieu de résidence" value="{{old("numCni")}}">
                                        @error('numCni')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="profile" class="form-control-label">
                                            Photo :
                                        </label>
                                        <input type="file" id="profile" name="profile" class="form-control"
                                            placeholder="Entrez le lieu de résidence" value="{{old("profile")}}">
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
                                            placeholder="Entrez le lieu de résidence" value="{{old("location")}}">
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
