<?php

namespace App\Core;

use App\DAO\BeheerDAO;
use App\Models\Beheer;
use App\Core\Database;
use PDO;

// Hier zit de authenticatielogica is.
// Is dus geen controller, en geen DAO.
class AuthService
{
    private BeheerDAO $beheerDAO;

    public function __construct(BeheerDAO $beheerDAO)
    {
        $this->beheerDAO = $beheerDAO;
    }

  // Inloggegevens gaan valideren.
  // Check: Bestaat gebruikersnaam en matcht het wachtwoord?
  // Geeft 'beheerder' terug als login geldig is, anders null.
    public function loginBeheerder(
        string $gebruikersnaam,
        string $wachtwoord
    ): ?Beheer {
      //Zoek de beheerder op in de database via de getbyUsername functie.
        $beheer = $this->beheerDAO->getByUsername($gebruikersnaam);

      // Als de gebruikersnaam niet bestaat, direct stoppen
        if ($beheer === null) {
            return null;
        }

      // Vergelijk wachtwoord met wachtwoord uit database.
      // Password verify verifieert dat de hash matcht met het wachtwoord.
        if (!password_verify($wachtwoord, $beheer->getWachtwoordHash())) {
            return null;
        }

      // Als hij door beide checks komt, dus A. Gebruikersnaam bestaat
      // en B. Wachtwoord matcht ook met de gebruiker.
        return $beheer;
    }
}
