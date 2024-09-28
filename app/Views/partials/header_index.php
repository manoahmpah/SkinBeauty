<?php

use Models\HandlerRequestsUsers;

$bdd = new Database();
$bdd->connect_bdd();
$request_obj = new HandlerRequestsUsers(); ?>

<header>

    <div class="logo">
        <h1>S<span class="BofLogo">B</span></h1>
    </div>
    <nav>
        <ul>
            <li><a href="/project/public/index.php">Accueil</a></li>
            <li><a href="/project/public/services.php">Services</a></li>
            <?php if (!isset($_SESSION['auth'])) {?>
                <li><a href="../../../../project/public/connexion.php">S'inscrire / se connecter</a></li>

            <?php } else { ?>
            <li><a href="/project/public/account.php">Mon compte</a></li>
            <li><a href="?log_out">Déconnexion</a></li>

            <?php } ?>
        </ul>
    </nav>
</header>