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
            header('Location: ' . BASE_URL . '/login');
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

    public function getAccountId(): ?int
    {
        return $_SESSION['account_id'] ?? null;
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

    // bij ongeldige invoer van formulier kunnen geldige waardes worden bewaard
    public function setInvoerFormulier(array $oud): void
    {
        $_SESSION['invoerFormulier'] = $oud;
    }

    // formlier vullen met eerder ingevulde waardes
    public function getInvoerFormulier(): array
    {
        $invoer = $_SESSION['invoerFormulier'] ?? [];
        unset($_SESSION['invoerFormulier']);
        return $invoer;
    }

    // Flash messages:
    // na het tonen van de melding blijft hij in de sessie staan.
    // Dus als je daarna naar een andere pagina gaat, zie je hem nog steeds.
    // Dit lost je op door de waarde te wissen na het ophalen:
    public function getMelding(): string
    {
        $melding = $_SESSION['melding'] ?? '';
        unset($_SESSION['melding']);
        return $melding;
    }

    public function setMelding(string $melding): void
    {
        $_SESSION['melding'] = $melding;
    }

    public function getFout(): string
    {
        $fout = $_SESSION['fout'] ?? '';
        unset($_SESSION['fout']);
        return $fout;
    }

    public function setFout(string $fout): void
    {
        $_SESSION['fout'] = $fout;
    }

    public function getContactFout(): string
    {
        $fout = $_SESSION['contact_fout'] ?? '';
        unset($_SESSION['contact_fout']);
        return $fout;
    }

    public function setContactFout(string $fout): void
    {
        $_SESSION['contact_fout'] = $fout;
    }

    public function setRolBeheer(string $rol): void
    {
        $_SESSION['rol_beheer'] = $rol;
    }

    public function getRolBeheer(): string
    {
        return $_SESSION['rol_beheer'] ?? '';
    }

    public function setVoornaam(string $voornaam): void
    {
        $_SESSION['voornaam'] = $voornaam;
    }

    public function getVoornaam(): string
    {
        return $_SESSION['voornaam'] ?? '';
    }

    public function getLoginPogingen(): int
    {
        return $_SESSION['login_pogingen'] ?? 0;
    }

    public function incrementLoginPogingen(): void
    {
        $_SESSION['login_pogingen'] = $this->getLoginPogingen() + 1;
    }

    public function resetLoginPogingen(): void
    {
        unset($_SESSION['login_pogingen']);
    }
}
