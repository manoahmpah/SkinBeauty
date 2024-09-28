<?php
require "../app/Views/modal.php";

function calendar($request_obj, $session): void
{
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
                modal($reservation);
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
<a href="add_reservation.php" class="add_reservation">
    <i class="fa-solid fa-plus" style="color: #ffffff;"></i>
    <h2>ajouter une resrvation</h2>
</a>
</section>

<form action="" method="post" id="modalForm">
    <input type="hidden" name="idCard" id="idCard">
</form>
<!--  Data check if admin -->


<?php

if ($session->get_session_user()["Role"] == 1){
    ?>
    <div id="dataReservation" data-reservation="<?= htmlspecialchars(json_encode($request_obj->get_all_reservations())) ?>"></div>
<?php }

}
?>

