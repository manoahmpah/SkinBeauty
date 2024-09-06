<?php

namespace Sessions;

class HandlerSessions
{
    public function set_session_user($email, $first_name, $last_name, $id_people, $role, $phone): void
    {
        $_SESSION['auth'] = [
            'Email' => $email,
            'First_name' => $first_name,
            'Last_name' => $last_name,
            'Id_people' => $id_people,
            'Role' => $role,
            'Phone' => $phone
        ];
    }

    public function unset_session_user(): void
    {
        unset($_SESSION['auth']);
    }

    public function is_session_user(): bool
    {
        return isset($_SESSION['auth']);
    }

    public function get_session_user(): array
    {
        return $_SESSION['auth'];
    }

    public function start_session(): void
    {
        session_start();
    }
}