<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container devoir-container">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $devoir->titre_devoir }}</h3>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar"
                             style="width: {{ ($currentQuestionNumber / $totalQuestions) * 100 }}%">
                            Question {{ $currentQuestionNumber }} of {{ $totalQuestions }}
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form id="qcmForm" action="{{ route('devoirs.answer', $devoir) }}" method="POST">
                        @csrf
                        <input type="hidden" name="question_id" value="{{ $question->id }}">

                        <div class="question-container">
                            <h4 class="question-text">{{ $question->question }}</h4>
                            <p class="text-muted">Points: {{ $question->points }}</p>

                            <div class="answers-container">
                                @foreach($question->reponses as $reponse)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="answers[]"
                                           id="answer_{{ $reponse->id }}"
                                           value="{{ $reponse->id }}">
                                    <label class="form-check-label" for="answer_{{ $reponse->id }}">
                                        {{ $reponse->reponse }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="navigation-buttons mt-4">
                            @if($currentQuestionNumber > 1)
                                <a href="{{ route('devoirs.take', [$devoir, $currentQuestionNumber - 1]) }}"
                                   class="btn btn-secondary">Précédent</a>
                            @endif

                            @if($currentQuestionNumber < $totalQuestions)
                                <button type="submit" class="btn btn-primary">Suivant</button>
                            @else
                                <button type="submit" name="finish" value="1" class="btn btn-success">
                                    Terminer le devoir
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
    @section('scripts')
    @endsection
</x-app-layout>
