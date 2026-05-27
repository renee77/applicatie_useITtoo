<?php

namespace App\Controllers;

use App\Core\SessionManager;
use App\DAO\ProductDAO;
use App\Models\Product;

// Onderstaande moeten in de klasse:
// $productDAO = new ProductDAO(Database::getConnection());
// $products = $productDAO->getAllProducts();

class ProductController
{
    private ProductDAO $productDAO;
    private SessionManager $session;

    public function __construct(ProductDAO $productDAO, SessionManager $session)
    {
        $this->productDAO = $productDAO;
        $this->session = $session;
    }

    public function index(): array
    {
        return [
        'products' => $this->productDAO->getAllProducts()
        ];
    }

    public function showProduct(int $product_id): array
    {
        return [
            'product' => $this->productDAO->getProductById($product_id)
        ];
    }
}
