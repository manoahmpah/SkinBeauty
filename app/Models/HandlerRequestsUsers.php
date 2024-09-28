<?php

namespace Models;
use Database;

class HandlerRequestsUsers
{

    private Database $database;
    private $bdd;

    public function __construct()
    {
        $this->database = new Database();
        $this->bdd = $this->database->connect_bdd();
    }

    public function update_reservation_state($id_reservation, $state): void
    {
        $req = $this->bdd[1]->prepare('UPDATE reservation SET State = :state WHERE id_reservation = :id_reservation');
        $req->execute([
            ':id_reservation' => $id_reservation,
            ':state' => $state
        ]);
    }

    public function update_one_credits($id_reservation): void
    {
        $req = $this->bdd[1]->prepare('UPDATE reservation SET credits_left = credits_left - 1 WHERE id_reservation = :id_reservation');
        $req->execute([
            ':id_reservation' => $id_reservation
        ]);
    }

    public function user_exists(string $email): bool
    {
        $req = $this->bdd[1]->prepare('SELECT * FROM people WHERE email = :email');
        $req->execute([':email' => $email]);
        return $req->fetch() !== false;
    }

    public function get_services(): array
    {
        $req = $this->bdd[1]->prepare('SELECT * FROM services');
        $req->execute();
        return $req->fetchAll();
    }

    public function get_infos_user($email)
    {
        $req = $this->bdd[1]->prepare('SELECT * FROM people WHERE email = :email');
        $req->execute([':email' => $email]);
        return $req->fetch();
    }

