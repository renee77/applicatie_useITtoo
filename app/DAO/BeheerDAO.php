<?php

namespace App\DAO;

use App\Core\Database;
use App\Models\Beheer;
use App\Models\Beheerdersrol;
use App\Models\Account;
// use App\Models\AccountType;
use PDO;
use DateTime;

class BeheerDAO
{
    private \PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getByUsername(string $gebruikersnaam): ?Beheer
    {
        $sql = "SELECT 
            account.account_id,
            account.email,
            account.gebruikersnaam,
            account.wachtwoord_hash,
            account.created_at,
            account.geboortedatum,
            account.type,
            account.voornaam,
            account.achternaam,
            account.telefoon,
            account.deleted_at,
            beheer.rol,
            beheer.datum_in_dienst
        FROM account
        INNER JOIN beheer 
            ON beheer.account_id = account.account_id
        WHERE account.gebruikersnaam = :gebruikersnaam
        LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":gebruikersnaam", $gebruikersnaam, \PDO::PARAM_STR);
        $stmt->execute();

        $beheer = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($beheer) {
            return new Beheer(
                Beheerdersrol::from($beheer['rol']),
                new DateTime($beheer['datum_in_dienst']),
                $beheer['email'],
                $beheer['gebruikersnaam'],
                $beheer['wachtwoord_hash'],
                new DateTime($beheer['created_at']),
                new DateTime($beheer['geboortedatum']),
                // AccountType::from($beheer['type']),
                $beheer['voornaam'],
                $beheer['achternaam'],
                $beheer['telefoon'],
                $beheer['deleted_at'] ?
                new DateTime($beheer['deleted_at']) : null,
                $beheer['account_id']
            );
        } else {
            return null;
        }
    }


    public function getById(int $id): ?Beheer
    {
        $sql = "SELECT 
            account.account_id,
            account.email,
            account.gebruikersnaam,
            account.wachtwoord_hash,
            account.created_at,
            account.geboortedatum,
            account.type,
            account.voornaam,
            account.achternaam,
            account.telefoon,
            account.deleted_at,
            beheer.rol,
            beheer.datum_in_dienst
        FROM account
        INNER JOIN beheer 
            ON beheer.account_id = account.account_id
        WHERE account.account_id = :id
        LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();

        $beheer = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($beheer) {
            return new Beheer(
                Beheerdersrol::from($beheer['rol']),
                new DateTime($beheer['datum_in_dienst']),
                $beheer['email'],
                $beheer['gebruikersnaam'],
                $beheer['wachtwoord_hash'],
                new DateTime($beheer['created_at']),
                new DateTime($beheer['geboortedatum']),
                // AccountType::from($beheer['type']),
                $beheer['voornaam'],
                $beheer['achternaam'],
                $beheer['telefoon'],
                $beheer['deleted_at'] ?
                new DateTime($beheer['deleted_at']) : null,
                $beheer['account_id']
            );
        } else {
            return null;
        }
    }
}
