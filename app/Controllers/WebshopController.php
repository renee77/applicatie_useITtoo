<?php

namespace App\Controllers;

use App\DAO\ProductDAO;
use App\Models\Categorie;

class WebshopController
{
    private ProductDAO $productDAO;

    public function __construct(ProductDAO $productDAO)
    {
        $this->productDAO = $productDAO;
    }

    // De "standaard actie" van een controller heet traditioneel index
    public function index(): array
    {
        return [
            'groenten' => $this->productDAO->getProductsByCategorie(Categorie::Groente),
            'fruit'    => $this->productDAO->getProductsByCategorie(Categorie::Fruit),
            'houdbaar' => $this->productDAO->getProductsByCategorie(Categorie::LangerHoudbaar),
        ];
    }
}
