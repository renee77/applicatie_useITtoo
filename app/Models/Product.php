<?php

namespace App\Models;

use DateTime;
use App\Models\Eenheid;

class Product
{
    private string $naam;
    private float $prijs;
    private float $verkoop_gewicht;
    private Eenheid $eenheid;
    private ?string $omschrijving;
    private ?string $leverancier;
    private ?string $foto_url;
    private ?DateTime $deleted_at;
    private ?int $product_id;

    public function __construct(
        string $naam,
        float $prijs,
        float $verkoop_gewicht,
        Eenheid $eenheid,
        ?string $omschrijving,
        ?string $leverancier,
        ?string $foto_url = null,
        ?DateTime $deleted_at = null,
        // product_id is null bij een nieuw product — de database kent het id toe via AUTO_INCREMENT
        // bij een opgehaald product vult de DAO het id in via deze parameter
        ?int $product_id = null
    ) {
        if ($prijs <= 0) {
            throw new \InvalidArgumentException("Prijs mag niet 0 of negatief zijn");
        }
        if (strlen($naam) < 2) {
            throw new \InvalidArgumentException("Naam moet minimaal 2 karakters hebben");
        }
        if ($verkoop_gewicht <= 0) {
            throw new \InvalidArgumentException("Gewicht moet groter dan 0 zijn");
        }


        $this->naam = $naam;
        $this->prijs = $prijs;
        $this->verkoop_gewicht = $verkoop_gewicht;
        $this->eenheid = $eenheid;
        $this->omschrijving = $omschrijving;
        $this->leverancier = $leverancier;
        $this->foto_url = $foto_url;
        $this->deleted_at = $deleted_at;
        $this->product_id = $product_id;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->product_id;
    }
    public function getNaam(): string
    {
        return $this->naam;
    }
    public function getPrijs(): float
    {
        return $this->prijs;
    }
    public function getVerkoopGewicht(): float
    {
        return $this->verkoop_gewicht;
    }
    public function getEenheid(): Eenheid
    {
        return $this->eenheid;
    }
    public function getOmschrijving(): ?string
    {
        return $this->omschrijving;
    }
    public function getLeverancier(): ?string
    {
        return $this->leverancier;
    }
    public function getFotoUrl(): ?string
    {
        return $this->foto_url;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deleted_at;
    }

    // Setters (alleen wat echt wijzigbaar moet zijn)
    public function setNaam(string $naam): void
    {
        if (strlen($naam) < 2) {
            throw new \InvalidArgumentException("Naam moet minimaal 2 karakters hebben");
        }
        $this->naam = $naam;
    }
    public function setPrijs(float $prijs): void
    {
        if ($prijs <= 0) {
            // throw new \InvalidArgumentException("Prijs mag niet 0 of negatief zijn xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");
        }

        $this->prijs = $prijs;
    }
    public function setVerkoopGewicht(float $verkoop_gewicht): void
    {
        if ($verkoop_gewicht <= 0) {
            throw new \InvalidArgumentException("Gewicht moet groter dan 0 zijn");
        }
        $this->verkoop_gewicht = $verkoop_gewicht;
    }
    public function setEenheid(Eenheid $eenheid): void
    {
        $this->eenheid = $eenheid;
    }
    public function setOmschrijving(?string $omschrijving): void
    {
        $this->omschrijving = $omschrijving;
    }
    public function setLeverancier(?string $leverancier): void
    {
        $this->leverancier = $leverancier;
    }
    public function setFotoUrl(?string $foto_url): void
    {
        $this->foto_url = $foto_url;
    }
}
