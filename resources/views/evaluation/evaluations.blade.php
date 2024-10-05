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
                                    <h5 class="">Liste des Evaluations</h5>
                                    <p class="text-sm">
                                        D'ici vous pouvez gérer les Evaluations(Ajouter, Supprimer, Mettre à jour)
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-6 text-end">
                                    <a href="#evaluationform" class="btn btn-lg btn-dark btn-primary">
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
                                            Libelle</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Trimestre
                                        </th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($evaluations as $evaluation)
                                        <tr>
                                            <td class="align-middle bg-transparent border-bottom">
                                                {{ $evaluation->libelleEvaluation }}
                                            </td>
                                            <td class="align-middle bg-transparent borer-bottom">
                                                {{ $evaluation->trimestre->libelleTrimestre }}
                                            </td>
                                            <td class="text-center align-middle bg-transparent border-bottom">
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('evaluation.evaluationsEdit', $evaluation->id) }}">
                                                                <i class="fas fa-user-edit" aria-hidden="true"></i>
                                                                modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <div class="dropdown-item">
                                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                                                <form role="form" class="form" method="POST" action="{{ route('evaluation.evaluationsDestroy', $evaluation->id) }}">
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
                                {{ $evaluations->appends(request()->query())->links() }}
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
                                    @if (isset($evaluationToEdit))
                                        <h5 class="">Modifier l'évaluation {{ $evaluationToEdit->libelleEvaluation}} </h5>
                                    @else
                                        <h5 class="">Ajouter une nouvelle Evaluation</h5>
                                    @endif
                                </div>
                            </div>
                            <form enctype="multipart/form-data" role="form" id="evaluationform" class="form row" method="POST" action="{{ isset($evaluationToEdit) ? route('evaluation.evaluationsUpdate', $evaluationToEdit->id) : route('evaluation.evaluationsStore') }}">
                                @csrf
                                @if (isset($evaluationToEdit))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="titre" class="form-control-label">
                                                Libellé :
                                            </label>
                                            <input
                                                type="text"
                                                id="libelleEvaluation"
                                                name="libelleEvaluation"
                                                class="form-control"
                                                placeholder="Entrez le tire de la evaluation"
                                                value="{{ isset($evaluationToEdit) ? $evaluationToEdit->libelleEvaluation : old("libelleEvaluation") }}"
                                            />
                                            @error('libelleEvaluation')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="trimestre_id" class="form-control-label">
                                                Trimestre :
                                            </label>
                                            <select name="trimestre_id" id="trimestre_id" class="form-select">
                                                @foreach ($trimestres as $trimestre)
                                                    <option value="{{ $trimestre->id }}" {{ isset($evaluationToEdit) && $evaluationToEdit->trimestre_id === $trimestre->id ? 'selected' : '' }}>
                                                        {{ $trimestre->libelleTrimestre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('trimestre_id')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-16">
                                        <input type="submit" value="{{ isset($evaluationToEdit) ? 'Mettre à jour' : 'Enregistrer'}}" class="btn btn-lg {{ isset($evaluationToEdit) ? 'btn-success' :  'btn-primary'}}">
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
