<?php

use Models\HandlerRequestsUsers;

$bdd = new Database();
$bdd->connect_bdd();
$request_obj = new HandlerRequestsUsers(); ?>

<header>

    <div class="logo">
        <h1>S<span class="BofLogo">B</span></h1>
    </div>
    <div class="container_resevation_shortcut">
        <form action="" id="form_shortcut_reservation">
            <div>
                <label for="reservation"></label>
                <input name="reservation" id="reservation_date" type="date">
            </div>
            <div>
                <label for="reservation"></label>
                <select name="reservation" id="reservation" disabled>
                    <option value="A">
                        A
                    </option>
                </select>

            </div>
            <div>
                <label for=""></label>
                <select name="" id="" disabled>
                    <option value="Heure">
                        Heure
                    </option>
                </select>
            </div>
            <div>
                <label for=""></label>
                <select name="" id="" disabled>
                    <option value="Services">
                        Services
                    </option>
                </select>
            </div>
            <div>
                <label for=""></label>
                <select name="" id="" disabled>
                    <option value="Formules">
                        Formules
                    </option>
                </select>
            </div>
            <div>
                <button disabled id="btn_reservation"><i class="fa-solid fa-arrow-right" style="color: #ffffff;"></i></button>
            </div>
        </form>
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