<?php

namespace App\Controllers;

use App\Core\SessionManager;
use App\DAO\ContactFormulierDAO;
use App\Models\ContactFormulier;

class ContactFormulierController
{
    public function __construct(
        private ContactFormulierDAO $dao,
        private SessionManager $session
    ) {
    }

    public function verwerkContactFormulier(): void
    {
        // Invoer ophalen uit $_POST
        $voornaam = trim($_POST['voornaam'] ?? '');
        $achternaam = trim($_POST['achternaam'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $bericht = trim($_POST['bericht'] ?? '');
        $telefoonnummer = trim($_POST['telefoonnummer'] ?? '');
        // Telefoonnummer strippen — alle tekens die geen cijfer zijn worden verwijderd.
        // Zo wordt bijvoorbeeld "013-456 44" automatisch "01345644" voordat we valideren.
        $telefoonnummer = preg_replace('/[^0-9]/', '', $telefoonnummer);


        $fouten = [];
        $invoerFormulier = [
            'voornaam' => $voornaam,
            'achternaam' => $achternaam,
            'email' => $email,
            'telefoonnummer' => $telefoonnummer,
            'bericht' => $bericht
        ];


        // De pagina waar de gebruiker vandaan komt (hidden field uit het formulier)
        // Fallback naar /webshop als het veld ontbreekt of leeg is
        $redirect_terug = $_POST['redirect_to'] ?? BASE_URL . '/webshop';

        // Controleer of het een interne URL is, zo niet stuur naar webshop
        if (!str_starts_with($redirect_terug, BASE_URL)) {
            $redirect_terug = BASE_URL . '/webshop';
        }

        // Valideren
        // voornaam — verplicht, minimaal 2 tekens
        if ($voornaam === '' || strlen($voornaam) < 2) {
            $fouten[] = "Voornaam moet minimaal 2 tekens hebben.";
        }
        // achternaam — verplicht, minimaal 2 tekens
        if ($achternaam === '' || strlen($achternaam) < 2) {
            $fouten[] = "Achternaam moet minimaal 2 tekens hebben.";
        }
        // email — verplicht, geldig formaat
        // FILTER_VALIDATE_EMAIL controleert op het patroon tekst@tekst.tekst
        // filter_var met FILTER_VALIDATE_EMAIL geeft het e-mailadres terug als het geldig is,
        // en false als het ongeldig is. Met ! zeg je dus "als het e-mailadres ongeldig is"
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $fouten[] = "Vul een geldig e-mailadres in.";
        }
        // telefoonnummer — optioneel, maximaal 10 cijfers
        if ($telefoonnummer !== '' && strlen($telefoonnummer) !== 10) {
            $fouten[] = "Ongeldig telefoonnummer, voer een nummer met 10 getallen in.";
        }
        // bericht — verplicht, minimaal 4 tekens, maximaal 1000 tekens
        $berichtLengte = strlen($bericht);
        if ($berichtLengte < 4) {
            $fouten[] = "Bericht is te kort, geef minimaal 4 letters in.";
        }
        if ($berichtLengte > 1000) {
            $fouten[] = "Bericht is te lang, uw bericht heeft $berichtLengte tekens. Geef maximaal 1000 tekens in.";
        }


        // Bij fouten → fout in sessie zetten + redirect terug naar referer
        // Als er fouten zijn, alle foutmeldingen samenvoegen tot één string gescheiden door '|'
        // zodat de view ze kan opsplitsen en als lijst kan tonen.
        // De ingevulde waarden worden bewaard in de sessie zodat het formulier voorgevuld blijft.
        if (!empty($fouten)) {
            $this->session->setContactFout(implode('|', $fouten));
            $this->session->setInvoerFormulier($invoerFormulier);
            header('Location: ' . $redirect_terug);
            exit;
        }

        // Bij succes → ContactFormulier aanmaken + opslaan via DAO + melding in sessie + redirect
        // Checken of er ingelogd is en account_id ophalen
        $klant_id = $this->session->getAccountId();
        if ($klant_id) {
            $invoerFormulier['klant_id'] = $klant_id;
        }

        $contactFormulier = new ContactFormulier(
            $voornaam,
            $achternaam,
            $email,
            $bericht,
            $telefoonnummer ?: null,
            $klant_id ?: null
        );

        try {
            $this->dao->contactFormulierOpslaan($contactFormulier);
            $this->session->setMelding("Je bericht is verzonden. We nemen zo snel mogelijk contact met je op.");
            header('Location: ' . $redirect_terug);
            exit;
        } catch (\Exception $e) {
            $this->session->setContactFout("Er is iets misgegaan bij het verzenden. Probeer het later opnieuw.");
            $this->session->setInvoerFormulier($invoerFormulier);
            header('Location: ' . $redirect_terug);
            exit;
        }
    }
}
