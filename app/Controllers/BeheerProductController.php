<?php

namespace App\Controllers;

use App\Core\SessionManager;
use App\DAO\ProductDAO;
use App\Models\Eenheid;
use App\Models\Product;

class BeheerProductController
{
    public function __construct(
        private ProductDAO $dao,
        private SessionManager $session
    ) {}

    public function index(): array
    {
        $zoekterm = trim($_GET['zoekterm'] ?? '');
        $products = $zoekterm !== '' ? $this->dao->getProductByName($zoekterm)
            : $this->dao->getAllProducts();

        return [
            'products' => $products,
            'zoekterm' => $zoekterm
        ];
    }

    public function newProductForm(): array
    {
        return [
            'eenheden' => Eenheid::cases()
        ];
    }

    public function createProduct(): void
    {
        $product = new Product(
            trim($_POST['naam']),
            (float) $_POST['prijs'],
            (float) $_POST['verkoop_gewicht'],
            Eenheid::from($_POST['eenheid']),
            trim($_POST['omschrijving']) ?: null,
            trim($_POST['leverancier']) ?: null,
            trim($_POST['foto_url']) ?: null,
        );

        $this->dao->addProduct($product);
        $this->session->setMelding("Het product {$product->getNaam()} is succesvol aangemaakt!");
        header('Location: ' . BASE_URL . '/beheer/product');
        exit;
    }

    public function editProductForm(): array
    {
        $product_id = (int) ($_GET['id'] ?? 0);

        return [
            'product'  => $this->dao->getProductById($product_id),
            'eenheden' => Eenheid::cases()
        ];
    }

    public function updateProduct(): void
    {
        $product_id = (int) ($_GET['id'] ?? 0);

        $product = new Product(
            trim($_POST['naam']),
            (float) $_POST['prijs'],
            (float) $_POST['gewicht'],
            Eenheid::from($_POST['eenheid']),
            trim($_POST['omschrijving']) ?: null,
            trim($_POST['leverancier']) ?: null,
            trim($_POST['foto_url']) ?: null,
            null,
            $product_id
        );

        $this->dao->updateProduct($product);
        $this->session->setMelding("Product succesvol bijgewerkt!");
        header('Location: ' . BASE_URL . '/beheer/product');
        exit;
    }

    public function deleteProduct(): void
    {
        $product_id = (int) ($_POST['id'] ?? 0);
        $this->dao->deleteProduct($product_id);
        $this->session->setMelding("Product succesvol verwijderd!");
        header('Location: ' . BASE_URL . '/beheer/product');
        exit;
    }
}