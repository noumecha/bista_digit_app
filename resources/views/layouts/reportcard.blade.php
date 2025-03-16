<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Bulletin de [nom]</title>
        <link rel="stylesheet" href="{{ public_path('front/css/bootstrap.min.css') }}"/>
        <link rel="stylesheet" href="{{ public_path('front/css/boostrap.min.css') }}"/>
        <link rel="stylesheet" href="{{ public_path('front/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ public_path('front/css/style.css') }}">
        <link rel="stylesheet" href="{{ public_path('css/add.css') }}" />
    </head>
    <body class="m-3">
        @include('layouts.reportcardheader')
        <main>
            {{ $slot }}
        </main>
        @include('layouts.reportcardfooter')
    </body>
 </html>