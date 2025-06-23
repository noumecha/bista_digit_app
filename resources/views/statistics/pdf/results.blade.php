<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Résultats {{ $trimestre->libelleTrimestre }} - {{ $classe->libClasse }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 14px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Établissement : {{ appConfiguration()->school_name }} </div>
        <div class="subtitle">
            Résultats du {{ $trimestre->libelleTrimestre }} - classe : {{ $classe->libClasse }}
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Rang</th>
                <th>Matricule</th>
                <th>Nom & Prénom</th>
                <th>Moyenne</th>
                <th>Appréciation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bulletins as $bulletin)
            <tr>
                <td>{{ $bulletin->range }}</td>
                <td>{{ $bulletin->student->matricule }}</td>
                <td>{{ $bulletin->student->name }} {{ $bulletin->student->surname }}</td>
                <td class="{{ $bulletin->average >= 10 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($bulletin->average, 2) }}
                </td>
                <td>{{ $bulletin->appreciation }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>