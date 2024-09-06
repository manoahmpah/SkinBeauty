<header>
    <div class="logo">SB</div>
    <nav>
        <ul class="nav-links">
            <li><a href="index.php" class="<?= $_SERVER["REQUEST_URI"] == "/project/public/index.php" ? "active" : "" ?>">Accueil</a></li>
            <li><a class="<?= $_SERVER["REQUEST_URI"] == "/project/public/services.php" ? "active" : "" ?>" href="<?= "/project/public/services.php" ?>">Services</a></li>

            <?php if (isset($_SESSION['auth'])){ ?>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="#"><i class="fa-solid fa-right-from-bracket" style="color: #000000;"></i></a></li>
            <?php } else { ?>
            <li><a class="<?= $_SERVER["REQUEST_URI"] == "/project/public/connexion.php" ? "active" : "" ?>" href="<?=  "/project/public/connexion.php" ?>"> s'inscrire / se connecter</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>