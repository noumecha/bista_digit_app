@foreach($notifications as $notif)
    <div class="{{ is_null($notif->read_at) ? 'bg-light' : 'text-muted' }}">
        <a href="{{ url('/notifications/view/'.$notif->id) }}">
            <strong>{{ $notif->title }}</strong><br>
            {{ Str::limit($notif->message, 100) }}
        </a>
    </div>
@endforeach