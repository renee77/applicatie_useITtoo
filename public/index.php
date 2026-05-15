<?php

require_once __DIR__ . '/../bootstrap.php';

// Maak de router aan met het projectmapje als basePath
// Dit mapje wordt van elke URL afgeknipt voor de vergelijking
$router = new \App\Core\Router($_ENV['APP_BASE_PATH'] ?? '');

// Registreer alle bekende routes
// Patroon: $router->register(URL-pad, view-bestand, layout-bestand);
// Het layout-bestand is optioneel — zonder opgave wordt main.php gebruikt
$router->register('/', __DIR__ . '/../app/Views/start/home.view.php');
$router->register(
    '/webshop',
    __DIR__ . '/../app/Views/webshop/webshop.view.php',
    'main.php',
    function () {
        $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
        $controller = new \App\Controllers\WebshopController($dao);
        return $controller->index();
    }
);
$router->register('/beheerlogin', __DIR__ . '/../app/Views/beheer/beheer.login.view.php', 'login.beheer.php');
$router->register('/beheer', __DIR__ . '/../app/Views/beheer/beheer.view.php', 'main.beheer.php');
$router->register(
    '/beheer/product',
    __DIR__ . '/../app/Views/beheer/beheer.product.overview.view.php',
    'main.beheer.php',
    function() {
        $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
        return $controller->index(); // ← return! zodat de view data krijgt
    }
);
// $router->register(
//  '/beheer/product',
//    __DIR__ . '/../app/Views/beheer/beheer.product.overview.view.php', 
//   'main.beheer.php',
//    __DIR__ . '/../app/Controllers/ProductController.php');
$router->register(
    '/beheer/product',
    __DIR__ . '/../app/Views/beheer/beheer.product.overview.view.php',
    'main.beheer.php',
    function() {
        $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
        return $dao->getAllProducts();
    }
);
// Voer de router uit — hij bepaalt welke view geladen wordt
$router->run();
