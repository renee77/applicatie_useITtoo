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
    ) {
    }

    public function index(): array
    {
        $zoekterm = trim($_GET['zoekterm'] ?? '');
        // Zoekterm is ingevuld maar te kort — minimaal 2 tekens vereisen
        // voorkomt ook dat een spatie als zoekterm wordt geaccepteerd
        if (isset($_GET['zoek']) && strlen($zoekterm) < 2) {
            $this->session->setFout(__('notifs.no_search'));
            $products = $this->dao->getAllProducts();
        } elseif ($zoekterm !== '') {
            $products = $this->dao->getProductByName($zoekterm);

            // Geef een melding als er geen resultaten zijn
            if (empty($products)) {
                $this->session->setFout(__('notifs.no_products') . $zoekterm);
            }
        } else {
            // Geen zoekterm — laad alle producten
            $products = $this->dao->getAllProducts();
        }

        return [
            'products' => $products,
            'zoekterm' => $zoekterm,
            'session' => $this->session
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
        try {
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
            $this->session->setMelding(__('notifs.product_made'));
            header('Location: ' . BASE_URL . '/beheer/product');
            exit;
        } catch (\ValueError $e) {
        // Ongeldige eenheid meegegeven
            $this->session->setFout("Ongeldige eenheid opgegeven.");
            header('Location: ' . BASE_URL . '/beheer/product/nieuw');
            exit;
        } catch (\InvalidArgumentException $e) {
            // Ongeldige productdata — negatieve prijs, naam te kort etc.
            $this->session->setFout($e->getMessage());
            header('Location: ' . BASE_URL . '/beheer/product/nieuw');
            exit;
        }
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

        try {
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
            $this->session->setMelding(__('notifs.product_updated'));
            header('Location: ' . BASE_URL . '/beheer/product');
            exit;
        } catch (\ValueError $e) {
            $this->session->setFout("Ongeldige eenheid opgegeven.");
            header('Location: ' . BASE_URL . '/beheer/product/edit?id=' . $product_id);
            exit;
        } catch (\InvalidArgumentException $e) {
            $this->session->setFout($e->getMessage());
            header('Location: ' . BASE_URL . '/beheer/product/edit?id=' . $product_id);
            exit;
        }
    }

    public function deleteProduct(): void
    {
        $product_id = (int) ($_POST['id'] ?? 0);
        $this->dao->deleteProduct($product_id);
        $this->session->setMelding(__('notifs.product_deleted'));
        header('Location: ' . BASE_URL . '/beheer/product');
        exit;
    }
}
