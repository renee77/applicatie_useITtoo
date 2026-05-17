<?php

use App\Core\Database;
use App\DAO\ProductDAO;

$productDAO = new ProductDAO(Database::getConnection());
$products = $productDAO->getAllProducts();
