<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            background-color: #000000;
            padding: 20px;
            text-align: center;
            color: white;
        }
        .content {
            padding: 20px;
            background-color: #fff;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f1f1f1;
            color: #666;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            color: #000000;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            font-size: 16px;
            color: white;
            background-color: #000000;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>

    <title>SkinBeauty - vérification email</title>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>SkinBeauty</h1>
    </div>
    <div class="content">
        <p>Bonjour,</p>
        <p>Merci de vous être inscrit chez SkinBeauty. Pour compléter votre inscription, veuillez utiliser le code de vérification suivant :</p>
        <p class="code"><?= htmlspecialchars($randomNumber) ?></p>
        <p>Ce code n\'est valide que durant <strong>10 min</strong>, après ce délais vous devrez vous l'envoyer une nouvelle fois.</p>

        <p>Merci et à bientôt,<br>SkinBeauty</p>

        <a href="#" class="btn">Visitez notre site</a>
    </div>
    <div class="footer">
        <p>&copy; <?= date('Y') ?> SkinBeauty. Tous droits réservés.</p>
    </div>
</div>
</body>
</html>