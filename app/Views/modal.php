<?php

function modal ($service)
{ ?>
    <h1 id='TitleModel'> <?= $service["first_name"]," ", $service["last_name"], " - ", $service["name_service"]?> </h1>
    <form action='' method='post' class="modal-form">
        <input type='hidden' name='idCard' value='<?= $service['id_reservation'] ?>'>

        <div class="form-group">
            <label for='first_name'>Nom</label>
            <input type='text' id='first_name' name='first_name' value='<?= $service['first_name'] ?>'>
        </div>

        <div class="form-group">
            <label for='last_name'>Prénom</label>
            <input type='text' id='last_name' name='last_name' value='<?= $service['last_name'] ?>'>
        </div>

        <div class="form-group">
            <label for='start_date_reservation'>Date de début</label>
            <input type='date' id='start_date_reservation' name='Start_date_reservation' value='<?= $service['Start_date_reservation'] ?>'>
        </div>

        <div class="form-group">
            <label for='hour_start'>Heure de début</label>
            <input type='time' min="9:00" max="17:00" id='hour_start' name='Hour_start' value='<?= $service['Hour_start'] ?>'>
        </div>

        <div class="form-group">
            <label for='hour_end'>Heure de fin</label>
            <input type='time' id='hour_end' name='Hour_end' value='<?= $service['Hour_end'] ?>'>
        </div>

        <div class="form-group">
            <label for='credits_left'>Crédits restants</label>
            <input type='number' id='credits_left' name='credits_left' value='<?= $service['credits_left'] ?>'>
        </div>

        <div class="form-group">
            <label for='name_formula'>Formule</label>
            <input type='text' id='name_formula' name='name_formula' value='<?= $service['name_formula'] ?>'>
        </div>

        <div class="form-group">
            <label for='price'>Prix</label>
            <input type='number' id='price' name='Price' value='<?= $service['Price'] ?>'>
        </div>

        <div class="form-group">
            <input type='submit' value='Modifier' class="btn-submit">
        </div>
    </form>


<?php } ?>