<?php

namespace App\Core;

/**
 * Router klasse
 *
 * Verantwoordelijk voor het koppelen van URLs aan views of controllers.
 * Dit is Core-infrastructuur: de Router weet niets van producten of gebruikers,
 * hij weet alleen hoe hij een URL vertaalt naar de juiste plek.
 *
 * Herbruikbaar: deze klasse werkt in elk PHP-project, ongeacht het onderwerp.
 * Daarom hoort hij in Core en niet in Models.
 */

// De verantwoordelijkheden zijn:

// Controller → bepaalt welke data de view nodig heeft en geeft die terug
// Router → zorgt dat die data beschikbaar is in de scope waar de view draait
// View → gebruikt de data, weet niet waar hij vandaan komt

// gebruik in index.php bij het registreren:
// $router->register(
//     '/webshop',
//     __DIR__ . '/app/Views/webshop.view.php',
//     'main.php',
//     function() use ($db) {
//         $dao = new ProductDAO($db);
//         $controller = new WebshopController($dao);
//         $controller->index();
//     }
// );


class Router
{
    // Lijst van geregistreerde routes.
    // Sleutel = URL pad ('/webshop'), waarde = array met 'view' en 'layout'.
    // Private: niemand buiten deze klasse mag er direct in schrijven.
    private array $routes = [];
    // Het projectmapje dat van de URL afgeknipt moet worden.
    // Bijvoorbeeld: '/eindopdracht_jaar1'
    // Herbruikbaar: door dit mee te geven bij aanmaken (in plaats van
    // hardcoderen) werkt deze klasse in elk project met elke mapnaam.
    private string $basePath;

    public function __construct(string $basePath = '')
    {
        $this->basePath = $basePath;
    }

    // Voeg de methode toe waarmee routes worden vastgelegd.
    // Parameters: $path, $view, $layout (met default), $controller (nullable callable, met default null).
    // Dit zijn afzonderlijke dingen die de router later apart nodig heeft
    // De methode slaat de route op in de $routes array.
    // als associatieve array per route  zodat ik straks in run() elk onderdeel appart kan ophalen

    public function register(
        string $path,
        string $view,
        string $layout = "main.php",
        ?callable $controller = null
    ): void {
        $this->routes[$path] = [
            'view' => $view,
            'layout' => $layout,
            'controller' => $controller,
        ];
    }

    //     Dit is een private methode die de URL schoonmaakt.
    // De URL is wat de browser naar de server stuurt, en die zit in een superglobal die PHP altijd beschikbaar maakt
    // je krijgt bijvoorbeel /eindopdracht_jaar1/webshop?pagina=2 terug
    // Drie dingen moet hij doen:
    // Het projectmapje afknippen
    // De querystring verwijderen
    // De trailing slash verwijderen

    private function resolve(): string
    {
        $uri = $_SERVER['REQUEST_URI'];
        // Het projectmapje afknippen
        $uri = str_replace($this->basePath, '', $uri);
        // De querystring verwijderen mbv strin tokenizer
        // hij knipt een string op bij een bepaald teken en geeft het eerste stuk terug
        $uri = strtok($uri, '?');
        // De trailing slash verwijderen
        // rtrim() righttrim knipt tekens weg aan het rechteruiteinde van de string
        $uri = rtrim($uri, '/');

        return $uri ?: '/';
    }

    // de publieke methode die je in index.php gebruikt, gebruik $router->run();
    //     Hij moet drie dingen doen:
    // Het schoongemaakte pad ophalen via resolve()
    // Kijken of dat pad bekend is in $routes
    // De juiste view en layout laden — of een 404 tonen

    public function run(): void
    {
        // Het schoongemaakte pad ophalen via resolve()
        $path = $this->resolve();

        // Kijken of dat pad bekend is in $routes
        if (array_key_exists($path, $this->routes)) {
            $view = $this->routes[$path]['view'];
            $layout = $this->routes[$path]['layout'];
            $controller = $this->routes[$path]['controller'];

            // eerst de check of de controller niet null is en dan zorg ik dat de variabelen
            // die de controller maakt voor de view beschikbaar komen
            //             $viewData = [
            //     'groenten' => [...],
            //     'fruit'    => [...],
            //     'houdbaar' => [...],
            // ];

            // extract($viewData);

            // Nu bestaan automatisch:
            // $groenten = [...]
            // $fruit    = [...]
            // $houdbaar = [...]

            if ($controller !== null) {
                $viewData = ($controller)();
                if (is_array($viewData)) {
                    extract($viewData);
                }
            }

            // Nu de output buffer. Het probleem is dit: de layout heeft de inhoud van de view nodig als variabele
            // $content zodat hij hem op de juiste plek kan plaatsen.
            // Maar als je gewoon include $view doet, wordt de HTML meteen naar de browser gestuurd.
            // ob_start() vangt alle output op in een buffer in plaats van hem meteen te sturen.
            // ob_get_clean() geeft die buffer terug als string en leegt hem.
            ob_start();
            include $view;
            $content = ob_get_clean();
            include __DIR__ . '/../../app/Views/layouts/' . $layout;
        } else {
            $matched = false;

            // Loop door alle geregistreerde routes
            // want we weten niet welk patroon gaat matchen
            foreach ($this->routes as $pattern => $route) {
                // Bouw de volledige regex op:
                // #^    = begin van de string
                // $#    = einde van de string
                // # wordt gebruikt ipv / omdat URL's al / bevatten
                if (preg_match('#^' . $pattern . '$#', $path, $matches)) {

                    $matched = true;

                    // $matches[1] bevat het id uit de URL
                    // (int) zorgt dat het een integer wordt ipv string
                    $id = (int) $matches[1];

                    $view       = $route['view'];
                    $layout     = $route['layout'];
                    $controller = $route['controller'];

                    if ($controller !== null) {
                        // geef het id mee aan de closure
                        // zodat de controller het juiste product kan ophalen
                        $viewData = ($controller)($id);
                        if (is_array($viewData)) {
                            extract($viewData);
                        }
                    }

                    ob_start();
                    include $view;
                    $content = ob_get_clean();
                    include __DIR__ . '/../../app/Views/layouts/' . $layout;
                    break; // stop de loop zodra een match gevonden is
                }
            }

            // Geen enkel patroon matched → 404
            if (!$matched) {
                http_response_code(404);
                echo '404 - Pagina niet gevonden';
            }
        }
    }
}
