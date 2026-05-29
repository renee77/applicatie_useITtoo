<?php

namespace App\Controllers;

use App\Core\SessionManager;
use App\DAO\AccountDAO;

class KlantLoginController
{
    // Door private vóór de parameter te zetten in de constructor doet PHP automatisch drie dingen:
    // property declareren, parameter ontvangen, en toewijzen aan $this->
    public function __construct(
        private AccountDAO $accountDAO,
        private SessionManager $session
    ) {
    }

    public function handleLogin(): void
    {
        // Gebruikersnaam en wachtwoord ophalen uit $_POST
        $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
        $wachtwoord     = $_POST['wachtwoord'] ?? '';

        // De pagina waar de gebruiker vandaan komt (hidden field uit het formulier)
        // Fallback naar /webshop als het veld ontbreekt of leeg is
        $redirect_terug = $_POST['redirect_to'] ?? BASE_URL . '/webshop';

        // Controleer of het een interne URL is, zo niet stuur naar webshop
        if (!str_starts_with($redirect_terug, BASE_URL)) {
            $redirect_terug = BASE_URL . '/webshop';
        }

        // Maximaal 5 mislukte pogingen toestaan
        if ($this->session->getLoginPogingen() >= 5) {
            $this->session->setFout('Te veel mislukte pogingen. Probeer het later opnieuw.');
            header('Location: ' . $redirect_terug);
            exit;
        }

        // Lege velden checken
        if ($gebruikersnaam === '' || $wachtwoord === '') {
            $this->session->incrementLoginPogingen();
            $this->session->setFout('Vul je gebruikersnaam en wachtwoord in.');
            header('Location: ' . $redirect_terug);
            exit;
        }

        // Account ophalen via gebruikersnaam
        $account = $this->accountDAO->getByUsername($gebruikersnaam);

        // Account bestaat niet
        if ($account === null) {
            $this->session->incrementLoginPogingen();
            $this->session->setFout('Ongeldige gebruikersnaam of wachtwoord.');
            header('Location: ' . $redirect_terug);
            exit;
        }

        // Controleer of het account niet verwijderd is
        if ($account->getDeletedAt() !== null) {
            $this->session->incrementLoginPogingen();
            $this->session->setFout('Dit account is niet meer actief.');
            header('Location: ' . $redirect_terug);
            exit;
        }

        // Wachtwoord controleren
        if (!$account->verifyPassword($wachtwoord)) {
            $this->session->incrementLoginPogingen();
            $this->session->setFout('Ongeldige gebruikersnaam of wachtwoord.');
            header('Location: ' . $redirect_terug);
            exit;
        }

        // Type ophalen (geeft 'klant' of 'beheer' terug, of null)
        $type = $this->accountDAO->getTypeByAccountId($account->getAccountId());

        // Account_id in de sessie zetten
        $this->session->setAccountId($account->getAccountId());

        // Voornaam altijd opslaan (beide rollen gebruiken dit)
        $this->session->setVoornaam($account->getVoornaam() ?? $gebruikersnaam);

        // Mislukte pogingen resetten na succesvolle login
        $this->session->resetLoginPogingen();

        // Switch op type → verschillende redirect per rol
        switch ($type) {
            case 'beheer':
                $this->session->setRolBeheer('beheer');
                header('Location: ' . BASE_URL . '/beheer');
                exit;

            case 'klant':
                $basePath = $_ENV['APP_BASE_PATH'] ?? '';
                $redirect_terug = ($redirect_terug === BASE_URL . '/')
                    ? BASE_URL . '/webshop'
                    : $redirect_terug;
                header('Location: ' . $redirect_terug);
                exit;

            default:
                // Type onbekend of null — veilig uitloggen en foutmelding tonen
                $this->session->destroy();
                $this->session->start();
                $this->session->setFout('Je account heeft geen geldige rol. Neem contact op.');
                header('Location: ' . $redirect_terug);
                exit;
        }
    }
}
