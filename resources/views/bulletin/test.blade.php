<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <link rel="stylesheet" href="{{ asset('css/bulletin-css/report-card.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bulletin-css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bulletin-css/header.css') }}">
</head>
<body>
    <h6>
        {{ $bulletin->appconfiguration->school_name }}
    </h6>
    <div class="">
        <div class="">
            <img class="" src="{{ public_path('storage/' . $bulletin->appconfiguration->school_logo) }}"/>
        </div>
    </div>
</body>
</html>