<?php

namespace App\Controllers;

use App\Core\AuthService;

class LoginController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Hier wordt het loginverzoek verwerkt (via POST).
    public function handleLogin(): void
    {
      if (ob_get_level() === 0) {
          ob_start();
      }
      //Waarden ophalen uit POST verzoek. 
      // Foutvoorkoming als een veld niet is gevuld (??)
      $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
      $wachtwoord = $_POST['wachtwoord'] ?? '';

      // Eerst nakijken of velden niet leeg zijn.
      if (empty($gebruikersnaam) || empty($wachtwoord)) {
        $_SESSION['error'] = "Vul alle velden in.";
        header("Location: ". BASE_URL ."/beheerlogin");
        exit;
      }

      // Nu gaan we Authservice gebruiken om te kijken of ww en naam matcht. 
      $beheer = $this->authService->loginBeheerder($gebruikersnaam, $wachtwoord);

      // Nakijken of er een match is (anders is het null.)
      if ($beheer === null) {
        $_SESSION['error'] = "Ongeldige gebruikersnaam of wachtwoord.";
        header("Location: ". BASE_URL ."/beheerlogin");
        exit;
      };
       // Als alle stappen doorlopen, geslaagd.
      /// Sla de gegvens van de beheerder op in sessie, zodat bekend is
      // wie er is ingelogd en welke rol deze persoon heeft.
      $_SESSION['account_id'] = $beheer->getAccountId();
      $_SESSION['rol'] = $beheer->getRol()->value;
      $_SESSION['type'] = $beheer->getType()->value;

      header("Location: " . BASE_URL . "/beheer");
      exit;
    }
  }
