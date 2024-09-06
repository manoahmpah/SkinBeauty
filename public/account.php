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

$data_people = array_fill(1, 12, 0);
foreach ($request_obj->get_creation_date_users_by_month() as $creation_date) {
    $data_people[$creation_date["month"]] = $creation_date["nb_users"];
}

if (isset($_POST['deny'])) {
    $request_obj->update_reservation_state($_POST['deny'], 2);
    header('Location: account.php');
    exit();

} elseif (isset($_POST['valid'])) {
    $request_obj->update_reservation_state($_POST['valid'], 0);
    $request_obj->update_one_credits($_POST['valid']);
    header('Location: account.php');
    exit();
}

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <!-- CSS  -->
    <link rel="stylesheet" href="assets/css/account.css">
    <link rel="stylesheet" href="assets/css/normal_header.css">

    <!-- JS  -->
    <script src="https://kit.fontawesome.com/ba74dd8982.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="assets/js/account.js" defer></script>

    <title>Mon compte</title>
</head>
<body>


<?php if ($session->get_session_user()['Role'] == 1 && (isset($_POST['validate-resevation']) || isset($_POST['denied-resevation']))){ ?>
            <div id="popup" class="popup">
                <div class="popup-content">
                    <h2>Confirmer</h2>
                    <?php
                    print_r($_POST);
                    if (isset($_POST["validate-resevation"])) {?>
                    <p>voulez-vous vraiment confirmer cette commande ?</p>
                    <?php } else {?>
                    <p>voulez-vous vraiment refuser cette commande?</p>
                    <?php } ?>
                    <div class="containerBtn">
                        <form action="#" method="post">
                            <label for="validate-resevation"></label>
                            <input type="hidden" name="<?= isset($_POST["validate-resevation"]) ? "valid" : "deny" ?>" value="<?= $_POST["validate-resevation"] ?? $_POST["denied-resevation"] ?>">

                            <button id="confirmation_acceptBtn" class="btn">Accepter</button>
                        </form>
                        <form action="#" method="post">
                            <label for="denyBtn"></label>
                            <button id="confirmation_denyBtn" class="btn">Refuser</button>
                        </form>
                    </div>
                </div>
              </div>
    <script>document.body.classList.add("modal-open");</script>
<?php
} ?>