    public function get_services_order_by_formulas()
    {
        $req = $this->bdd[1]->prepare('SELECT * FROM categories 
                                        JOIN services ON categories.id_services = services.id_services
                                        JOIN formulas ON categories.id_category = formulas.id_category
                                        ORDER BY services.name_service ASC
                                        ');
        $req->execute();
        return $req->fetchAll();
    }

//    public function update_user($id, $first_name, $last_name, $email, $password, $role)
//    {
//        $database = new Database();
//        $bdd = $database->connect_bdd();
//
//        if ($bdd[0]) {
//            $bdd = $bdd[1];
//            $query = $bdd->prepare("UPDATE people SET first_name = :first_name, last_name = :last_name, email = :email, password = :password, role = :role WHERE id_people = :id");
//            $query->execute([
//                'id' => $id,
//                'first_name' => $first_name,
//                'last_name' => $last_name,
//                'email' => $email,
//                'password' => $password,
//                'role' => $role
//            ]);
//        }
//    }

    public function register_user($first_name, $last_name, $email, $password): void {
        $req = $this->bdd[1]->prepare('INSERT INTO people (first_name, last_name, email, password, Is_admin, Creation_date) VALUES (:first_name, :last_name, :email, :password, :role, NOW())');
        $req->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':role' => 0
        ]);
    }

    public function get_reservation_from_user($id_user)
    {
        $req = $this->bdd[1]->prepare('SELECT id_reservation, Start_date_reservation, Hour_start, credits_left, name_formula, Price, State, name_service FROM reservation 
                                                                                     JOIN formulas ON reservation.Id_formula = formulas.Id_formula 
                                                                                     JOIN name_formula ON formulas.Id_name_formula = name_formula.id_name_formula 
                                                                                     JOIN categories ON formulas.Id_category = categories.Id_category
                                                                                     JOIN services ON categories.Id_services = services.Id_services
                                                                                     WHERE id_people = :id_people ORDER BY Start_date_reservation ASC'
        );
        $req->execute([':id_people' => $id_user]);
        return $req->fetchAll();
    }

    public function find_reservation_by_id($id_reservation)
    {
        $req = $this->bdd[1]->prepare('SELECT id_reservation, first_name, last_name, Start_date_reservation, Hour_start, Hour_end, credits_left, name_formula, Price, State, name_service 
                               FROM reservation 
                               JOIN formulas ON reservation.Id_formula = formulas.Id_formula 
                               JOIN name_formula ON formulas.Id_name_formula = name_formula.id_name_formula 
                               JOIN categories ON formulas.Id_category = categories.Id_category
                               JOIN services ON categories.Id_services = services.Id_services
                               JOIN people ON reservation.Id_people = people.id_people
                               WHERE id_reservation = :id_reservation');
        $req->execute([':id_reservation' => $id_reservation]);
        return $req->fetch();
    }

    public function get_all_reservations()
    {
        $req = $this->bdd[1]->prepare('SELECT id_reservation, Start_date_reservation, Hour_start, Hour_end, credits_left, name_formula, Price, State, name_service 
                               FROM reservation 
                               JOIN formulas ON reservation.Id_formula = formulas.Id_formula 
                               JOIN name_formula ON formulas.Id_name_formula = name_formula.id_name_formula 
                               JOIN categories ON formulas.Id_category = categories.Id_category
                               JOIN services ON categories.Id_services = services.Id_services
                               ORDER BY Start_date_reservation ASC, Hour_start ASC');

        $req->execute();
        return $req->fetchAll();
    }

    public function get_creation_date_users_by_month()
    {
        $req = $this->bdd[1]->prepare('SELECT COUNT(id_people) as nb_users, MONTH(Creation_date) as month FROM people WHERE YEAR(Creation_date) = YEAR(NOW()) GROUP BY MONTH(Creation_date)');
        $req->execute();
        return $req->fetchAll();
    }

    public function get_categories_by_service(int $id_service): array
    {
        $req = $this->bdd[1]->prepare('SELECT Id_formula, name_category, Price, name_formula  FROM categories 
                                                    JOIN formulas ON categories.Id_category = formulas.Id_category 
                                                    JOIN name_formula ON formulas.id_name_formula = name_formula.id_name_formula 
                                                    WHERE Id_services = :Id_services');

        $req->execute([':Id_services' => $id_service]);
        return $req->fetchAll();
    }

    public function get_services_by_id(int $id_services): array|bool
    {
        $req = $this->bdd[1]->prepare('SELECT * FROM services WHERE Id_services = :id_services');
        $req->execute([':id_services' => $id_services]);
        return $req->fetch();
    }

    public function get_credit_from_formulas($id_formula){
        $req = $this->bdd[1]->prepare('SELECT credits FROM formulas WHERE Id_formula = :id_formula');
        $req->execute(
            [':id_formula' => $id_formula]
        );
        return $req->fetch();
    }

    public function add_service($name_service, $details, $name_image): void
    {
        $req = $this->bdd[1]->prepare('INSERT INTO services (name_service, details, name_Image) VALUES (:name_service, :details, :name_image)');
        $req->execute([
            ':name_service' => $name_service,
            ':details' => $details,
            ':name_image' => $name_image
        ]);
    }

    public function modify_reservation($id, $first_name, $last_name, $start_date_reservation, $hour_start, $hour_end, $credits_left, $name_formula, $price){
        $req = $this->bdd[1]->prepare('UPDATE reservation SET first_name = :first_name, last_name = :last_name, Start_date_reservation = :start_date_reservation, Hour_start = :hour_start, Hour_end = :hour_end, credits_left = :credits_left, name_formula = :name_formula, Price = :price WHERE id_reservation = :id');
        $req->execute([
            ':id' => $id,
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':start_date_reservation' => $start_date_reservation,
            ':hour_start' => $hour_start,
            ':hour_end' => $hour_end,
            ':credits_left' => $credits_left,
            ':name_formula' => $name_formula,
            ':price' => $price
        ]);
    }

    public function add_reservation($email, $first_name, $last_name, $start_date, $start_time, $end_time, $formula) {
        // Étape 1 : Vérifier si l'utilisateur existe
        if (!$this->user_exists($email)) {
            // Étape 2 : Créer l'utilisateur si nécessaire
            $this->create_default_user($first_name, $last_name, $email);
        }

        // Étape 3 : Récupérer les informations de l'utilisateur
        $user = $this->get_infos_user($email);
        $id_people = $user['id_people'];

        // Étape 4 : Récupérer les crédits de la formule sélectionnée
        $credits = $this->get_credit_from_formulas($formula)['credits'];

        // Étape 5 : Insérer la réservation dans la base de données
        $req = $this->bdd[1]->prepare('INSERT INTO reservation (Start_date_reservation, Hour_start, Hour_end, Id_formula, Id_people, credits_left, state) 
                                    VALUES (:date, :start_time, :end_time, :formula, :id_people, :credits, 1)');
        $req->execute([
            ':date' => $start_date,
            ':start_time' => $start_time,
            ':end_time' => $end_time,
            ':formula' => $formula,
            ':id_people' => $id_people,
            ':credits' => $credits
        ]);
    }

    public function create_default_user($first_name, $last_name, $email) {
        // Créer un utilisateur par défaut
        $password = password_hash('defaultpassword', PASSWORD_BCRYPT);
        $req = $this->bdd[1]->prepare('INSERT INTO people (first_name, last_name, email, password, ls_admin, Creation_date) 
                                    VALUES (:first_name, :last_name, :email, :password, 0, NOW())');
        $req->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':password' => $password
        ]);
    }

    public function get_all_users() {
        $req = $this->bdd[1]->prepare('SELECT first_name, last_name FROM people');
        $req->execute();
        return $req->fetchAll();
    }

    public function add_reservation_by_user($user_id, $start_date, $start_time, $end_time, $formula) {
        $sql = "INSERT INTO reservations (user_id, start_date, start_time, end_time, formula)
            VALUES (:user_id, :start_date, :start_time, :end_time, :formula)";
        $stmt = $this->bdd->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->bindParam(':formula', $formula);
        $stmt->execute();
    }


}