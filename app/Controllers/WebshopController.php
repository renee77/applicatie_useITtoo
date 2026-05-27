<?php

namespace App\Controllers;

use App\Core\SessionManager;
use App\DAO\ProductDAO;
use App\Models\Categorie;

class WebshopController
{
    private ProductDAO $productDAO;
    private SessionManager $session;

    public function __construct(ProductDAO $productDAO, SessionManager $session)
    {
        $this->productDAO = $productDAO;
        $this->session = $session;
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
