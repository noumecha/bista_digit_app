<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 15px; text-align: center; }
        .content { padding: 20px; background-color: #fff; }
        .footer { margin-top: 20px; text-align: center; font-size: 0.8em; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $title }}</h2>
        </div>

        <div class="content">
            <p>{!! nl2br(e($content)) !!}</p>
        </div>

        <div class="footer">
            <p>Cet email vous a été envoyé automatiquement par le système de notification de l'établissement.</p>
            <p>Merci de ne pas répondre à ce message.</p>
        </div>
    </div>
</body>
</html>