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
            function () use ($session) {
                    $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
                    $controller = new \App\Controllers\BeheerProductController($dao, $session);
                    return $controller->index();
            }
        );

        $router->register(
            '/beheer/product/nieuw',
            __DIR__ . '/../../app/Views/beheer/beheer.product.nieuw.view.php',
            'main.beheer.php',
            function () use ($session) {
                $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
                $controller = new \App\Controllers\BeheerProductController($dao, $session);

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->createProduct();
                }

                return $controller->newProductForm();
            }
        );

        $router->register(
            '/beheer/product/edit',
            __DIR__ . '/../../app/Views/beheer/beheer.product.edit.view.php',
            'main.beheer.php',
            function () use ($session) {
                $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
                $controller = new \App\Controllers\BeheerProductController($dao, $session);

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->updateProduct();
                }

                return $controller->editProductForm();
            }
        );

        $router->register(
            '/beheer/product/delete',
            __DIR__ . '/../../app/Views/beheer/beheer.product.overview.view.php',
            'main.beheer.php',
            function () use ($session) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
                    $controller = new \App\Controllers\BeheerProductController($dao, $session);
                    $controller->deleteProduct();
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
                // Hier wordt één verbinding aangemaakt. Als er een losse verbinding wordt gemaakt in de DAO,
                // Zit de transactie en de DAO op twee verschillende verbndingen. Dit zou dus niet werken.
                $db = \App\Core\Database::getConnection();
                // Gaat alleen over de informatie die op moet worden opgehaald via de DB.
                $dao = new \App\DAO\ProductDAO($db);
                // Behandelt de logica op basis van de o.a. de DAO en database.
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
                // Check of er met de request method POST is gewerkt.
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