<?php include '../app/Views/partials/normal_header.php'; ?>
        <section class="section_infos_customer">
            <div class="container_infos new_section">
                <div>
                    <h2>Mon compte</h2>
                    <?php if ($session->get_session_user()['Role'] == 1) { ?>
                        <h3>admin</h3>
                    <?php } else {?>
                        <h3>client</h3>
                    <?php } ?>
                </div>
                <p class="name"><?= $session->get_session_user()['First_name'] . ' ' . $session->get_session_user()['Last_name'] ?></p>
                <p><?= $session->get_session_user()['Email'] ?></p>
                <button>Changer</button>
            </div>
            <div class="container_appointment new_section">
                <div>
                    <h3>Prendre rendez-vous</h3>
                    <p>Vos rendez-vous sera envoyé à SkinBeauty avec vos informations personnelles, veuillez vérifier que votre email et votre prénom soit correct, veuillez noter que vous disposez de 5 jours avant la résevation min avant d'annuler merci d'avance</p>
                </div>
                <div>
                    <i class="fa-regular fa-calendar icon_calendar fa-6x" style="color: #ffffff;"></i>
                </div>
            </div>
        </section>
        <section class="stat section-stat">
            <?php if ($_SESSION['auth']['Role'] === 1){ ?>
                <section class="new_section statistics-services">
                    <canvas id="statistics-services" height="400"></canvas>
                </section>
                <section class="new_section statistics-peoples">
                    <canvas id="statistics-peoples" height="400"></canvas>
                </section>
            <?php } ?>
        </section>
        <section class="reservations-section new_section">
            <h4 class="reservations-title">Mes réservations</h4>
            <table class="reservations-table">
                <thead>
                <tr class="table-header-row">
                    <th class="table-header">Date</th>
                    <th class="table-header">Services</th>
                    <th class="table-header">Formules</th>
                    <th class="table-header">Prix</th>
                    <th class="table-header">Heure</th>
                    <?php if ($session->get_session_user()['Role'] == 0){ ?>

                        <th class="table-header">Status</th>
                        <th class="table-header">Credits</th>

                    <?php } ?>
                    <th class="table-header"></th>
                </tr>
                </thead>
                <tbody>
                <?php

                $list_services = [];
                foreach ($request_obj->get_services() as $service) {
                    $list_services[$service["name_service"]] = 0;
                }

                $request_reservation = $session->get_session_user()['Role'] == 0 ? $request_obj->get_reservation_from_user($session->get_session_user()['Id_people']) : $request_obj->get_all_reservations();
                foreach ($request_reservation as $reservation) {
                    $list_services[$reservation["name_service"]] += 1;

                    $reservationDate = DateTime::createFromFormat('Y-m-d', $reservation["Start_date_reservation"]);
                    $today = new DateTime('now');

                    if ($reservation["credits_left"] > 0 || $reservationDate >= $today){
                    ?>

                    <tr class="table-row">
                        <td class="table-cell" data-label="Date">
                            <?php

                            $tomorrow = (clone $today)->modify('+1 day');
                            $fiveDaysAfter = (clone $today)->add(new DateInterval('P5D'));

                            if ($reservationDate->format('Y-m-d') == $today->format('Y-m-d')) {
                                echo "Aujourd'hui";
                            }
                            elseif ($reservationDate->format('Y-m-d') == $tomorrow->format('Y-m-d')) {
                                echo "Demain";
                            } elseif ($reservationDate < $today && $_SESSION['auth']['Role'] == 0) { ?>
                                <label for="new-date"></label>
                                <input type="date" name="new-date" id="new-date">
                            <?php }
                            else {
                                $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
                                $formatter->setPattern('EE d MMMM');
                                echo $formatter->format($reservationDate);
                            }
                            ;
                            ?></td>
                        <td class="table-cell" data-label="Services"><?= $reservation["name_service"] ?></td>
                        <td class="table-cell" data-label="Formules"><?= $reservation["name_formula"] ?></td>
                        <td class="table-cell" data-label="Prix"><?= $reservation["Price"] ?> €</td>
                        
                        <td class="table-cell" data-label="Time"><?php

                            if ($reservationDate->format('Y-m-d') >= $today->format('Y-m-d')) {
                                echo $reservation["Hour_start"];
                            } elseif ($reservationDate < $today  && $_SESSION['auth']['Role'] == 0){?>

                                <label for="new_time_reservation"></label>
                                <select name="new_time_reservation" id="new_time_reservation">
                                </select>
                                
                            <?php
                            }
                            
                            ?></td>

                        <?php if ($_SESSION['auth']['Role'] == 0){ ?>
                            <td class="table-cell" data-label="Status"><?php
                                if (($reservationDate < $today)){
                                    echo "en attente";
                                    if ($reservation["State"] !== 3){
                                        $request_obj->update_reservation_state($reservation["id_reservation"], 3);
                                    }
                                }else{
                                    echo $reservation["State"] == "1" ? "en attente" : "valider";
                                }

                                ?></td>
                            <td class="table-cell" data-label="Credits"><?= $reservation["credits_left"] ?></td>
                            <td class="table-cell"><?= $reservationDate > $fiveDaysAfter ? "<a class='cancel-link' href=''>Annuler</a>" : "<a class='cancel-link-disable'>Annuler</a>" ?></td>
                        <?php } else { ?>
                            <?php if ($reservation["State"] === 1) { ?>
                                <!--  l'état est à 1 donc en attente -->
                                <td class='table-cell accept_or_denied_btn' data-label=''>
                                    <form action="#" method="post">
                                        <input type="hidden" name="validate-resevation" value="<?= $reservation["id_reservation"] ?>">
                                        <button><i class="fa-solid fa-check fa-xs"></i></button>
                                    </form>
                                    <form action="#" method="post">
                                        <input type="hidden" name="denied-resevation" value="<?= $reservation["id_reservation"] ?>">
                                        <button><i class="fa-solid fa-xmark fa-xs"></i></button>
                                    </form>
                                </td>
                            <?php } elseif ($reservation["State"] === 0) {
                                if ($reservationDate < $today) {
                                    $request_obj->update_reservation_state($reservation["id_reservation"], 3);
                                }
                                ?>
                                <!--  l'état est à 0 donc accepter -->
                                <td class="table-cell">
                                    valider
                                </td>

                            <?php } elseif ($reservation["State"] === 2) {
                                if ($reservationDate < $today) {
                                    $request_obj->update_reservation_state($reservation["id_reservation"], 3);
                                }
                                ?>
                                <!--  l'état est à 2 donc refuser -->
                                <td class="table-cell ">
                                    refuser
                                </td>
                            <?php
                            } elseif ($reservation["State"] === 3){?>
                                <td class="table-cell">
                                    en attente
                                </td>
                        <?php } ?>
                    </tr>
                    <?php
                }
                }
                }

                ?>
                </tbody>
            </table>
        </section>


<div id="service_value" data-value="<?= json_encode(array_values($list_services)); ?>"></div>
<div id="service_name" data-name="<?= htmlspecialchars(json_encode(array_keys($list_services))); ?>"></div>

<div id="data_number_peoples_by_month" data-peoples="<?= htmlspecialchars(json_encode(array_values($data_people))); ?>"></div>


</body>
</html>
