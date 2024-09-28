<?php

// Required files //
use Models\HandlerRequestsUsers;


require_once "../config/database.php";
require_once "../app/Models/HandlerRequestsUsers.php";
require_once "../app/Sessions/HandlerSessions.php";
require_once "../app/Models/HandleConnexion.php";
require_once "../app/Models/HandleRegister.php";

// Database connection //
$bdd = new Database();
$bdd->connect_bdd();
$request_obj = new HandlerRequestsUsers();

// Start session //
$session = new Sessions\HandlerSessions();
$session->start_session();

// Handle register //
$Register = new \Models\HandleRegister();


// password check //
if (isset($_POST["email"]) && isset($_POST["password"])){
    $Connexion = new \Models\HandleConnexion($_POST["email"], $_POST["password"]);
    $verification = $Connexion->verification_connexion_email_password();
    if ($verification["state"]){

        $user_infos = $verification["user_info"];
        $session->set_session_user($user_infos["email"], $user_infos["first_name"], $user_infos["last_name"], $user_infos["id_people"], $user_infos["is_admin"], $user_infos["phone_number"]);
        $valid = $verification["message"];

    }else{
        $errorMessages_connexion = $verification["message"];
    }
}

// registration check //
$register = "";
if (isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email_inscription"]) && isset($_POST["password_inscription"]) && isset($_POST["password_confirmation"])){

    $verification = $Register->verification_inscription($_POST["email_inscription"], $_POST["password_inscription"], $_POST["password_confirmation"]);

    if ($verification["state"]){

        try {
            $Register->send_email($_POST["first_name"], $_POST["last_name"] ,$_POST["email_inscription"]);
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $errorMessage_inscription = "Erreur lors de l'envoi de l'email";
        }

        $register = "inscription";

//        $user_infos = $verification["user_info"];
//        $session->set_session_user($user_infos["Email"], $user_infos["First_name"], $user_infos["Last_name"], $user_infos["Id_people"], $user_infos["Is_admin"]);
//        $valid_inscription = $verification["message"];
    }else{
        $errorMessage_inscription = $verification["message"];
    }
}

