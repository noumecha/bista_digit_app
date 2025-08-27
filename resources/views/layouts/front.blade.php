<!DOCTYPE html>
<html lang="{{ str_replace('_', '-',app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>{{ env('APP_NAME')}}</title>
        <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
        <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}"/>
        <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-utilities.css') }}">
        <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-grid.css') }}">
        <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-reboot.css') }}">
        <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/add.css') }}">
        <!-- Fonts and icons -->
        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Noto+Sans:300,400,500,600,700,800|PT+Mono:300,400,500,600,700"
            rel="stylesheet" />
        <!-- Font Awesome Icons -->
        <script src="https://kit.fontawesome.com/612ac88160.js" crossorigin="anonymous"></script>
    </head>

    <body>
        @include('layouts.frontnavigation')
        <main>
            {{ $slot }}
        </main>
        @include('layouts.frontfooter')
        <script src="{{ asset('front/js/jquery-3.2.1.min.js') }}"></script>
        <script src="{{ asset('front/js/popper.min.js') }}"></script>
        <script src="{{ asset('js/functions/modules/utils.js') }}"></script>
        <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
        <script src="{{ asset('front/js/script.js') }}"></script>
        <script src="{{ asset('js/functions/actualites-search-bar.js') }}"></script>
        <script src="{{ asset('js/functions/epreuves-search-bar.js') }}"></script>
        <script src="{{ asset('js/functions/obc-search-filter.js') }}"></script>
    </body>
</html>
