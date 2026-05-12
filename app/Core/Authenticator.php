<?php

namespace App\Core;

use App\Core\Database;
use App\DAO\AccountDAO;
use PDO;

class Authenticator
{
    private AccountDAO $accountDAO;

    // Maak de userDAO aan om door de Authenticator klasse te halen.
    public function __construct()
    {
        $this->accountDAO = new AccountDAO(Database::getConnection());
    }

    // Een functie waarbij de user wordt gecheckt (bestaat de username?) 
    // en het wachtwoord uit de user wordt gehaald.
    public function login(string $gebruikersnaam, string $wachtwoord): bool
    {
        $account = $this->accountDAO->getByUsername($gebruikersnaam);

        if (!$gebruikersnaam) {
            return false;
        }

        if (!password_verify($wachtwoord, $account->getPassword())) {
            return false;
        }

        $_SESSION['account_id'] = $account->getId();
        $_SESSION['gebruikersnaam'] = $account->getUsername();

        return true;
    }
}
