<?php

class Product
{
    private int $product_id;
    private string $naam;
    private float $prijs;
    private ?string $omschrijving;
    private ?string $leverancier;
    private ?string $foto_url;
    private string $verkoop_gewicht;
    private ?DateTime $deleted_at;
}
