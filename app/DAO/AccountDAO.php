<?php

namespace App\DAO;

use App\Models\Account;
use App\Models\AccountType;
use PDO;
use DateTime;

class AccountDAO
{
    private \PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getById(int $id): ?Account
    {
        // account_id gemaakt van id
        $sql = "SELECT * FROM `account` WHERE `account_id` = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            return new Account(
                $user['email'],
                $user['gebruikersnaam'],
                $user['wachtwoord_hash'],
                new DateTime($user['created_at']),
                new DateTime($user['geboortedatum']),
                // AccountType::from($user['type']),
                $user['voornaam'],
                $user['achternaam'],
                $user['telefoon'],
                $user['deleted_at'] ?
                new DateTime($user['deleted_at']) : null,
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
        // De SQL query voorbereiden EB: users veranderd in account
        $sql = "SELECT * FROM account 
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
                $user['email'],
                $user['gebruikersnaam'],
                $user['wachtwoord_hash'],
                new DateTime($user['created_at']),
                new DateTime($user['geboortedatum']),
                // AccountType::from($user['type']),
                $user['voornaam'],
                $user['achternaam'],
                $user['telefoon'],
                $user['deleted_at'] ?
                new DateTime($user['deleted_at']) : null,
                $user['account_id']
            );
            // Als er niets bestaat, een null-value terugsturen
        } else {
            return null;
        }
    }

    public function getTypeByAccountId(int $account_id): ?string
    {
        // Loop door alle mogelijke accounttypes (klant, beheer)
        foreach (AccountType::cases() as $type) {
            // Zoek het account_id op in de bijbehorende tabel
            // Tabelnaam wordt direct in de query gezet omdat bindValue
            // niet werkt voor tabelnamen, alleen voor waardes
            $sql = "SELECT account_id FROM $type->value 
                WHERE $type->value.account_id = :account_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':account_id', $account_id);
            $stmt->execute();

            $typeVan = $stmt->fetch(\PDO::FETCH_NUM);

            // fetch() geeft false terug als er niets gevonden is
            // Als er wel iets gevonden is, is dit het type van het account
            if ($typeVan !== false) {
                return $type->value;
            }
        }

        // Account staat in geen van de tabellen — geen type bekend
        return null;
    }
}
