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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dossier où les fichiers seront uploadés
    $target_dir = "assets/images/Services/";

    // Type de fichier
    $imageFileType = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));

    // Générer un nom de fichier unique en utilisant l'horodatage et un ID unique
    $new_filename = uniqid() . '.' . $imageFileType;

    // Chemin complet du fichier uploadé avec le nouveau nom
    $target_file = $target_dir . $new_filename;

    // Variable qui stockera s'il y a des erreurs
    $uploadOk = 1;
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    if ($_FILES["file"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Vérifiez si `$uploadOk` est toujours à 1 (pas d'erreurs)
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";
            $request_obj->add_service($_POST['name_service'], $_POST['details'], $new_filename);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SkinBeauty - Ajouter un service</title>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/ba74dd8982.js" crossorigin="anonymous"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/normal_header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/upload_form.css"> <!-- Fichier CSS pour styliser le formulaire -->
</head>
<body>

<?php include '../app/Views/partials/normal_header.php'; ?>

<section class="upload-section">

    <form action="#" method="POST" enctype="multipart/form-data" class="upload-form">
        <div class="form-group">
            <label for="file">Sélectionner une image :</label>
            <input type="file" name="file" id="file">
        </div>

        <div class="form-group">
            <label for="name_service">Nom du service :</label>
            <input type="text" name="name_service" id="name_service" placeholder="Nom du service">
        </div>

        <div class="form-group">
            <label for="details">Détails du service :</label>
            <textarea name="details" id="details" placeholder="Détails du service"></textarea>
        </div>

        <button type="submit" class="btn-upload">Télécharger</button>
    </form>
</section>

<?php include '../app/Views/partials/footer.html'; ?>

</body>
</html>