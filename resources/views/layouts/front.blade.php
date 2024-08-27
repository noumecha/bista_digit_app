<!DOCTYPE html>
<html lang="{{ str_replace('_', '-',app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>BISTA WEBSITE</title>
        <link rel="stylesheet" href="../assets/front/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/front/css/all.min.css">
        <link rel="stylesheet" href="../assets/front/css/owl.carousel.min.css">
        <link rel="stylesheet" href="../assets/front/css/owl.carousel.css">
        <link rel="stylesheet" href="../assets/front/css/style.css">
    </head>

    <body>
        @include('layouts.frontnavigation')
        <main>
            {{ $slot }}
        </main>
        @include('layouts.frontfooter')

    </body>

    <script src="../assets/front/js/jquery-3.2.1.min.js"></script>
    <script src="../assets/front/js/popper.min.js"></script>
    <script src="../assets/front/js/bootstrap.min.js"></script>
    <script src="../assets/front/js/owl.carousel.min.js"></script>
    <script src="../assets/front/js/owl.carousel.js"></script>
    <script src="../assets/front/js/script.js"></script>
</html>
