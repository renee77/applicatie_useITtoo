<?php

namespace App\Models;

use DateTime;

/**
 * Vertegenwoordigt een ingestuurde contactformulier-aanvraag.
 *
 * Een nieuw object wordt aangemaakt via de constructor wanneer een bezoeker
 * het formulier instuurt. Bij het laden uit de database wordt de statische
 * factory method fromDatabase() gebruikt, zodat de constructor puur blijft
 * voor nieuwe aanvragen.
 */
class ContactFormulier
{
    // Deze properties worden niet via de constructor ingesteld omdat ze
    // ofwel automatisch worden bepaald (verzonden_op), ofwel pas bekend zijn
    // na opslag in de database (contactFormulier_id), ofwel later worden
    // bijgewerkt door de applicatie (afgehandeld_op, deleted_at).
    private DateTime $verzonden_op;
    private ?DateTime $afgehandeld_op;
    private ?DateTime $deleted_at;
    private ?int $contactFormulier_id;

    /**
     * Maakt een nieuw ContactFormulier aan op basis van invoer van de bezoeker.
     * verzonden_op wordt automatisch ingesteld op het huidige tijdstip.
     *
     * @param string      $voornaam       Voornaam van de afzender.
     * @param string      $achternaam     Achternaam van de afzender.
     * @param string      $email          E-mailadres van de afzender.
     * @param string      $bericht        Berichttekst van de afzender.
     * @param string|null $telefoonnummer Optioneel telefoonnummer.
     * @param int|null    $klant_id       Ingevuld als de bezoeker is ingelogd als klant.
     */
    public function __construct(
        private string $voornaam,
        private string $achternaam,
        private string $email,
        private string $bericht,
        private ?string $telefoonnummer = null,
        private ?int $klant_id = null,
    ) {
        $this->verzonden_op = new DateTime();
        $this->afgehandeld_op = null;
        $this->deleted_at = null;
        $this->contactFormulier_id = null;
    }

    /**
     * Factory method voor het laden van een ContactFormulier uit de database.
     *
     * We gebruiken een statische factory method in plaats van de constructor
     * omdat de constructor bedoeld is voor nieuwe aanvragen. Bij laden uit de
     * database zijn er extra velden bekend (id, timestamps) die we niet via
     * de constructor willen doorgeven — dat zou de constructor vervuilen met
     * parameters die alleen relevant zijn voor databaserijen.
     *
     * Static betekent dat je deze methode aanroept op de klasse zelf, zonder
     * eerst een object te hebben: ContactFormulier::fromDatabase($row).
     * Self betekent dat de methode een instantie van deze klasse teruggeeft.
     *
     * @param array $row Associatief array met kolomwaarden uit de database.
     * @return self      Een volledig gevuld ContactFormulier object.
     */
    public static function fromDatabase(array $row): self
    {
        // Maak eerst een object aan via de constructor met de basisvelden.
        // De overige velden worden daarna handmatig ingesteld omdat de
        // constructor die niet accepteert — die zijn niet relevant bij
        // het aanmaken van een nieuw formulier.
        $contactFormulier = new ContactFormulier(
            $row['voornaam'],
            $row['achternaam'],
            $row['email'],
            $row['bericht'],
            $row['telefoonnummer'],
            $row['klant_id']
        );

        // MariaDB geeft timestamps terug als strings (bijv. "2024-01-15 10:30:00").
        // createFromFormat zet die string om naar een DateTime object op basis
        // van het opgegeven formaat: Y=jaar, m=maand, d=dag, H=uur, i=minuten, s=seconden.
        $contactFormulier->verzonden_op = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $row['verzonden_op']
        );

        // afgehandeld_op en deleted_at kunnen NULL zijn in de database.
        // We controleren dit eerst voordat we createFromFormat aanroepen,
        // want die functie kan niet omgaan met een null waarde.
        $contactFormulier->afgehandeld_op = $row['afgehandeld_op'] !== null
            ? DateTime::createFromFormat('Y-m-d H:i:s', $row['afgehandeld_op'])
            : null;

        $contactFormulier->deleted_at = $row['deleted_at'] !== null
            ? DateTime::createFromFormat('Y-m-d H:i:s', $row['deleted_at'])
            : null;

        // contact_formulier_id is de kolomnaam in de database (snake_case).
        // contactFormulier_id is de propertynaam in de klasse (camelCase).
        // De cast naar int is nodig omdat PDO alle waarden als strings teruggeeft.
        $contactFormulier->contactFormulier_id = (int) $row['contact_formulier_id'];

        return $contactFormulier;
    }

    // ---------------------------------------------------------------
    // Getters
    // ---------------------------------------------------------------

    public function getContactFormulierId(): ?int
    {
        return $this->contactFormulier_id;
    }

    public function getKlantId(): ?int
    {
        return $this->klant_id;
    }

    public function getVoornaam(): string
    {
        return $this->voornaam;
    }

    public function getAchternaam(): string
    {
        return $this->achternaam;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTelefoonnummer(): ?string
    {
        return $this->telefoonnummer;
    }

    public function getBericht(): string
    {
        return $this->bericht;
    }

    public function getVerzondenOp(): DateTime
    {
        return $this->verzonden_op;
    }

    public function getAfgehandeldOp(): ?DateTime
    {
        return $this->afgehandeld_op;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deleted_at;
    }

    // ---------------------------------------------------------------
    // Setters
    // ---------------------------------------------------------------

    /**
     * Markeert het formulier als afgehandeld door de afhandeldatum in te stellen.
     */
    public function setAfgehandeldOp(DateTime $afgehandeld_op): void
    {
        $this->afgehandeld_op = $afgehandeld_op;
    }

    /**
     * Soft delete: zet deleted_at op een tijdstip om het formulier als verwijderd te markeren.
     * Het record blijft in de database maar wordt gefilterd in queries via WHERE deleted_at IS NULL.
     */
    public function setDeletedAt(DateTime $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }

    /**
     * Telefoonnummer kan worden bijgewerkt na aanmaken.
     */
    public function setTelefoonnummer(string $telefoonnummer): void
    {
        $this->telefoonnummer = $telefoonnummer;
    }

    /**
     * E-mailadres kan worden bijgewerkt na aanmaken.
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
