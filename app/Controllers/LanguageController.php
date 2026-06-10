<?php

namespace App\Controllers;

use App\Core\SessionManager;

class LanguageController
{
  // De sessie wordt via de constructor meegegeven.
  // Dit garandeert dat de controller in dezelfde sessie werkt als
  // de rest van de applicatie.
    private SessionManager $session;

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    public function set(): void
    {
        $options = ['nl', 'en'];
      // Via GET binnenhalen, of naar standaard: NL
        $language = $_GET['lang'] ?? 'nl';

      // Alleen de toegestane talen kunnen worden verwerkt
        if (in_array($language, $options)) {
            $this->session->setLanguage($language);
        }

      // Stuur terug naar de vorige pagina waar de gebruiker vandaan komt.
      // Stuur hem anders terug naar homepage.
        $referentie = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $referentie);
        exit;
    }
}
