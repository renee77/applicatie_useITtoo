<?php

require_once __DIR__ . '/../bootstrap.php';

// maak de sessie aan en start deze daarna
$session = new \App\Core\SessionManager();
$session->start();

// Maak de router aan met het projectmapje als basePath
// Dit mapje wordt van elke URL afgeknipt voor de vergelijking
$router = new \App\Core\Router($_ENV['APP_BASE_PATH'] ?? '', $session);

// Registreer alle bekende routes
// Patroon: $router->register(URL-pad, view-bestand, layout-bestand);
// Het layout-bestand is optioneel — zonder opgave wordt main.php gebruikt
$router->register('/', __DIR__ . '/../app/Views/start/home.view.php');
$router->register(
    '/webshop',
    __DIR__ . '/../app/Views/webshop/webshop.view.php',
    'main.php',
    function () use ($session) {
        $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
        $controller = new \App\Controllers\WebshopController($dao, $session);
        return $controller->index();
    }
);
$router->register(
    '/webshop/(\d+)-([a-z0-9-]+)',
    __DIR__ . '/../app/Views/webshop/product.view.php',
    'main.php',
    function (int $id) use ($session) {
        $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
        $controller = new \App\Controllers\ProductController($dao, $session);
        return $controller->showProduct($id);
    }
);

// BEHEEROMGEVING
$router->register(
    '/beheerlogin',
    __DIR__ . '/../app/Views/beheer/beheer.login.view.php',
    'login.beheer.php',
    function () use ($session) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = \App\Core\Database::getConnection();
            $beheerDAO = new \App\DAO\BeheerDAO($db);
            $authService = new \App\Core\AuthService($beheerDAO);
            $controller = new \App\Controllers\LoginController($authService, $session);
            $controller->handleLogin();
        }
    }
);

$router->register(
    '/beheer',
    __DIR__ . '/../app/Views/beheer/beheer.view.php',
    'main.beheer.php',
    function () use ($session) {
        return [
            'voornaam' => $session->getVoornaam()
        ];
    }
);
$router->register(
    '/beheer/product',
    __DIR__ . '/../app/Views/beheer/beheer.product.overview.view.php',
    'main.beheer.php',
    function () use ($session) {
        $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
        $controller = new \App\Controllers\BeheerProductController($dao, $session);
        return $controller->index();
    }
);

$router->register(
    '/beheer/product/nieuw',
    __DIR__ . '/../app/Views/beheer/beheer.product.nieuw.view.php',
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
    __DIR__ . '/../app/Views/beheer/beheer.product.edit.view.php',
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
    __DIR__ . '/../app/Views/beheer/beheer.product.overview.view.php',
    'main.beheer.php',
    function () use ($session) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
            $controller = new \App\Controllers\BeheerProductController($dao, $session);
            $controller->deleteProduct();
        }
    }
);

//*******************//
// Alle upload views //
//*******************//
$router->register(
    '/beheer/upload',
    __DIR__ . '/../app/Views/beheer/beheer.upload.view.php',
    'main.beheer.php'
);

$router->register(
    '/beheer/upload/csv',
    __DIR__ . '/../app/Views/beheer/beheer.upload.csv.view.php',
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
    __DIR__ . '/../app/Views/beheer/beheer.upload.csv.view.php',
    'main.beheer.php',
    function () use ($session) {
        $controller = new \App\Controllers\UploadController($session);
        $controller->sendCSVTemplate();
    }
);

$router->register(
    '/beheer/upload/afbeelding',
    __DIR__ . '/../app/Views/beheer/beheer.upload.afb.view.php',
    'main.beheer.php',
    function () use ($session) {
        // Check of er met de request method POST is gewerkt.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new \App\Controllers\UploadController($session);
            $controller->uploadImage();
        }
    }
);
//*******************//
// Eind upload views //
//*******************//

$router->register(
    '/webshop/login',
    __DIR__ . '/../app/Views/start/home.view.php', // wordt nooit getoond, controller doet altijd een redirect
    'main.php',
    function () use ($session) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dao = new \App\DAO\AccountDAO(\App\Core\Database::getConnection());
            $controller = new \App\Controllers\KlantLoginController($dao, $session);
            $controller->handleLogin();
        }
        // GET-request op /klant/login heeft geen zin — stuur terug naar home
        header('Location: ' . BASE_URL . '/');
        exit;
    }
);

// uitlog route
$router->register(
    '/logout',
    __DIR__ . '/../app/Views/start/home.view.php',
    'main.php',
    function () use ($session) {
        $session->destroy();
        $session->start();
        header('Location: ' . BASE_URL . '/');
        exit;
    }
);

// $router->register(
//  '/beheer/product',
//    __DIR__ . '/../app/Views/beheer/beheer.product.overview.view.php',
//   'main.beheer.php',
//    __DIR__ . '/../app/Controllers/ProductController.php');
// Voer de router uit — hij bepaalt welke view geladen wordt

$router->run();
