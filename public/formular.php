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

$service = $request_obj->get_services_by_id($_GET['id']);

?>

<!doctype html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<!--    <link rel="stylesheet" href="../assets/css/styles.css">-->
    <link rel="stylesheet" href="assets/css/formular.css">
    <link rel="stylesheet" href="assets/css/normal_header.css">
    <title>Connexion</title>
</head>
<body>
<?php include '../app/Views/partials/normal_header.php'; ?>
<main>
    <?php
    if ($service && !isset($_SESSION['auth'])) {
        echo "<div class='container-title'>";
        echo "<h1 class='Title-service'>".$service['name_service'] . "</h1>";
        echo "<div class='container-connexion-info'>";
        echo "<p><span>vous devez vous </span> <a href='connexion.php'>connecter</a></p>";
        echo "</div>";
        echo "</div>";
    } elseif (!$service) {
        echo "<div class='container-title'>";
        echo "<h1 class='Title-service'>Service non disponible</h1>";
        echo "</div>";
        echo "<script>
            setTimeout(function() {
                window.location.href = '/project/public/index.php';
            }, 2000);
          </script>";


    }else{?>
        <div class='container-title animation-title'>
            <h1 class='Title-service'><?= $service['name_service'] ?></h1>
        </div>
        <div class='container-info-about-form'>
            <div>
                <h2>Qu'est ce que c'est ?</h2>
                <p class="paragraph-details"><?= $service['details'] ?></p>
            </div>
            <div>
                <h2>selectionner votre formule</h2>
                <div class="container-formula">
                    <?php

                    $categories = $request_obj->get_categories_by_service($_GET['id']);

                    $arr_categories = [];
                    $name_categories = [];

                    for ($i = 0; $i < count($categories); $i++){
                        if(!in_array($categories[$i]['name_category'], $name_categories)){
                            $name_categories[] = $categories[$i]['name_category'];
                            $arr_categories[] = ["name_category" => $categories[$i]['name_category'], "formula" => [[$categories[$i]['name_formula'], $categories[$i]['Price'], $categories[$i]['Id_formula']]]];
                        }else{
                            $arr_categories[count($name_categories)-1]["formula"][] = [$categories[$i]['name_formula'], $categories[$i]['Price'], $categories[$i]['Id_formula']];
                        }
                    }



                    for ($i = 0; $i < count($arr_categories); $i++){
                        echo "<div class='container-category'>";
                        echo "<h3>".$arr_categories[$i]['name_category']."</h3>";
                        echo "<div >";
                        for ($j = 0; $j < count($arr_categories[$i]['formula']); $j++){
                            echo "<a href='hour.php?formula=" . $arr_categories[$i]['formula'][$j][2] . "' class='formula'>";
                            echo "<p>".$arr_categories[$i]['formula'][$j][0]."</p>";
                            echo "<p>".$arr_categories[$i]['formula'][$j][1]." €</p>";
                            echo "</a>";
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php }
    ?>
</main>
<script src="https://kit.fontawesome.com/ba74dd8982.js" crossorigin="anonymous"></script>

</body>
</html>

