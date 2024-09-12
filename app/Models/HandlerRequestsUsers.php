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

    public function update_one_credits($id_reservation)
    {
        $req = $this->bdd[1]->prepare('UPDATE reservation SET credits_left = credits_left - 1 WHERE id_reservation = :id_reservation');
        $req->execute([
            ':id_reservation' => $id_reservation
        ]);
    }

    public function delete_one_credit()
    {

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

    public function update_user($id, $first_name, $last_name, $email, $password, $role)
    {
        $database = new Database();
        $bdd = $database->connect_bdd();

        if ($bdd[0]) {
            $bdd = $bdd[1];
            $query = $bdd->prepare("UPDATE people SET first_name = :first_name, last_name = :last_name, email = :email, password = :password, role = :role WHERE id_people = :id");
            $query->execute([
                'id' => $id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $password,
                'role' => $role
            ]);
        }
    }

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

    public function add_reservation($date, $time, $formula, $id_people, $credits_left): void
    {
        $req = $this->bdd[1]->prepare('INSERT INTO reservation (Start_date_reservation, Hour_start, Id_formula, Id_people, credits_left, State) VALUES (:date, :time, :formula, :id_people, :credits_left, 1)');
        $req->execute([
            ':date' => $date,
            ':time' => $time,
            ':formula' => $formula,
            ':id_people' => $id_people,
            ':credits_left' => $credits_left
        ]);


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
}