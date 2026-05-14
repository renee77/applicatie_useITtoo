<?php

namespace App\Controllers;

use App\Core\AuthService;

class LoginController
{
    // De Authservice wordt hier direct aangeroepen en opgeslagen
    // zodat er snel weer gebruik van kan worden gemaakt.
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Hier wordt het loginverzoek verwerkt wat via de POST-methode
    // wordt toegestuurd. De gebruiker wordt altijd verder geleid,
    // of het nu is geslaagd of niet. Dit gebeurd via de header() functie.
    public function handleLogin(): void
    {
        if (ob_get_level() === 0) {
            ob_start();
        }
      // Waarden ophalen uit POST verzoek.
      // Foutvoorkoming als een veld niet is gevuld (??)
        $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
        $wachtwoord = $_POST['wachtwoord'] ?? '';

      // Eerst nakijken of velden niet leeg zijn.
      // Dit ter voorkoming van het feit dat er een database query wordt gedaan met de helft van de informatie.
        if (empty($gebruikersnaam) || empty($wachtwoord)) {
            $_SESSION['error'] = "Vul alle velden in.";
            header("Location: " . BASE_URL . "/beheerlogin");
            exit;
        }

      // Nu gaan we Authservice gebruiken om te kijken of ww en naam matcht.
      // Dit gaat allemaal buiten de controller om.
        $beheer = $this->authService->loginBeheerder($gebruikersnaam, $wachtwoord);

      // Nakijken of er een match is qua gebruikersnaam(anders is het null.)
        if ($beheer === null) {
            $_SESSION['error'] = "Ongeldige gebruikersnaam of wachtwoord.";
            header("Location: " . BASE_URL . "/beheerlogin");
            exit;
        };
        
       // Als alle stappen doorlopen, geslaagd.
      /// Sla de gegvens van de beheerder op in sessie, zodat bekend is
      // wie er is ingelogd en welke rol deze persoon heeft.
        $_SESSION['account_id'] = $beheer->getAccountId();
        $_SESSION['rol'] = $beheer->getRol()->value;
        $_SESSION['type'] = $beheer->getType()->value;
        $_SESSION['voornaam'] = trim($beheer->getVoornaam());

        header("Location: " . BASE_URL . "/beheer");
        exit;
    }
}
