<?php

namespace App\Models;

class User
{
    private string $naam;
    private string $email;
    private int $leeftijd;
    private ?string $telefoon;

    public function __construct(
        string $naam,
        string $email,
        int $leeftijd,
        ?string $telefoon = null
    ) {
        if (strlen($naam) < 2) {
            throw new \InvalidArgumentException("Naam moet minimaal 2 karakters zijn.");
        }
        if ($leeftijd < 0 || $leeftijd > 150) {
            throw new \InvalidArgumentException("Leeftijd moet tussen 0 en 150 zijn.");
        }

        $this->naam     = $naam;
        $this->email    = $email;
        $this->leeftijd = $leeftijd;
        $this->telefoon = $telefoon;
    }

    public function getNaam(): string
    {
        return $this->naam;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getLeeftijd(): int
    {
        return $this->leeftijd;
    }

    public function getTelefoon(): ?string
    {
        return $this->telefoon;
    }

    public function setTelefoon(string $telefoon): void
    {
        $this->telefoon = $telefoon;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
