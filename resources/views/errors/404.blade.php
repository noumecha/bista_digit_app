<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Non trouvé</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Link to your CSS file -->
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8f9fa;
                color: #343a40;
                text-align: center;
                padding: 50px;
            }
            h1 {
                font-size: 50px;
                margin-bottom: 20px;
            }
            p {
                font-size: 20px;
                margin-bottom: 30px;
            }
            a {
                text-decoration: none;
                color: #007bff;
                font-weight: bold;
            }
            a:hover {
                text-decoration: underline;
            }
            .logo {
                max-width: 150px;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <img src="{{ asset(appConfiguration() != null ? 'storage/'.appConfiguration()->school_logo : "") }}"
            alt="{{ appConfiguration() != null ? appConfiguration()->school_name : "" }}" class="logo">
        <h1>404 - Page Not Found</h1>
        <p>Désolé, la page que vous recherchez n'existe pas.</p>
        <p>Contactez l'administrateur si vous pensez qu'il s'agit d'une erreur.</p>
        <p>
            <a href="{{ route('home.index') }}">
                Retournez à l'accueil
            </a>
        </p>
    </body>
</html>
