<?php

namespace App\Models;

use App\Models\AccountType;
use DateTime;

class Account
{
    private ?int $account_id;
    private string $email;
    private string $gebruikersnaam;
    private string $wachtwoord;
    private ?string $voornaam;
    private ?string $achternaam;
    private DateTime $created_at;
    private DateTime $geboortedatum;
    private ?string $telefoon;
    private AccountType $type;
    private ?DateTime $deleted_at;

    public function __construct(
        string $email,
        string $gebruikersnaam,
        string $wachtwoord,
        ?string $voornaam = null,
        ?string $achternaam = null,
        DateTime $created_at,
        DateTime $geboortedatum,
        ?string $telefoon = null,
        AccountType $type,
        ?DateTime $deleted_at = null,
        // Het account_id wordt automatisch toegekend bij een nieuw product via database. 
        ?int $account_id = null
    ) {
        if (strlen($gebruikersnaam) <= 2) {
            throw new \InvalidArgumentException("Gebruikersnaam moet langer als 2 karakters zijn.");
        }

        $minDatum = new DateTime();
        $minDatum->modify('-18 years');
        if ($geboortedatum > $minDatum) {
            throw new \InvalidArgumentException(
                "Geboortedatum moet minimaal 18 jaar geleden zijn."
            );
        }

        $this->account_id = $account_id;
        $this->email = $email;
        $this->gebruikersnaam = $gebruikersnaam;
        $this->wachtwoord = $wachtwoord;
        $this->voornaam = $voornaam;
        $this->achternaam = $achternaam;
        $this->created_at = $created_at;
        $this->geboortedatum = $geboortedatum;
        $this->telefoon = $telefoon;
        $this->type = $type;
        $this->deleted_at = $deleted_at;
    } 
}