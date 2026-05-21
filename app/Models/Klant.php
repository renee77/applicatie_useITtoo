<?php

namespace App\Models;

use DateTime;

class Klant extends Account
{
    private DateTime $startdatum_lidmaatschap;

    public function __construct(
        string $email,
        string $gebruikersnaam,
        string $wachtwoord_hash,
        DateTime $created_at,
        DateTime $geboortedatum,
        ?string $voornaam = null,
        ?string $achternaam = null,
        ?string $telefoon = null,
        ?DateTime $deleted_at = null,
        ?int $account_id = null
    ) {
        parent::__construct(
            $email,
            $gebruikersnaam,
            $wachtwoord_hash,
            $created_at,
            $geboortedatum,
            $voornaam,
            $achternaam,
            $telefoon,
            $deleted_at,
            $account_id
        );
        $this->startdatum_lidmaatschap = new DateTime();
    }

    public function getStartdatumLidmaatschap(): DateTime
    {
        return $this->startdatum_lidmaatschap;
    }
}
