<?php

namespace App\Routes;

use App\Core\Router;
use App\Core\SessionManager;

class Routes
{
    public static function register(Router $router, SessionManager $session): void
    {
        $router->register('/', __DIR__ . '/../../app/Views/start/home.view.php');

        $router->register(
            '/webshop',
            __DIR__ . '/../../app/Views/webshop/webshop.view.php',
            'main.php',
            function () use ($session) {
                $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
                $controller = new \App\Controllers\WebshopController($dao, $session);
                return $controller->index();
            }
        );

        $router->register(
            '/webshop/(\d+)-([a-z0-9-]+)',
            __DIR__ . '/../../app/Views/webshop/product.view.php',
            'main.php',
            function (int $id) use ($session) {
                $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
                $controller = new \App\Controllers\ProductController($dao, $session);
                return $controller->showProduct($id);
            }
        );

        $router->register(
            '/zoeken',
            __DIR__ . '/../../app/Views/webshop/zoeken.view.php',
            'main.php',
            function () use ($session) {
                $zoekDao = new \App\DAO\ZoektermDAO(\App\Core\Database::getConnection());
                $productDao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
                $controller = new \App\Controllers\ZoekController($zoekDao, $productDao, $session);
                return $controller->zoeken();
            }
        );

        // BEHEEROMGEVING
        $router->register(
            '/beheer',
            __DIR__ . '/../../app/Views/beheer/beheer.view.php',
            'main.beheer.php',
            function () use ($session) {
                return [
                    'voornaam' => $session->getVoornaam()
                ];
            }
        );

        $router->register(
            '/beheer/product',
            __DIR__ . '/../../app/Views/beheer/beheer.product.overview.view.php',
            'main.beheer.php',
            function () {
                $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());

                $zoekterm = trim($_GET['zoekterm'] ?? '');
                if ($zoekterm !== '') {
                    $products = $dao->getProductByName($zoekterm);
                } else {
                    $products = $dao->getAllProducts();
                }
                return [
                    'products' => $products,
                    'zoekterm' => $zoekterm
                ];
            }
        );

        $router->register(
            '/beheer/product/nieuw',
            __DIR__ . '/../../app/Views/beheer/beheer.product.nieuw.view.php',
            'main.beheer.php',
            function () use ($session) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());

                    $product = new \App\Models\Product(
                        trim($_POST['naam']),
                        (float) $_POST['prijs'],
                        (float) $_POST['verkoop_gewicht'],
                        \App\Models\Eenheid::from($_POST['eenheid']),
                        trim($_POST['omschrijving']) ?: null,
                        trim($_POST['leverancier']) ?: null,
                        trim($_POST['foto_url']) ?: null,
                    );

                    $dao->addProduct($product);
                    $session->setMelding("Het product {$product->getNaam()} is succesvol aangemaakt!");
                    header('Location: ' . BASE_URL . '/beheer/product');
                    exit;
                }

                return [
                    'eenheden' => \App\Models\Eenheid::cases()
                ];
            }
        );

        $router->register(
            '/beheer/product/edit',
            __DIR__ . '/../../app/Views/beheer/beheer.product.edit.view.php',
            'main.beheer.php',
            function () use ($session) {
                $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
                $product_id = (int) ($_GET['id'] ?? 0);

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $product = new \App\Models\Product(
                        trim($_POST['naam']),
                        (float) $_POST['prijs'],
                        (float) $_POST['gewicht'],
                        \App\Models\Eenheid::from($_POST['eenheid']),
                        trim($_POST['omschrijving']) ?: null,
                        trim($_POST['leverancier']) ?: null,
                        trim($_POST['foto_url']) ?: null,
                        null,
                        $product_id
                    );

                    $dao->updateProduct($product);
                    $session->setMelding("Product succesvol bijgewerkt!");
                    header('Location: ' . BASE_URL . '/beheer/product');
                    exit;
                }

                try {
                    return [
                        'product'  => $dao->getProductById($product_id),
                        'eenheden' => \App\Models\Eenheid::cases()
                    ];
                } catch (\RuntimeException) {
                    $session->setFout("Product niet gevonden.");
                    header('Location: ' . BASE_URL . '/beheer/product');
                    exit;
                }
            }
        );

        $router->register(
            '/beheer/product/delete',
            __DIR__ . '/../../app/Views/beheer/beheer.product.overview.view.php',
            'main.beheer.php',
            function () use ($session) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
                    $product_id = (int) ($_POST['id'] ?? 0);

                    $dao->deleteProduct($product_id);
                    $session->setMelding("Product succesvol verwijderd!");
                    header('Location: ' . BASE_URL . '/beheer/product');
                    exit;
                }
            }
        );

        // Upload routes
        $router->register(
            '/beheer/upload',
            __DIR__ . '/../../app/Views/beheer/beheer.upload.view.php',
            'main.beheer.php'
        );

        $router->register(
            '/beheer/upload/csv',
            __DIR__ . '/../../app/Views/beheer/beheer.upload.csv.view.php',
            'main.beheer.php',
            function () use ($session) {
                $db = \App\Core\Database::getConnection();
                $dao = new \App\DAO\ProductDAO($db);
                $controller = new \App\Controllers\UploadController($session, $dao, $db);

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->handleCSVUpload();
                }
            }
        );

        $router->register(
            '/beheer/upload/csv/template',
            __DIR__ . '/../../app/Views/beheer/beheer.upload.csv.view.php',
            'main.beheer.php',
            function () use ($session) {
                $controller = new \App\Controllers\UploadController($session);
                $controller->sendCSVTemplate();
            }
        );

        $router->register(
            '/beheer/upload/afbeelding',
            __DIR__ . '/../../app/Views/beheer/beheer.upload.afb.view.php',
            'main.beheer.php',
            function () use ($session) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller = new \App\Controllers\UploadController($session);
                    $controller->uploadImage();
                }
            }
        );

        $router->register(
            '/webshop/login',
            __DIR__ . '/../../app/Views/start/home.view.php',
            'main.php',
            function () use ($session) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $dao = new \App\DAO\AccountDAO(\App\Core\Database::getConnection());
                    $controller = new \App\Controllers\LoginController($dao, $session);
                    $controller->handleLogin();
                }
                header('Location: ' . BASE_URL . '/');
                exit;
            }
        );

        $router->register(
            '/logout',
            __DIR__ . '/../../app/Views/start/home.view.php',
            'main.php',
            function () use ($session) {
                $session->destroy();
                $session->start();
                header('Location: ' . BASE_URL . '/');
                exit;
            }
        );
    }
}
