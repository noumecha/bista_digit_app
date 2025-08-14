<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Notification</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Expéditeur</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($notifications as $notification)
            <tr id="{{ $notification->id }}">
                <td>
                    <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column">
                            <h6 class="mb-0 text-sm">
                                <a href="{{ isset($notification->id) ?? route('notifications.show', $notification->id) }}">
                                    {{ isset($notification->title) ? $notification->title : "-" }}
                                </a>
                            </h6>
                            <p class="text-xs text-secondary mb-0">
                                {{ isset($notification->message) ? Str::limit($notification->message, 20, " ...") : "-" }}
                            </p>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="text-xs font-weight-bold mb-0">
                        {{ isset($notification->sender) ? $notification->sender->name : "-" }}
                    </p>
                </td>
                <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">
                        {{ isset($notification->created_at) ? $notification->created_at->format('d/m/Y H:i') : "-" }}
                    </span>
                </td>
                <td class="align-middle text-center">
                    @if(isset($notification->read_at))
                        <span class="badge badge-sm bg-gradient-success">Lu</span>
                    @else
                        <span class="badge badge-sm bg-gradient-danger">Non lu</span>
                    @endif
                </td>
                <td class="text-center d-flex justify-content-center
                    align-middle bg-transparent border-bottom"
                    style="gap:10px;">
                    <button
                        type="button"
                        class="btn btn-primary ml-2 mt-3 p-2"
                        data-bs-toggle="modal"
                        id="notification-show-btn"
                        data-notification-id = "{{ $notification->id }}"
                        data-bs-target="#show-{{ $notification->id }}">
                        voir
                    </button>
                    <!-- modal for showing notification -->
                    <div class="modal fade" id="show-notif-modal-{{ $notification->id }}" tabindex="-1" aria-labelledby="exampleModalLabel">
                        <div class="modal-dialog">
                            <div class="modal-content text-justify">
                                <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                                    <h4>
                                        <i class="fa-solid me-2 fa-bell"></i> {{ $notification->title }}
                                    </h4>
                                    <hr>
                                    <p>
                                        {{ $notification->message }}
                                    </p>
                                    <button type="button" class="text-dark btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-4">
                    <p class="text-sm text-secondary mb-0">Aucune notification trouvée</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $notifications->appends(request()->query())->links() }}
</div>