// code verification //
if (isset($_POST["numero1"]) && isset($_POST["numero2"]) && isset($_POST["numero3"]) && isset($_POST["numero4"])){
    $code = $_POST["numero1"] . $_POST["numero2"] . $_POST["numero3"] . $_POST["numero4"];
    if ($Register->verification_code($code)["state"]){

        $request_obj->register_user($_POST["first_name"], $_POST["last_name"], $_POST["email_inscription"], $_POST["password_inscription"]);
        $id = $request_obj->get_infos_user($_POST["email_inscription"])["Id_people"];
        $session->set_session_user($_POST["email_inscription"], $_POST["first_name"], $_POST["last_name"], $id, 0, $_POST["phone"]);
        header("Location: index.php");
        exit();

    }else{
        $errorMessages_code = $Register->verification_code($code)["message"];
    }
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!--  CSS  -->
    <link rel="stylesheet" href="assets/css/connexion.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/normal_header.css">

    <!--  JS  -->
    <script src="https://kit.fontawesome.com/ba74dd8982.js" crossorigin="anonymous"></script>
    <script src="assets/js/connexion.js"></script>

    <title>Connexion - SkinBeauty</title>
</head>
<body>

<?php include '../app/Views/partials/normal_header.php'; ?>

<section class="section-connexion">

    <div class="img_inscription">
        <img src="assets/images/connexion/flower_hand.png" alt="flower_hand">
    </div>

    <div>
        <div class="input_display">
            <div class="connexion">
                <h2>connexion</h2>
                <form action="#" method="POST">
                    <?php if (isset($errorMessages_connexion)){
                        echo "<p class='message_error'>" . $errorMessages_connexion . "</p>";
                    } ?>
                    <label for="<?= !isset($errorMessages_connexion) ? "email" : "email_error"; ?>"></label>
                    <input placeholder="email" id="<?= !isset($errorMessages_connexion) ? "email" : "email_error"; ?>" name="email" type="email">

                    <label for="<?= !isset($errorMessages_connexion) ? "password" : "password_error"; ?>"></label>
                    <input placeholder="mot de passe" name="password" id="<?= !isset($errorMessages_connexion) ? "password" : "password_error"; ?>" type="password">

                    <div>
                        <button> Connexion </button>
                    </div>
                </form>
            </div>
            <div class="noAccount">
                <p>Pas de compte ? <span id="inscription">S'inscrire ici</span></p>
            </div>
            <div style="display: none" class="inscription">
                <div class="return_container">
                    <i class=" arrow_return fa-solid fa-arrow-right fa-rotate-180" style="color: #666;"></i>
                    <h4 class="arrow_return return_text">Retour</h4>
                </div>
                <h2>s'inscrire</h2>
                <form method="post">
                    <?php if (isset($errorMessage_inscription)){
                        echo "<p class='message_error'>" . $errorMessage_inscription . "</p>";
                    } ?>
                    <div class="container_name_form">
                       <div>
                           <label aria-label="first name" for="first_name"></label>
                           <input type="text" name="first_name" id="first_name" placeholder="nom" value="<?= $_POST["first_name"] ?? "" ?>">
                       </div>

                        <div>
                            <label aria-label="last_nane" for="last_name"></label>
                            <input type="text" name="last_name" id="last_name" placeholder="prénom" value="<?= $_POST["last_name"] ?? "" ?>">
                        </div>
                    </div>

                    <label for="email_inscription"></label>
                    <input type="email" name="email_inscription" id="email_inscription" placeholder="email" value="<?= $_POST["email_inscription"] ?? "" ?>">

                    <label for="password_inscription"></label>
                    <input type="password" name="password_inscription" id="password_inscription" placeholder="mot de passe" value="<?= $_POST["password"] ?? "" ?>">

                    <label for="password_confirmation"></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="resaisir mot de passe">

                    <label for="phone"></label>
                    <input type="tel" name="phone" id="phone" placeholder="téléphone">

                    <div>
                        <button> S'inscrire </button>
                    </div>
                </form>
            </div>
            <div style="display: none" class="code_inscription">
                <div>
                    <h2>Derniere étape</h2>
                    <p>Un code de vérification vous a été envoyé sur votre boîte email <strong><?= $_POST["email_inscription"] ?></strong></p>
                </div>
                <?php if (isset($errorMessages_code)){
                    echo "<p class='message_error'>" . $errorMessages_code . "</p>";
                } ?>
                <div>
                    <form class="class_digital_number" action="" method="post">
                        <div>
                            <label for="numero1"></label>
                            <input class="digit-input" placeholder="_" type="number" name="numero1" id="numero1" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
                            <label for="numero2"></label>
                            <input class="digit-input" placeholder="_" type="number" name="numero2" id="numero2" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
                            <label for="numero3"></label>
                            <input class="digit-input" placeholder="_" type="number" name="numero3" id="numero3" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
                            <label for="numero4"></label>
                            <input class="digit-input" placeholder="_" type="number" name="numero4" id="numero4" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>

                            <label for="email_inscription"></label>
                            <input type="hidden" name="email_inscription" id="email_inscription" value="<?= $_POST['email_inscription'] ?>" required>

                            <label for="password_inscription"></label>
                            <input type="hidden" name="password_inscription" id="password_inscription" value="<?= $_POST['password_inscription'] ?>" required>

                            <label for="first_name"></label>
                            <input type="hidden" name="first_name" id="first_name" value="<?= $_POST['first_name'] ?>" required>

                            <label for="last_name"></label>
                            <input type="hidden" name="last_name" id="last_name" value="<?= $_POST['last_name'] ?>" required>

                            <label for="phone"></label>
                            <input type="hidden" name="phone" id="phone" value="<?= $_POST['phone'] ?>" required>
                        </div>
                        <div>
                            <button>continuer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="container-dots">
            <div class="snippet" data-title="dot-flashing">
                <div class="stage">
                    <div class="dot-flashing"></div>
                </div>
            </div>
        </div>
    </div>


</section>
<div id="variable_success_or_no" data-variable="<?= $errorMessages_connexion ?? ($valid ?? $register) ?>"></div>
<div id="submit_inscription" data-variable="<?= isset($errorMessage_inscription) ? 'false' : 'true'?>"></div>
<div id="stat_code" data-variable="<?= $errorMessages_code ?? 'false' ?>"></div>


<?php include '../app/Views/partials/footer.html'?>

</body>
</html>
