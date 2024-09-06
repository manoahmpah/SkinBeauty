<?php
// Required files //
use Models\HandlerRequestsUsers;

require_once "../config/database.php";
require_once "../app/Models/HandlerRequestsUsers.php";
require_once "../app/Sessions/HandlerSessions.php";

// Database connection //
$bdd = new Database();
$bdd->connect_bdd();
$request_obj = new HandlerRequestsUsers();

// Start session //
$session = new Sessions\HandlerSessions();
$session->start_session();
//$session->unset_session_user();

if (isset($_GET['log_out'])) {
    $session->unset_session_user();
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkinBeauty</title>

    <!--  CSS  -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/header_index.css">
    <link rel="stylesheet" href="assets/css/footer.css">

    <!--  JS  -->
    <script src="https://kit.fontawesome.com/ba74dd8982.js" crossorigin="anonymous"></script>
</head>
<body data-theme='light'>
<section class="first_page">
    <?php include '../app/Views/partials/header_index.php'; ?>
    <div class="SkinBeauty">
        <h1>SkinBeauty</h1>
    </div>
    <div class="moreInfo">
        <h2>faites défiler pour en savoir plus</h2>
    </div>
</section>


<section class="presentation">
    <div>
        <h3>Bienvenue chez <span class="name_brain">SkinBeauty</span></h3>
        <p>
            Découvrez une expérience de bien-être unique avec nos soins haut de gamme : <strong>HeadSpa</strong>, soins du corps, du visage, des cils, et bien plus encore. Prenez soin de vous avec des traitements personnalisés qui subliment votre beauté naturelle.
        </p>
    </div>
</section>

<section class="customer_return">
    <h3>Retour clients</h3>
    <div class="All_comments">
        <p>
            J'ai passé un moment de détente incroyable chez SkinBeauty. Les soins sont de qualité et le personnel est très accueillant. Je recommande vivement !
        </p>
        <p>
            Encore merci pour se moment de détente de bonheur, tu es au top ❤️ (HeadSpa)
        </p>
        <p>
            un petit message pour te dire que j’ai eu trop de compliment sur mon rehaussement.
        </p>
        <p>
            Merci pour ce moment de détente et de bien-être. Je suis ressortie de chez vous avec une peau douce et éclatante. À très bientôt !
        </p>
    </div>
</section>

<section>
    <h3>~ Nos services phares ~</h3>
    <div class="Top-services">
        <div></div>
        <div></div>
        <div></div>
    </div>
</section>

<?php include '../app/Views/partials/footer.html'; ?>
</body>
</html>
