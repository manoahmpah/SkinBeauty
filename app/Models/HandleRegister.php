<?php

namespace Models;

require_once "../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Sessions\HandlerSessions;

class HandleRegister
{
    private string $smtpHost;
    private int $smtpPort;
    private string $smtpUser;
    private string $smtpPass;
    private string $fromEmail;
    private string $fromName;
    private int $randomNumber;
    private string $template_path;
    private HandlerRequestsUsers $request_obj;
    private HandlerSessions $session;

    public function __construct()
    {

        // Email configuration
        $this->randomNumber = mt_rand(1000, 9999);
        $this->smtpHost = 'smtp.gmail.com';
        $this->smtpPort = 587;
        $this->fromEmail = 'auth-skinBeauty@gmail.com';
        $this->fromName = 'SkinBeauty';
        $this->smtpUser = 'exposeun@gmail.com';
        $this->smtpPass = 'kaxoeopjrryjjpzu';
        $this->template_path = __DIR__ . '/../Views/email/template_email.php';

        // object Sessions & Requests
        $this->request_obj = new HandlerRequestsUsers();
        $this->session = new HandlerSessions();
    }

    public function verification_inscription($email, $password, $password_confirmation): array
    {
        if ($this->request_obj->user_exists($email)) {
            return ["state" => false, "message" => "Cet email est déjà utilisé"];
        } else {
            if ($password !== $password_confirmation) {
                return ["state" => false, "message" => "Les mots de passe ne correspondent pas"];
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ["state" => false, "message" => "L'email n'est pas valide"];
            }

            if (!preg_match('/[0-9]/', $password)) {
                return ["state" => false, "message" => "Le mot de passe doit contenir au moins un chiffre"];
            }

            if (!preg_match('/[a-z]/', $password)) {
                return ["state" => false, "message" => "Le mot de passe doit contenir au moins une lettre minuscule"];
            }

            if (!preg_match('/[\W_]/', $password)) {
                return ["state" => false, "message" => "Le mot de passe doit contenir au moins un caractère spécial"];
            }

            return ["state" => true, "message" => "success", "user_info"];
        }
    }


    /**
     * @throws Exception
     */
    public function send_email($first_name, $last_name, $email): array|bool
    {
        try {
            unset($_SESSION['code']['random_number']);
            $_SESSION['code']['random_number'] = $this->randomNumber;
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpUser;
            $mail->Password = $this->smtpPass;

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->smtpPort;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($email, $first_name . ' ' . $last_name);
            $mail->isHTML(true);
            $mail->Subject = 'Vérification de votre adresse email';

            ob_start();
            $firstName = htmlspecialchars($first_name);
            $lastName = htmlspecialchars($last_name);
            $randomNumber = htmlspecialchars($_SESSION['code']['random_number']);
            include $this->template_path;

            $mail->Body = ob_get_clean();
            $mail->AltBody = 'voici votre code de vérification : ' . $_SESSION['code']['random_number'] . ' il est valide que durant 10 min';

            $mail->send();


            return true;
        } catch (Exception $e) {

            return [false, throw new Exception($e->getMessage())];
        }

    }

    public function verification_code($code): array
    {

        if ($_SESSION['code']['random_number'] == $code) {
            if (isset($_SESSION['auth']['Email']) && $_SESSION['auth']['Email'] !== $_POST['email']) {
                $this->request_obj->update_email($_POST['email'], $_SESSION['auth']['Id_people']);
                header('Location: account.php');
                exit();
            } else {

                if (!$this->request_obj->user_exists($_POST['email_inscription'])) {
                    return ['state' => true, 'message' => 'success'];

                } else {
                    return ['state' => false, 'message' => 'Cet email est déjà utilisé'];
                }
            }
        }else{
            return ['state' => false, 'message' => 'Le code est incorrect'];
        }
    }
}