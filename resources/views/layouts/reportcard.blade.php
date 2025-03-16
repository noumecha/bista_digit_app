<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Bulletin de [nom]</title>
        <link rel="stylesheet" href="{{ public_path('bootstrap/css/bootstrap-grid.css') }}"/>
        <link rel="stylesheet" href="{{ public_path('bootstrap/css/bootstrap-reboot.css') }}"/>
        <link rel="stylesheet" href="{{ public_path('bootstrap/css/bootstrap-utilities.css') }}"/>
        <link rel="stylesheet" href="{{ public_path('bootstrap/css/bootstrap.css') }}"/>
        <link rel="stylesheet" href="{{ public_path('bootstrap/css/bootstrap.rtl.css') }}"/>
        <link rel="stylesheet" href="{{ public_path('bootstrap/css/bootstrap-reboot.css') }}"/>
        <link rel="stylesheet" href="{{ public_path('bulletin/bulletin.css') }}"/>
    </head>
    <body class="m-3 report-card">
        @include('layouts.reportcardheader')
        <main>
            {{ $slot }}
        </main>
        @include('layouts.reportcardfooter')
    </body>
 </html>