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

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- JS  -->
    <script src="https://kit.fontawesome.com/ba74dd8982.js" crossorigin="anonymous"></script>

    <!--  CSS  -->
    <link rel="stylesheet" href="assets/css/services.css">
    <link rel="stylesheet" href="assets/css/normal_header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <title>SkinBeauty - Services</title>
</head>
<body>

<?php include '../app/Views/partials/normal_header.php'; ?>

<div class="Container-all-services">
    <?php

    foreach ($request_obj->get_services() as $service) {
        ?>
        <a href="formular.php?id=<?= $service['id_services']?>" class="service service-item">
            <img src="assets/images/Services/<?= $service['name_image'] ?>" alt="Photo <?= $service['name_service'] ?>">
            <div class="name_service_arrow">
                <h4><?= $service['name_service'] ?></h4>
                <i class="fa-solid fa-arrow-right fa-rotate-by" style="--fa-rotate-angle: 45deg;"></i>
            </div>
        </a>
    <?php } ?>
    <?php if ($session->get_session_user()["Role"] == 1){
        ?>
        <a href="add_service.php" class="service service-item">
            <div class="name_service_arrow">
                <h4>Ajouter un service</h4>
                <i class="fa-solid fa-plus"></i>
            </div>
        </a>
<!--    --><?php
    } ?>

</div>

<?php include '../app/Views/partials/footer.html'; ?>
</body>
</html>