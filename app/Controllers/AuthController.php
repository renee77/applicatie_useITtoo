<?php

namespace app\Controllers;

use App\Core\Authenticator;

class AuthController
{
    private Authenticator $auth;

    public function __construct()
    {
        $this->auth = new Authenticator();
    }

    public function login()
    {
      // Haal de gebruikersnaam en het wachtwoord binnen uit de POST.
        $gebruikersnaam = trim($_POST['username'] ?? '');
        $wachtwoord = $_POST['password'] ?? '';

      // Als één van beiden leeg is, moet hier een error voor worden opgegooid, en
      // wordt de rest van de functie niet uitgevoerd.
        if (empty($gebruikersnaam) || empty($wachtwoord)) {
            $_SESSION['error'] = 'Niet alle velden zijn ingevuld.';
          // Ook wordt je teruggestuurd naar de beheerderslogin.
            header('Location: /beheerderslogin');
            exit;
        }

      //Nu gaat hij de login validatie uitvoeren
        $succes = $this->auth->login($gebruikersnaam, $wachtwoord);

      // Als hij de if-statement doorkomt, moet er worden geprobeerd of de informate valide is om in te loggen.
      // ALs het niet lukt, een error in de sessie zetten en terugsturen naar de beheerderslogin.
        if (!$succes) {
            $_SESSION['error'] = 'Ongeldige login. Gegevens kloppen niet.';
            header('Location: /beheerderslogin');
            exit;
        }

      // Bij een succesvolle verwerking van alles
        header('Location: /beheerdersdashboard');
        exit;
    }
}
