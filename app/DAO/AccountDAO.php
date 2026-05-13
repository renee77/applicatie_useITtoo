<?php

namespace App\DAO;

use App\Core\Database;
use App\Models\Account;
use PDO;

class AccountDAO
{
    private \PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getById(int $id): ?Account
    {
        $sql = "SELECT * FROM `account` WHERE `id` = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            return new Account(
                $user['email'],
                $user['gebruikersnaam'],
                $user['wachtwoord'],
                $user['created_at'],
                $user['geboortedatum'],
                $user['type'],
                $user['voornaam'],
                $user['achternaam'],
                $user['telefoon'],
                $user['deleted_at'],
                $user['account_id']
            );
        } else {
            return null;
        }
    }

    // Haal de user op op basis van de gebruikersnaam
    //(voor de validatie van het inloggen)
    public function getByUsername(string $gebruikersnaam): ?Account
    {
        // De SQL query voorbereiden
        $sql = "SELECT * FROM users 
        WHERE gebruikersnaam = :gebruikersnaam";
        // De informatie uit de database opvragen via een SQL query
        // (SQL-Injectie voorkomen via bindValue)
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':gebruikersnaam', $gebruikersnaam);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        // Als er wel iets bestaat, wordt er een nieuwe userklasse
        // aangemaakt met de opgehaalde informatie uit de database
        if ($user) {
            return new Account(
                $user['account_id'],
                $user['email'],
                $user['gebruikersnaam'],
                $user['wachtwoord'],
                $user['created_at'],
                $user['geboortedatum'],
                $user['type'],
                $user['voornaam'],
                $user['achternaam'],
                $user['telefoon'],
                $user['deleted_at']
            );
        // Als er niets bestaat, een null-value terugsturen
        } else {
            return null;
        }
    }
}
