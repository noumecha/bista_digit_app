<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Résultats du devoir : {{ $devoir->titre_devoir }}</h4>
                        </div>
                        <div class="card-body">
                            <!-- Summary Section -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">Score Final</h5>
                                            <div class="display-4 text-primary">
                                                <strong>{{ $result->score ?? 0 }}</strong>
                                                <small class="text-muted">/ {{ $result->total_questions }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">Pourcentage</h5>
                                            <div class="display-4 text-primary">
                                                <strong>{{ round(($result->score / $result->total_questions) * 100, 2) }}%</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Completion Details -->
                            <div class="alert alert-info">
                                <p class="mb-1">
                                    <strong>Date de début:</strong>
                                    {{ $result && $result->started_at ? formatDate($result->started_at, 'd/m/Y H:i') : '-' }}
                                </p>
                                <p class="mb-1">
                                    <strong>Date de fin:</strong>
                                    {{ $result && $result->completed_at ? formatDate($result->completed_at, 'd/m/Y H:i') : '-' }}
                                </p>
                                <p class="mb-0">
                                    <strong>Temps passé:</strong>
                                    {{ $result && $result->started_at && $result->completed_at
                                        ? date_diff($result->started_at, $result->completed_at)->format('%i minutes')
                                        : '-' }}
                                </p>
                            </div>
                            <!-- Detailed Results -->
                            <h5 class="mt-4 mb-3 border-bottom pb-2">Détail des questions</h5>
                            @foreach($answersByQuestion as $questionId => $data)
                            <div class="question-result mb-4 p-3 border rounded {{ $data['status'] ? 'border-success bg-light-success' : 'border-danger bg-light-danger' }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">
                                        Question #{{ $loop->iteration }}
                                        <span class="badge {{ $data['status'] ? 'bg-success' : 'bg-danger' }}">
                                            {{ $data['status'] ? 'Correcte' : 'Incorrecte' }}
                                        </span>
                                        <p>
                                            {!! $data['question']->question !!}
                                        </p>
                                    </h6>
                                    <span class="badge bg-secondary">{{ $data['points'] }} point(s)</span>
                                </div>
                                <p class="fw-bold">{{ $data['question']->reponse }}</p>
                                <div class="mt-2">
                                    <p class="mb-1"><strong>Vos réponses:</strong></p>
                                    <ul class="list-group list-group-flush">
                                        @forelse($data['user_answers'] as $answer)
                                        <li class="list-group-item {{ $answer->status ? 'list-group-item-success' : 'list-group-item-danger' }}">
                                            {{ $answer->reponse }}
                                            @if($answer->status)
                                                <i class="fas fa-check-circle float-end text-success"></i>
                                            @else
                                                <i class="fas fa-times-circle float-end text-danger"></i>
                                            @endif
                                        </li>
                                        @empty
                                        <li class="list-group-item list-group-item-danger">
                                            Aucune réponse sélectionnée
                                            <i class="fas fa-times-circle float-end text-danger"></i>
                                        </li>
                                        @endforelse
                                    </ul>
                                </div>
                                @if(!$data['status'] && $data['question']->reponses->where('status', true)->count() > 0)
                                <div class="mt-2">
                                    <p class="mb-1"><strong>Réponses correctes:</strong></p>
                                    <ul class="list-group list-group-flush">
                                        @foreach($data['question']->reponses->where('status', true) as $correctAnswer)
                                        <li class="list-group-item list-group-item-success">
                                            {{ $correctAnswer->reponse }}
                                            <i class="fas fa-check-circle float-end text-success"></i>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
</x-app-layout>