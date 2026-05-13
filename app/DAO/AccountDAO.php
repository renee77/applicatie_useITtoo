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

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM account");
        return array_map(
            fn($row) => new Account(
                $row['account_id'],
                $row['voornaam'],
                $row['email'],
                $row['gebruikersnaam'],
                $row['wachtwoord']
            ),
            $stmt->fetchAll(\PDO::FETCH_ASSOC)
        );
    }

    public function findById(int $id): ?Account
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? new Account(
            $row['account_id'],
            $row['voornaam'],
            $row['email'],
            $row['gebruikersnaam'],
            $row['wachtwoord']
        ) : null;
    }

    // Haal de user op op basis van de gebruikersnaam
    //(voor de validatie van het inloggen)
    public function getByUsername(string $gebruikersnaam): ?Account
    {
        // De informatie uit de database opvragen via een SQL query
        // (SQL-Injectie voorkomen via bindValue)
        $stmt = $this->db->prepare("SELECT * FROM users 
        WHERE gebruikersnaam = :gebruikersnaam");
        $stmt->bindValue(':gebruikersnaam', $gebruikersnaam);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        // Als er niets bestaat, een null-value terugsturen
        if (!$row) {
            return null;
        }
        // Als er wel iets bestaat, wordt er een nieuwe userklasse
        // aangemaakt met de opgehaalde informatie uit de database
        return new Account(
            $row['account_id'],
            $row['voornaam'],
            $row['email'],
            $row['gebruikersnaam'],
            $row['wachtwoord']
        );
    }
}
