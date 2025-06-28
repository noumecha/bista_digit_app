@if($selectedOBC)
    <div class="obc-ranking text-center py-4">
        <div class="display-2 text-success fw-bold mb-3">
            {{ $selectedOBC->obc_rank ?? 3 }}<sup>ème</sup>
        </div>
        <p class="lead">
            sur {{ $selectedOBC->data['total_schools'] ?? '1200' }} établissements au Cameroun
        </p>
        <div class="progress mt-4" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar"
                style="width: {{ ($selectedOBC->data['total_schools'] - $selectedOBC->obc_rank) / $selectedOBC->data['total_schools'] * 100 }}%"
                aria-valuenow="{{ $selectedOBC->obc_rank }}"
                aria-valuemin="1"
                aria-valuemax="{{ $selectedOBC->data['total_schools'] }}">
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info">
        Aucun classement OBC disponible pour cette année
    </div>
@endif