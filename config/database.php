<?php
require_once "config.php";

class Database
{
    private string $host;
    private string $dbname;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->host = DB_HOST;
        $this->dbname = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
    }

    public function connect_bdd()
    {
        try {
            $bdd = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
            );

            $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return [True, $bdd];

        } catch (PDOException $e) {
            return [False, "Erreur de connexion à la base de données : " . $e->getMessage()];
        }
    }
}

