<?php
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

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/test.css">

    <!-- JS -->
    <script defer src="assets/js/test.js"></script>
    <script src="https://kit.fontawesome.com/ba74dd8982.js" crossorigin="anonymous"></script>

    <title>calendar</title>
</head>
<body>
<?php
if (isset($_POST["idCard"])){?>
    <section id="modal">
        <div>
            <div>
                <i class="fa-solid fa-xmark" id="closeModal"></i>
            </div>
            <?php

            if (isset($_POST['idCard'])) {
                $id = htmlspecialchars($_POST['idCard']);
                $reservation = $request_obj->find_reservation_by_id($id);
                echo "<h1 id='TitleModel'>" . $reservation["first_name"] . " " . $reservation["last_name"] . " - " . $reservation["name_service"] . "</h1>";
                echo "<pre>";
                print_r($request_obj->find_reservation_by_id($id));
                echo "</pre>";
            }else{
                echo "Aucun ID n'a été envoyé";
            }

            ?>
        </div>
    </section>
<?php
}


?>
<section id="calendar">
    <div id="containerInfosDays">
        <div id="containerDateAndBtnAdd"></div>
        <div id="containerDays">
            <div id="previousWeekAndTime">
                <div id="PreviousWeek">
                    <i class="fa-solid fa-chevron-up fa-rotate-270" style="color: #ffffff;"></i>

                </div>
                <div>

                    <p>9h</p>
                    <p>10h</p>
                    <p>11h</p>
                    <p>12h</p>
                    <p>13h</p>
                    <p>14h</p>
                    <p>15h</p>
                    <p>16h</p>
                    <p>17h</p>
                </div>
            </div>

            <div id="containerDayAndAppointment">
                <div id="Days"></div>
                <div id="containerAppointments">
                </div>
            </div>

            <div id="NextWeek">
                <i class="fa-solid fa-chevron-up fa-rotate-90" style="color: #ffffff;"></i>
            </div>
        </div>

    </div>

</section>

<form action="" method="post" id="modalForm">
    <input type="hidden" name="idCard" id="idCard">
</form>
<!--  Data check if admin -->


<div id="dataReservation" data-reservation="<?= htmlspecialchars(json_encode($request_obj->get_all_reservations())) ?>"></div>
</body>
</html>