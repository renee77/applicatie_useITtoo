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
class Router
{
    /**
     * Lijst van geregistreerde routes.
     * Sleutel = URL pad ('/webshop'), waarde = array met 'view' en 'layout'.
     * Private: niemand buiten deze klasse mag er direct in schrijven.
     */
    private array $routes = [];

    /**
     * Het projectmapje dat van de URL afgeknipt moet worden.
     * Bijvoorbeeld: '/eindopdracht_jaar1'
     *
     * Herbruikbaar: door dit mee te geven bij aanmaken (in plaats van
     * hardcoderen) werkt deze klasse in elk project met elke mapnaam.
     */
    private string $basePath;

    /**
     * @param string $basePath  Het voorvoegsel in de URL dat geen deel
     *                          uitmaakt van de routing, bijv. '/eindopdracht_jaar1'
     */
    public function __construct(string $basePath = '')
    {
        $this->basePath = $basePath;
    }

    /**
     * Registreert een nieuwe route.
     *
     * Je roept deze methode aan vanuit index.php voor elke pagina
     * die de applicatie kent. Zo bouw je de routelijst op.
     *
     * Voorbeeld gebruik:
     *   $router->register('/', 'home.view.php');
     *   $router->register('/beheer', 'beheer.view.php', 'main.beheer.php');
     *
     * @param string $path      Het URL-pad, bijv. '/' of '/webshop'
     * @param string $view      Het bestandspad naar de bijbehorende view
     * @param string $layout    Het te gebruiken layout-bestand (standaard main.php)
     */
    public function register(string $path, string $view, string $layout = 'main.php'): void
    {
        // Sla de route op met pad, view én layout
        // Bijvoorbeeld: $routes['/beheer'] = ['view' => 'beheer.view.php', 'layout' => 'main.beheer.php']
        $this->routes[$path] = [
            'view'   => $view,
            'layout' => $layout,
        ];
    }

    /**
     * Leest de huidige URL uit en maakt hem klaar voor vergelijking.
     *
     * Drie dingen worden gedaan:
     * 1. Het projectmapje wordt eraf geknipt (/eindopdracht_jaar1/webshop → /webshop)
     * 2. De querystring wordt verwijderd (/webshop?pagina=2 → /webshop)
     * 3. De trailing slash wordt verwijderd (/webshop/ → /webshop)
     *
     * Daarna is de URL schoon en klaar om te vergelijken met de routelijst.
     *
     * Private: wordt alleen intern aangeroepen door run(), niet vanuit buiten de klasse.
     *
     * @return string  Het schoongemaakte URL-pad, bijv. '/' of '/webshop'
     */
    private function resolve(): string
    {
        // Haal de volledige URL op, bijv. /eindopdracht_jaar1/webshop?pagina=2
        $uri = $_SERVER['REQUEST_URI'];

        // Knip het projectmapje eraf
        // /eindopdracht_jaar1/webshop → /webshop
        $uri = str_replace($this->basePath, '', $uri);

        // Verwijder de querystring (alles vanaf het vraagteken)
        // /webshop?pagina=2 → /webshop
        $uri = strtok($uri, '?');

        // Verwijder een eventuele trailing slash aan het einde
        // /webshop/ → /webshop
        // rtrim() knipt tekens aan het einde van een string
        $uri = rtrim($uri, '/');

        // Als de uri nu leeg is, zijn we op de homepagina
        // '' → '/'
        return $uri ?: '/';
    }

    /**
     * Voert de router uit.
     *
     * Dit is de enige publieke methode die je vanuit index.php aanroept.
     * Hij gebruikt resolve() om de URL schoon te maken, zoekt die op
     * in de routelijst, en laadt de juiste view én layout — of toont een 404.
     *
     * Gebruik in index.php:
     *   $router->run();
     */
    public function run(): void
    {
        // Haal het schoongemaakte URL-pad op via de private resolve() methode
        $path = $this->resolve();

        // Kijk of dit pad bekend is in de routelijst
        if (array_key_exists($path, $this->routes)) {
            // Haal view én layout op uit de route-array
            $view   = $this->routes[$path]['view'];
            $layout = $this->routes[$path]['layout'];

            // Vang de output van de view op in een variabele
            // zodat het layout-bestand hem op de juiste plek kan plaatsen
            ob_start();
            include $view;
            $content = ob_get_clean();

            // Laad het juiste layout-bestand (main.php of main.beheer.php)
            include __DIR__ . '/../../app/Views/layouts/' . $layout;
        } else {
            // Pad staat niet in de routelijst → 404
            http_response_code(404);
            echo '404 - Pagina niet gevonden';
        }
    }
}
