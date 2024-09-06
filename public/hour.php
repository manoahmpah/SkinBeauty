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


$reservations = $request_obj->get_all_reservations();
$disableTimes = [];

foreach ($reservations as $reservation) {
    $disableTimes[$reservation['Start_date_reservation']][] = $reservation['Hour_start'];
}
$disableTimesJson = json_encode($disableTimes);

if (isset($_POST['datepicker']) && isset($_POST['timepicker'])) {
    $date = $_POST['datepicker'];
    $time = $_POST['timepicker'];
    $formula = $_POST['formula'];
    $request_obj->add_reservation($date, $time, $formula, $session->get_session_user()['Id_people'], $request_obj->get_credit_from_formulas($formula)["credits"]);
    $arr = explode('-', $date);
    $date = $arr[2] . '/' . $arr[1] . '/' . $arr[0];
    if ($arr[1][0] === '0')
        $arr[1] = $arr[1][1];
    header('Location: account.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flatpickr avec dates et heures désactivées</title>
    <link rel="stylesheet" href="assets/css/hour.css">
    <link rel="stylesheet" href="assets/css/normal_header.css">
    <script src="assets/js/handle_reservation.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

</head>

<body>


<?php include '../app/Views/partials/normal_header.php'; ?>

<div id="main-container">
    <form action="#" method="post">
        <label for="datepicker" class="form-label">Choisissez une date:</label>
        <input type="date" id="datepicker" class="form-input" name="datepicker">

        <label for="timepicker" class="form-label">Choisissez une heure:</label>
        <select id="timepicker" class="form-input form-input-select" name="timepicker"></select>

        <input type="hidden" name="formula" value="<?= $_GET["formula"] ?>">

        <button type="submit">Soumettre</button>
    </form>

    <script>
        const reservationHandler = new ReservationHandler();
        reservationHandler.initializeDatepicker();
        reservationHandler.toggleTimepicker();

        const disableTimes = <?= $disableTimesJson; ?>;
        const datePiker = document.getElementById('datepicker');
        datePiker.addEventListener('change', ()=>{
            reservationHandler.removeTimepickerOptions()
            reservationHandler.resetDisabledTime()
            if (disableTimes[datePiker.value] !== undefined) {
                let dateFormat = []
                for (let i = 0; i < disableTimes[datePiker.value].length; i++) {
                    const [hours, minutes] = disableTimes[datePiker.value][i].split(':');
                    const timeFormatted = `${hours}:${minutes}`;
                    dateFormat.push(timeFormatted);
                }
                console.log(dateFormat)
                reservationHandler.setDisabledTime(dateFormat);
                reservationHandler.initializeTimepicker();
                console.log(reservationHandler.getDisabledTime())
            }else{
                reservationHandler.initializeTimepicker();
            }
        })
    </script>
</div>
</body>

</html>
