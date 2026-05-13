<?php

namespace App\Models;

use App\Models\AccountType;
use DateTime;

class Account
{
    private string $email;
    private string $gebruikersnaam;
    private string $wachtwoord;
    private DateTime $created_at;
    private DateTime $geboortedatum;
    private AccountType $type;
    private ?string $voornaam;
    private ?string $achternaam;
    private ?string $telefoon;
    private ?DateTime $deleted_at;
    private ?int $account_id;

    public function __construct(
        string $email,
        string $gebruikersnaam,
        string $wachtwoord,
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


    // GETTERS
    public function getAccountId(): ?int
    {
        return $this->account_id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getGebruikersnaam(): string
    {
        return $this->gebruikersnaam;
    }

    public function getWachtwoord(): string
    {
        return $this->wachtwoord;
    }

    public function getVoornaam(): ?string
    {
        return $this->voornaam;
    }

    public function getAchternaam(): ?string
    {
        return $this->achternaam;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function getGeboortedatum(): DateTime
    {
        return $this->geboortedatum;
    }

    public function getTelefoon(): ?string
    {
        return $this->telefoon;
    }

    public function getType(): AccountType
    {
        return $this->type;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deleted_at;
    }

    // SETTERS - Wijzigbaar
    public function setVoornaam(string $voornaam): void
    {
        $this->voornaam = $voornaam;
    }

    public function setAchternaam(string $achternaam): void
    {
        $this->achternaam = $achternaam;
    }

    public function setTelefoon(string $telefoon): void
    {
        $this->telefoon = $telefoon;
    }
}
