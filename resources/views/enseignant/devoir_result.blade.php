<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="py-4 container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h4>
                                Résultat détaillé du devoir : {{ $devoir->titre_devoir }} -
                                Elève :  {{ $student->name }}
                            </h4>
                            <a href="{{ route('devoirs.teacher.show', $devoir) }}" class="btn btn-primary">
                                <i class="fa-solid fa-arrow-left"></i> Retour
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card bg-gradient-primary text-white">
                                        <div class="card-body">
                                            <h5 class="text-white mb-0">Score</h5>
                                            <h2 class="text-white mb-0">{{ $result->score }} / {{ $result->total_questions}}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-gradient-success text-white">
                                        <div class="card-body">
                                            <h6 class="text-white mb-0">Pourcentage</h6>
                                            <h2 class="text-white mb-0">{{ number_format($result->percentage, 1) }}%</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-gradient-info text-white">
                                        <div class="card-body">
                                            <h6 class="text-white mb-0">Commencé le</h6>
                                            <h6 class="text-white mb-0">
                                                {{ $result && $result->started_at ? formatDate($result->started_at, 'd/m/Y H:i') : '-' }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-gradient-dark text-white">
                                        <div class="card-body">
                                            <h6 class="text-white mb-0">Terminé le</h6>
                                            <h6 class="text-white mb-0">
                                                {{ $result && $result->completed_at ? formatDate($result->completed_at, 'd/m/Y H:i') : '-' }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7">
                                                Question
                                            </th>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7 text-center">
                                                Points
                                            </th>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7 text-center">
                                                Statut
                                            </th>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7">
                                                Réponses
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($answersByQuestion as $answer)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{!! $answer['question']->question !!}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $answer['points'] }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-sm bg-gradient-{{ $answer['status'] ? 'success' : 'danger' }}">
                                                    {{ $answer['status'] ? 'Correct' : 'Incorrect' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($answer['question']->reponses as $reponse)
                                                    <span class="badge
                                                        @if($answer['user_answers']->contains('id', $reponse->id))
                                                            bg-gradient-{{ $reponse->status ? 'success' : 'danger' }}
                                                        @else
                                                            {{ $reponse->status ? 'border border-success text-success' : 'border border-secondary text-secondary' }}
                                                        @endif">
                                                        {{ $reponse->reponse }}
                                                        @if($reponse->status)
                                                            <i class="fas fa-check ms-1"></i>
                                                        @endif
                                                    </span>
                                                    @endforeach
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
        </div>
        <x-app.footer />
    </main>
</x-app-layout>