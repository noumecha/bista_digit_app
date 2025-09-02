
<table class="table text-secondary text-center">
    <thead>
        <tr>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Utilisateur</th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Rôle</th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Type</th>
            <th
                class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($roles->items()))
            <td colspan="6" class="text">
                Aucune donnée disponible
            </td>
        @else
            @foreach ($roles as $role)
            <tr>
                <td class="align-middle bg-transparent border-bottom">
                    {{ $role->name }}
                </td>
                <td class="align-middle bg-transparent border-bottom">
                    {{ $role->role }}
                </td>
                <td class="align-middle bg-transparent border-bottom">
                    {{ $role->typeUser ? $role->typeUser : $role->role }}
                </td>
                <td class="text-center d-flex justify-content-center align-middle bg-transparent border-bottom" style="gap:10px;">
                    <a
                        data-bs-toggle="modal"
                        id="edit-button"
                        data-bs-target="#create-role-modal"
                        data-action="edit"
                        data-role-id="{{ $role->id }}"
                        data-role-role="{{ $role->role }}"
                        data-url="{{ route('utilisateur.roleStore', $role->id) }}"
                        class="btn btn-primary mt-3 p-2"
                        href="#"
                    >
                        <i class="fa-solid fa-pen"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ !empty($roles) ? $roles->appends(request()->query())->links() : '' }}
</div>