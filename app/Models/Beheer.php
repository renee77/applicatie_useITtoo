<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Beheerdersrol;
use DateTime;

class Beheer extends Account
{
    private Beheerdersrol $rol;
    private DateTime $datum_in_dienst;

    public function __construct(
        Beheerdersrol $rol,
        DateTime $datum_in_dienst,
        string $email,
        string $gebruikersnaam,
        string $wachtwoord_hash,
        DateTime $created_at,
        DateTime $geboortedatum,
        AccountType $type,
        ?string $voornaam = null,
        ?string $achternaam = null,
        ?string $telefoon = null,
        ?DateTime $deleted_at = null,
        // Het account_id wordt automatisch toegekend bij een nieuw product via database.
        ?int $account_id = null
    ) {

     // Roep de constructor van Account aan
        parent::__construct(
            $email,
            $gebruikersnaam,
            $wachtwoord_hash,
            $created_at,
            $geboortedatum,
            $type,
            $voornaam,
            $achternaam,
            $telefoon,
            $deleted_at,
            $account_id
        );

        $this->rol = $rol;
        $this->datum_in_dienst = $datum_in_dienst;
    }

  // GETTERS
    public function getRol(): Beheerdersrol
    {
        return $this->rol;
    }

    public function getDatumInDienst(): DateTime
    {
        return $this->datum_in_dienst;
    }

  // SETTERS
    public function setRol(Beheerdersrol $rol): void
    {
        $this->rol = $rol;
    }
}
