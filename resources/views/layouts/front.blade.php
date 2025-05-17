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
    </head>

    <body>
        @include('layouts.frontnavigation')
        <main>
            {{ $slot }}
        </main>
        @include('layouts.frontfooter')
        <script src="{{ asset('front/js/jquery-3.2.1.min.js') }}"></script>
        <script src="{{ asset('front/js/popper.min.js') }}"></script>
        <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
        <script src="{{ asset('front/js/script.js') }}"></script>
        <script src="https://kit.fontawesome.com/349ee9c857.js" crossorigin="anonymous"></script>
    </body>
</html>
