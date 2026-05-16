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
$router->register('/beheer/product',
    __DIR__ . '/../app/Views/beheer/beheer.product.overview.view.php',
    'main.beheer.php',
    function() {
        $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
        
        // Voor nu een hard gecodeerde zoekterm zodat kan worden gecheckt dat het werkt, 
        // Alles staat klaar zodat het straks kan de get-request.
        $zoekterm = 'aardbei';
        //$zoekterm = trim($_GET['zoek'] ?? '');
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
$router->register('/beheer/product/nieuw', 
__DIR__ . '/../app/Views/beheer/beheer.product.nieuw.view.php', 'main.beheer.php',
function() {
        // POST request = formulier verstuurd
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
            // Sessiemelding aanmaken zodat er een melding kan worden getoond.
            $_SESSION['melding'] = "Het product $product is succesvol aangemaakt!";
            header('Location: ' . BASE_URL . '/beheer/product');
            exit;
        }
    }
);
$router->register(
    '/beheer/product/edit',
    __DIR__ . '/../app/Views/beheer/beheer.product.edit.view.php',
    'main.beheer.php',
    function() {
        $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
        $product_id = (int) ($_GET['id'] ?? 0);

        // POST = formulier verstuurd, update uitvoeren
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
                // Hier wordt het id meegegeven, zodat updateProduct weet welk
                // product moet worden geupdate.
                $product_id
            );

            $dao->updateProduct($product);
            $_SESSION['melding'] = "Product succesvol bijgewerkt!";
            header('Location: ' . BASE_URL . '/beheer/product');
            exit;
        }
        return [
            'product'  => $dao->getProductById($product_id),
            'eenheden' => \App\Models\Eenheid::cases()
        ];
    }
);

$router->register(
    '/beheer/product/delete',
    __DIR__ . '/../app/Views/beheer/beheer.product.overview.view.php',
    'main.beheer.php',
    function() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
            $product_id = (int) ($_POST['id'] ?? 0);

            $dao->deleteProduct($product_id);
            $_SESSION['melding'] = "Product succesvol verwijderd!";
            header('Location: ' . BASE_URL . '/beheer/product');
            exit;
        }
    }
);
// $router->register(
//  '/beheer/product',
//    __DIR__ . '/../app/Views/beheer/beheer.product.overview.view.php', 
//   'main.beheer.php',
//    __DIR__ . '/../app/Controllers/ProductController.php');
// Voer de router uit — hij bepaalt welke view geladen wordt
$router->run();
