<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container devoir-container">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $devoir->titre_devoir }}</h3>
                    <div class="progress" style="height: 15px">
                        <div class="progress-bar"
                            style="height: 15px; padding-left: 14px; padding-right: 14px; width: {{ (int)round($progress, 0) }}%"
                            role="progressbar">
                            Question {{ $questionNumber }} / {{ $totalQuestions }}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="qcmForm" action="{{ route('devoirs.answer', $devoir) }}" method="POST">
                        @csrf
                        <input type="hidden" name="question_id" value="{{ $question->id }}">

                        <div class="question-container">
                            <div>
                                {!! $question->question !!}
                            </div>
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
                            @if($questionNumber > 1)
                                <a href="{{ route('devoirs.previous', [$devoir]) }}"
                                   class="btn btn-secondary">Précédent</a>
                            @endif

                            @if($questionNumber < $totalQuestions)
                                <button type="submit" class="btn btn-primary">Suivant</button>
                            @else
                                <button type="submit" name="finish" value="1" class="btn btn-success">
                                    resultat
                                </button>
                            @endif
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger text-center success-message">
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
        <x-app.footer />
    </main>
    @section('scripts')
    @endsection
</x-app-layout>
