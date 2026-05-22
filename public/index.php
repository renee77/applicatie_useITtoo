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
$router->register(
    '/webshop/(\d+)-([a-z0-9-]+)',
    __DIR__ . '/../app/Views/webshop/product.view.php',
    'main.php',
    function (int $id) {
        $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
        $controller = new \App\Controllers\ProductController($dao);
        return $controller->showProduct($id);
    }
);

$router->register('/beheerlogin', __DIR__ . '/../app/Views/beheer/beheer.login.view.php', 'login.beheer.php');
$router->register('/beheer', __DIR__ . '/../app/Views/beheer/beheer.view.php', 'main.beheer.php');
$router->register('/beheer/product',
    __DIR__ . '/../app/Views/beheer/beheer.product.overview.view.php',
    'main.beheer.php',
    function () {
        $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
        $controller = new \App\Controllers\ProductController($dao);
        
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

$router->register('/beheer/upload', 
__DIR__ . '/../app/Views/beheer/beheer.upload.view.php', 
'main.beheer.php');

$router->register('/beheer/upload/csv', 
__DIR__ . '/../app/Views/beheer/beheer.upload.csv.view.php', 
'main.beheer.php',
function () {
    // Check of de form via een POST is verzonden
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dao = new \App\DAO\ProductDAO(\App\Core\Database::getConnection());
        // Controleer of het bestand correct is geüpload.
        // 'error' is UPLOAD_ERR_OK (0) als alles goed is gegaan.
        // Anders is er iets misgegaan, bijv. bestand te groot of geen bestand.
        if ($_FILES['csv_bestand']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['fout'] = "Fout bij uploaden van bestand.";
            header('Location: ' . BASE_URL . '/beheer/upload/csv');
            exit;
        }

        // Open het tijdelijke bestand dat PHP heeft aangemaakt via fopen (file open).
        // r betekent read-only. Bestand wordt alleen ingelezen.
        $bestand = fopen($_FILES['csv_bestand']['tmp_name'], 'r');

        // Eerste rij wordt overgeslagen, zijn kolomnamen en geen product.
        fgetcsv($bestand);

        // Houdt in de gaten hoeveel producten succesvol zijn aangemaakt.
        $aangemaakt = 0;
        // Houdt in de gaten hoeveel fouten er zijn geweest.
        $fouten = 0;

        // Nu door alle CSV-rijen gaan lopen.
        // fgetcsv() leest één rij tegelijk en geeft een array terug.
        // Geef aan dat het max 1000 tekens is, en dat het scheidngsteken ',' is.
        // Als einde van het bestand is bereikt, krijgen we false.
        while (($rij = fgetcsv($bestand, 1000, ',')) !== false) {
            try {
                // Voor elke rij wordt een Product gemaakt. 
                // Het is belangrijk dat de volgorde van CSV bestand klopt met de nummering. 
                // Anders wordt informatie bij verkeerde kolom geplaatst.
                $product = new \App\Models\Product(
                    // naam
                    trim($rij[0]),                    
                    // prijs
                    (float) $rij[1],                     
                    // verkoop_gewicht
                    (float) $rij[2],                     
                    // eenheid (moet kloppen met ENUM)
                    \App\Models\Eenheid::from(trim($rij[3])),
                    // Omschrijving, kan null zijn.
                    trim($rij[4]) ?: null,
                    // Leverancier, kan null zijn.
                    trim($rij[5]) ?: null,
                    // foto_url, kan null zijn.
                    trim($rij[6]) ?: null,
                );
                // Vervolgens wordt het product aangemaakt, en wordt er dus opgeplust bij aangemaakt variabele.
                $dao->addProduct($product);
                $aangemaakt++;
            } catch (\Exception $e) {
                // Als er een fout in de rij zit, wordt de volledige rij overgeslagen. 
                // Fout kan zijn, negatieve prijs, ongeldige eenheid, prijs als string oid.
                // Dan wordt de fouten variabele opgeplust en de rij overgeslagen. 
                // Daarna gaat hij door met de rest van het bestand. 
                $fouten++;
            }
        }

        // Als alles is verwerkt, wordt het bestand weer gesloten. 
        fclose($bestand);

        // Toon een melding met het aantal succesvol aangemaakte
        // en overgeslagen producten.
        $_SESSION['melding'] = "$aangemaakt product(en) geïmporteerd, $fouten overgeslagen.";
        header('Location: ' . BASE_URL . '/beheer/product');
        exit;
    }
});

$router->register('/beheer/upload/csv/template',
    __DIR__ . '/../app/Views/beheer/beheer.upload.csv.view.php', 
    'main.beheer.php',
    function() {
        // Geef aan dat het een CSV bestand gaat zijn.
        header('Content-Type: text/csv');
        // Geef aan dat het een download gaat worden en geen nieuwe pagina, met de naam
        // product_template.csv
        header('Content-Disposition: attachment; filename="product_template.csv"');

        // Open een output buffer zodat we fputcsv() kunnen gebruiken
        $output = fopen('php://output', 'w');

        // Eerst zetten we de header in de csv, geven hiermee de kolommen aan. 
        // Deze matcht met wat de import verwacht.
        fputcsv($output, [
            'naam',
            'prijs',
            'verkoop_gewicht',
            'eenheid',
            'omschrijving',
            'leverancier',
            'foto_url'
        ]);

        // Een voorbeeldrij, waarop te zien is hoe de data moet worden geschreven.
        fputcsv($output, [
            'Wortel',
            '1.95',
            '1000',
            'kg',
            'Verse wortels van de boer',
            'Boer Koen',
            'wortel.jpg'
        ]);

        // Sluit het document en zorg ervoor dat het kan worden gedownload.
        fclose($output);
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
