<?php

namespace App\Controllers;

use App\Core\SessionManager;
use App\DAO\AccountDAO;

class LoginController
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
            $this->session->setFout(__('notifs.many_logins'));
            header('Location: ' . $redirect_terug);
            exit;
        }

        // Lege velden checken
        if ($gebruikersnaam === '' || $wachtwoord === '') {
            $this->session->incrementLoginPogingen();
            $this->session->setFout(__('notifs.no_empty'));
            header('Location: ' . $redirect_terug);
            exit;
        }

        // Account ophalen via gebruikersnaam
        $account = $this->accountDAO->getByUsername($gebruikersnaam);

        // Account bestaat niet
        if ($account === null) {
            $this->session->incrementLoginPogingen();
            $this->session->setFout(__('notifs.wrong_login'));
            header('Location: ' . $redirect_terug);
            exit;
        }

        // Controleer of het account niet verwijderd is
        if ($account->getDeletedAt() !== null) {
            $this->session->incrementLoginPogingen();
            $this->session->setFout(__('notifs.inactive_account'));
            header('Location: ' . $redirect_terug);
            exit;
        }

        // Wachtwoord controleren
        if (!$account->verifyPassword($wachtwoord)) {
            $this->session->incrementLoginPogingen();
            $this->session->setFout(__('notifs.invalid'));
            header('Location: ' . $redirect_terug);
            exit;
        }

        // Type ophalen (geeft 'klant' of 'beheer' terug, of null)
        $type = $this->accountDAO->getTypeByAccountId($account->getAccountId());

        // Sessie-ID vernieuwen vóór het opslaan van logingegevens.
        // Dit beschermt tegen een "session fixation" aanval:
        //
        // Zonder deze regel kan een aanvaller als volgt te werk gaan:
        //   1. De aanvaller bezoekt de site en krijgt een sessie-ID (bijv. "abc123").
        //   2. De aanvaller stuurt een link met dat sessie-ID naar het slachtoffer,
        //      bijvoorbeeld via een phishing-mail.
        //   3. Het slachtoffer klikt de link en logt in — maar gebruikt nog steeds
        //      hetzelfde sessie-ID "abc123" dat de aanvaller al heeft.
        //   4. De aanvaller heeft dat sessie-ID nog in zijn browser en is daarmee
        //      nu ook ingelogd als het slachtoffer.
        //
        // Door hier een nieuw sessie-ID aan te maken is het oude sessie-ID
        // van de aanvaller na het inloggen waardeloos geworden.
        // De sessie-inhoud (bijv. winkelwagen) blijft volledig bewaard —
        // alleen het ID verandert, niet de data.
        // De true parameter zorgt dat het oude sessie-bestand op de server
        // ook meteen wordt verwijderd.
        session_regenerate_id(true);

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
                $this->session->setFout(__('notifs.invalid_role'));
                header('Location: ' . $redirect_terug);
                exit;
        }
    }
}
