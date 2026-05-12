<?php

namespace App\Core;

use App\DAO\UserDAO;

class Authenticator
{
    private UserDAO $userDAO;

    // Maak de userDAO aan om door de Authenticator klasse te halen.
    public function __construct() 
    {
      $this->userDAO = new UserDAO();
    }

    // Een functie waarbij de user wordt gecheckt (bestaat de username?) en het wachtwoord uit de user wordt gehaald.
    public function login(string $username, string $password): bool
    {
        $user = $this->userDAO->getByUsername($username);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->getPassword())) {
            return false;
        }

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();

        return true;
    }

}