<?php

namespace Models;
use Database;

class HandleConnexion
{
    private string $email;
    private string $password;
    private Database | array $bdd;
    private HandlerRequestsUsers $request_obj;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;

        $this->bdd = new Database();
        $this->bdd = $this->bdd->connect_bdd();

        $this->request_obj = new HandlerRequestsUsers();

    }

    public function verification_connexion_email_password(): array
    {

        if ($this->request_obj->user_exists($this->email)){

            $user_info = $this->request_obj->get_infos_user($this->email);

            if (password_verify($this->password, $user_info["password"])){
                return ["state" => true, "message" => "success", "user_info" => $user_info];
            }else {
                return ["state" => false, "message" => "email ou mot de passe incorrect"];
            }
        } else{
            return ["state" => false, "message" => "email ou mot de passe incorrect"];
        }
    }

}