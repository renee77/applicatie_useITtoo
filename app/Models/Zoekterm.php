<?php

namespace App\Models;

class Zoekterm
{
    public function __construct(private string $zoekterm, private int $aantalKeerGezocht = 1)
    {
    }

    public function getZoekterm(): string
    {
        return $this->zoekterm;
    }

    public function getAantalKeerGezocht(): int
    {
        return $this->aantalKeerGezocht;
    }
}
