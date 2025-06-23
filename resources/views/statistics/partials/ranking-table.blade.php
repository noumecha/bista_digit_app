@foreach($bulletins as $classeName => $classBulletins)
<div class="mb-5">
    <h6 class="text-center bg-light p-2 mb-3">{{ $classeName }}</h6>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Rang OBC</th>
                    <th>Matricule</th>
                    <th>Nom & Prénom</th>
                    <th>Moyenne</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classBulletins as $bulletin)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $bulletin->student->matricule }}</td>
                    <td>{{ $bulletin->student->name }}</td>
                    <td class="fw-bold {{ $bulletin->average >= 10 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($bulletin->average, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach