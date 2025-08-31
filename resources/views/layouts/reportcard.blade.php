@props(['bulletin','principal','effectif','qrcode'])
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Bulletin</title>
        <link rel="stylesheet" href="{{ public_path('css/bulletin-css/header.css') }}">
        <link rel="stylesheet" href="{{ public_path('css/bulletin-css/report-card.css') }}">
        <link rel="stylesheet" href="{{ public_path('css/bulletin-css/footer.css') }}">
    </head>
    <body class="d-block p-rltv">
        <!-- @ include('layouts.reportcardheader') -->
        @include('layouts.test-header')
        <main class="d-block">
            {{ $slot }}
        </main>
        @include('layouts.reportcardfooter')
        <!-- qr code -->
        <img class="d-block p-abs qrcode-container" src="data:image/svg+xml;base64,{{ $qrcode }}" alt="QR Code"/>
        <!-- bottom line -->
        <div class="d-block p-abs footer-bulletin-credit"></div>
        <!-- date on left -->
        <p class="d-block p-abs footer-text-left p-0 m-0">
            &copy; {{ Date('Y') }} - POWEREDUCATION
        </p>
        <!-- date on right -->
        <p class="d-block p-abs footer-text-right p-0 m-0">
            Imprimé le : {{ Date('d/m/y') }} à {{ Date('h:m:s') }}
        </p>
        <!-- date on right -->
        <p class="d-block p-abs footer-text-left p-0 m-0">
            &copy; {{ Date('Y') }} - POWEREDUCATION
        </p>
    </body>
</html>