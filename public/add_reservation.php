<?php
// Required files //
use Models\HandlerRequestsUsers;

require_once "../config/database.php";
require_once "../app/Models/HandlerRequestsUsers.php";
require_once "../app/Sessions/HandlerSessions.php";
require_once "../app/Models/HandleConnexion.php";
require_once "../app/Models/HandleRegister.php";

$request_obj = new HandlerRequestsUsers();

echo "<pre>";
print_r($request_obj->get_services_order_by_formulas());
echo "</pre>";
// Get the list of users for the select input
$users = $request_obj->get_all_users(); // Assuming this method exists to fetch all users

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['full_name'])) {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $start_date = $_POST['start_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $formula = $_POST['formula'];

        // Split full name into first and last name
        $name_parts = explode(' ', $full_name);
        $first_name = $name_parts[0];
        $last_name = isset($name_parts[1]) ? $name_parts[1] : '';

        // Check if user exists
        if ($request_obj->user_exists($email)) {
            // User exists, add the reservation
            $request_obj->add_reservation($email, $first_name, $last_name, $start_date, $start_time, $end_time, $formula);
            echo "<p>Réservation ajoutée avec succès.</p>";
        } else {
            // User doesn't exist, offer to add the user
            echo "<p>L'utilisateur n'existe pas. <button id='add_user'>Ajouter l'utilisateur</button></p>";
        }
    } elseif (isset($_POST['new_first_name']) && isset($_POST['new_last_name'])) {
        // Handle the creation of a new user
        $first_name = $_POST['new_first_name'];
        $last_name = $_POST['new_last_name'];
        $email = $_POST['new_email']; // You could add an email field for the new user
        // Logic to add new user to the database
        $request_obj->add_user($first_name, $last_name, $email); // Assuming an add_user method exists
        echo "<p>Nouvel utilisateur ajouté: $first_name $last_name</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Réservation</title>
    <link rel="stylesheet" href="assets/css/add_reservation.css">
</head>
<body>
<div class="container">
    <h1>Ajouter une Réservation</h1>
    <form method="POST" action="">
        <label for="full_name">Nom Complet:</label>
        <select id="full_name" name="full_name" required>
            <?php
            foreach ($users as $user) {
                $full_name = $user['first_name'] . ' ' . $user['last_name'];
                echo "<option value=\"$full_name\">$full_name</option>";
            }
            ?>
        </select>


        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <button type="button" id="create_user_btn">Créer un nouvel utilisateur</button>

        <!-- Hidden inputs for new user creation, shown only if 'Créer un nouvel utilisateur' is clicked -->
        <div id="new_user_fields" style="display: none;">
            <label for="new_first_name">Prénom:</label>
            <input type="text" id="new_first_name" name="new_first_name">

            <label for="new_last_name">Nom:</label>
            <input type="text" id="new_last_name" name="new_last_name">

            <label for="new_email">Email:</label>
            <input type="email" id="new_email" name="new_email" required>
        </div>

        <label for="start_date">Date de début:</label>
        <input type="date" id="start_date" name="start_date" required>

        <label for="start_time">Heure de début:</label>
        <input type="time" id="start_time" name="start_time" required>

        <label for="end_time">Heure de fin:</label>
        <input type="time" id="end_time" name="end_time" required>

        <label for="service">Service :</label>
        <select name="service" id="service">
            <?php foreach ($request_obj->get_services() as $service){
                echo "<option value='".$service['id_services']."'>".$service['name_service']."</option>";
            } ?>
        </select>

        <label for="formula">Formule:</label>
        <input type="text" id="formula" name="formula" required>


        <button type="submit">Ajouter Réservation</button>
    </form>
</div>

<script>
    document.getElementById('create_user_btn').addEventListener('click', function() {
        // Toggle the display of the new user input fields
        var newUserFields = document.getElementById('new_user_fields');
        newUserFields.style.display = newUserFields.style.display === 'none' ? 'block' : 'none';
    });

    // Handle the 'Créer un nouvel utilisateur' button
    document.getElementById('create_user_btn').addEventListener('click', function() {
        // Show the input fields for new user creation
        document.getElementById('new_user_fields').style.display = 'block';
    });
    document.getElementById('service').addEventListener("change", function(){
        console.log(document.getElementById('service'));
    });

</script>

</body>
</html>
