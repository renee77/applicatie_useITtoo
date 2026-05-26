<?php

namespace App\Core;

// waar en hoe gebruik je SessionManager? Denk aan de flow:
// Elke pagina — start() moet aangeroepen worden voordat er iets anders gebeurt.
//      Waar in je applicatie gebeurt altijd iets als eerste?
//      de start moet dus gebeuren in index.php
// Bij inloggen — setAccountId() aanroepen na succesvolle login. Waar gebeurt dat?
// Bij beveiligde pagina's — requireLogin() bovenaan. Wie beslist welke pagina's beveiligd zijn?
// Bij uitloggen — destroy() aanroepen. Waar komt de uitlogknop terecht?

class SessionManager
{
    // start een sessie
    public function start(): void
    {
        // check of er al een sessie bestaat met session_status()
        // Er zijn drie mogelijkheden:
        // PHP_SESSION_DISABLED — sessies zijn uitgeschakeld
        // PHP_SESSION_NONE — geen actieve sessie
        // PHP_SESSION_ACTIVE — sessie is al actief
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // check of gebruiker ingelogd is
    // bij inloggen wordt account_id opgeslagen in de session
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['account_id']);
    }

    // sessie verwijderen
    public function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            // sessie eerst leegmaken zodat er niets achterblijf in geheugen
            $_SESSION = [];
            session_destroy();
        }
    }

    // Login afdwingen
    public function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            // header() stuurt een HTTP-header naar de browser.
            // Een HTTP-header is een instructie die de server meegeeft aan de browser
            // in dit geval Location: /login, wat de browser vertelt "ga naar deze URL".
            // De browser voert dat dan automatisch uit.
            header('Location: /login');
            // exit() stopt de uitvoering van het PHP-script op dat punt.
            // Zonder exit() zou PHP na de header() gewoon doorgaan met de rest van de code uitvoeren
            // ook al heeft de browser al de redirect ontvangen.
            // Dat is een beveiligingsrisico want iemand zou dan toch bij de pagina-inhoud kunnen komen.
            // Dus exit() zorgt dat er echt niets meer wordt uitgevoerd na de redirect.
            exit();
        }
    }

    // account_id onthouden na inloogen
    public function setAccountId(int $account_id): void
    {
        $_SESSION['account_id'] = $account_id;
    }

    public function getLanguage(): string
    {
        return $_SESSION['lang'] ?? 'nl';
    }

    public function setLanguage(string $language): void
    {
        // default bij null wordt nederlands meegegeven
        $_SESSION['lang'] = $language;
    }
}
