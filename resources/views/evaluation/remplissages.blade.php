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
                                    <h5 class="">Liste des Remplissages de notes configurés</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les configurations de remplissage de notes(Ajouter, Supprimer, Mettre à jour)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <a href="#remplissageForm" class="btn btn-lg btn-dark btn-primary">
                                        <i class="fas fa-user-plus me-2"></i> Ajouter
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="table text-secondary text-center">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Date debut
                                        </th>
                                        <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Date fin
                                        </th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Evaluation
                                        </th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Statut
                                        </th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($remplissages as $remplissage)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $remplissage->date_debut }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $remplissage->date_fin }}
                                            </td>
                                            <td class="align-middle bg-transparent borer-bottom">
                                                {{ $remplissage->evaluation->libelleEvaluation }}
                                            </td>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $remplissage->statut }}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('evaluation.remplissagesEdit', $remplissage->id) }}">
                                                                <i class="fas fa-user-edit" aria-hidden="true"></i>
                                                                modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <div class="dropdown-item">
                                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                                                <form role="form" class="form" method="POST" action="{{ route('evaluation.remplissagesDestroy', $remplissage->id) }}">
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
                            <div class="d-flex justify-content-center">
                                {{ $remplissages->appends(request()->query())->links() }}
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
                                <div class="row alert alert-success text-center" id="success-message">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    @if (isset($remplissageToEdit))
                                        <h5 class="">Modifier le remplissage {{ $remplissageToEdit->id }} pour l'évaluation {{ $remplissageToEdit->evaluation->libelleEvaluation  }} </h5>
                                    @else
                                        <h5 class="">Définir une nouvelle période de remplissage des notes</h5>
                                    @endif
                                </div>
                            </div>
                            <form enctype="multipart/form-data" role="form" id="remplissageForm" class="form row" method="POST" action="{{ isset($remplissageToEdit) ? route('evaluation.remplissagesUpdate', $remplissageToEdit->id) : route('evaluation.remplissagesStore') }}">
                                @csrf
                                @if (isset($remplissageToEdit))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_debut" class="form-control-label">
                                                Date de debut du remplissage :
                                            </label>
                                            <input type="date" id="date_debut" name="date_debut" class="form-control"
                                                placeholder="Entrez la date de la date debut de remplissage de note" value="{{ old("date_debut" , isset($remplissageToEdit) ? \Carbon\Carbon::parse($remplissageToEdit->date_debut)->format('Y-m-d') : '') }}">
                                            @error('date_debut')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_fin" class="form-control-label">
                                                Date de fin du remplissage :
                                            </label>
                                            <input type="date" id="date_fin" name="date_fin" class="form-control"
                                                placeholder="Entrez la date de la date fin de remplissage de note" value="{{ old("date_fin" , isset($remplissageToEdit) ? \Carbon\Carbon::parse($remplissageToEdit->date_fin)->format('Y-m-d') : '') }}">
                                            @error('date_fin')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="evaluation_id" class="form-control-label">
                                                Evaluation :
                                            </label>
                                            <select name="evaluation_id" id="evaluation_id" class="form-select">
                                                @foreach ($evaluations as $evaluation)
                                                    <option value="{{ $evaluation->id }}" {{ isset($remplissageToEdit) && $remplissageToEdit->evaluation_id === $evaluation->id ? 'selected' : '' }}>
                                                        {{ $evaluation->libelleEvaluation}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('evaluation_id')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="statut" class="form-control-label">
                                                Statut :
                                            </label>
                                            <select name="statut" id="statut" class="form-select">
                                                <option value="activé" {{ isset($remplissageToEdit) && $remplissageToEdit->statut === 'activé' ? 'selected' : '' }}>activé</option>
                                                <option value="désactivé" {{ isset($remplissageToEdit) && $remplissageToEdit->statut === 'désactivé' ? 'selected' : '' }}>désactivé</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-16">
                                        <input type="submit" value="{{ isset($remplissageToEdit) ? 'Mettre à jour' : 'Enregistrer'}}" class="btn btn-lg {{ isset($remplissageToEdit) ? 'btn-success' :  'btn-primary'}}">
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
