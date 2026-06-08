<?php

namespace Tests\Unit;

use App\DAO\ContactFormulierDAO;
use App\Models\ContactFormulier;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class ContactFormulierDAOTest extends TestCase
{
    /**
     * Hulpmethode die een standaard databaserij aanmaakt zoals fromDatabase() die verwacht.
     *
     * array_merge combineert twee arrays. Als dezelfde sleutel in beide arrays voorkomt,
     * wint de tweede array. Zo kun je in een test één veld aanpassen zonder de hele
     * array opnieuw te schrijven:
     *
     *   $this->maakDatabaseRij(['email' => 'ongeldig@'])
     *
     * geeft de standaardrij terug maar met een ander e-mailadres.
     *
     * @param array $overschrijf Velden die afwijken van de standaardwaarden.
     */
    private function maakDatabaseRij(array $overschrijf = []): array
    {
        return array_merge([
            'contact_formulier_id' => 1,
            'voornaam'             => 'Anna',
            'achternaam'           => 'de Vries',
            'email'                => 'anna@example.com',
            'bericht'              => 'Hallo, ik heb een vraag.',
            'telefoonnummer'       => '0612345678',
            'klant_id'             => null,
            'verzonden_op'         => '2026-06-01 10:00:00',
            'afgehandeld_op'       => null,
            'deleted_at'           => null,
        ], $overschrijf);
    }

    // ---------------------------------------------------------------
    // contactFormulierOpslaan
    // ---------------------------------------------------------------

    /**
     * Controleert dat execute() wordt aangeroepen bij een formulier zonder optionele velden.
     * We mocken PDO en PDOStatement zodat er geen echte databaseverbinding nodig is.
     * Een mock vervangt een echt object door een nep-versie die we kunnen besturen in de test.
     */
    public function testContactFormulierOpslaan(): void
    {
        // createMock maakt een nep-PDOStatement aan.
        // expects($this->once()) controleert dat execute() precies één keer wordt aangeroepen.
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        // De nep-PDO geeft altijd het nep-statement terug bij prepare().
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);

        $formulier = new ContactFormulier('Anna', 'de Vries', 'anna@example.com', 'Hallo');
        $dao->contactFormulierOpslaan($formulier);
    }

    /**
     * Controleert dat opslaan ook werkt als telefoonnummer en klant_id zijn ingevuld.
     * Dit test het pad waarbij de nullable velden een waarde hebben in plaats van null.
     */
    public function testContactFormulierOpslaanMetTelefoonnummerEnKlantId(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);

        // Formulier mét optionele velden ingevuld
        $formulier = new ContactFormulier('Anna', 'de Vries', 'anna@example.com', 'Hallo', '0612345678', 5);
        $dao->contactFormulierOpslaan($formulier);
    }

    // ---------------------------------------------------------------
    // getOpenFormulieren
    // ---------------------------------------------------------------

    /**
     * Controleert dat getOpenFormulieren() een array van ContactFormulier objecten teruggeeft.
     * fetchAll() geeft twee ruwe rijen terug; de methode moet die omzetten via fromDatabase().
     */
    public function testGetOpenFormulieren(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        // willReturn stuurt de opgegeven waarde terug wanneer fetchAll() wordt aangeroepen.
        $stmt->method('fetchAll')->willReturn([
            $this->maakDatabaseRij(),
            $this->maakDatabaseRij(['contact_formulier_id' => 2, 'email' => 'bob@example.com']),
        ]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);

        $resultaat = $dao->getOpenFormulieren();

        // assertCount controleert dat er precies 2 objecten in de array zitten.
        $this->assertCount(2, $resultaat);
        // assertInstanceOf controleert dat het eerste element een ContactFormulier object is.
        $this->assertInstanceOf(ContactFormulier::class, $resultaat[0]);
    }

    /**
     * Controleert dat getOpenFormulieren() een lege array teruggeeft als er geen resultaten zijn.
     * Dit test het randgeval waarbij de database geen overeenkomende rijen heeft.
     */
    public function testGetOpenFormulierenGeeftLegeArray(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);

        $resultaat = $dao->getOpenFormulieren();

        // assertIsArray controleert dat de return value een array is, ook als die leeg is.
        $this->assertIsArray($resultaat);
        $this->assertEmpty($resultaat);
    }

    // ---------------------------------------------------------------
    // getFormulierById
    // ---------------------------------------------------------------

    /**
     * Controleert dat getFormulierById() een ContactFormulier object teruggeeft
     * en dat de waarden correct worden ingeladen via fromDatabase().
     */
    public function testGetFormulierById(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn($this->maakDatabaseRij());

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);

        $formulier = $dao->getFormulierById(1);

        $this->assertInstanceOf(ContactFormulier::class, $formulier);
        $this->assertEquals('Anna', $formulier->getVoornaam());
    }

    /**
     * Controleert dat getFormulierById() null teruggeeft als het id niet bestaat.
     * fetch() geeft false terug bij geen resultaat — de methode moet dat opvangen.
     */
    public function testGetFormulierByIdGeeftNull(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        // false simuleert een lege databaseresultaat — het id bestaat niet.
        $stmt->method('fetch')->willReturn(false);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);

        $formulier = $dao->getFormulierById(999);

        $this->assertNull($formulier);
    }

    // ---------------------------------------------------------------
    // deleteContactFormulier
    // ---------------------------------------------------------------

    /**
     * Controleert dat de soft delete query wordt uitgevoerd.
     * We controleren alleen dat execute() wordt aangeroepen — de SQL zelf
     * wordt niet getest omdat we geen echte database hebben.
     */
    public function testDeleteContactFormulier(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);
        $dao->deleteContactFormulier(1);
    }

    // ---------------------------------------------------------------
    // setAfgehandeld
    // ---------------------------------------------------------------

    /**
     * Controleert dat de afhandeling query wordt uitgevoerd.
     */
    public function testSetAfgehandeld(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);
        $dao->setAfgehandeld(1);
    }

    // ---------------------------------------------------------------
    // updateEmail
    // ---------------------------------------------------------------

    /**
     * Controleert dat het e-mailadres kan worden bijgewerkt.
     */
    public function testUpdateEmail(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);
        $dao->updateEmail(1, 'nieuw@example.com');
    }

    // ---------------------------------------------------------------
    // updateTelefoonnummer
    // ---------------------------------------------------------------

    /**
     * Controleert dat het telefoonnummer kan worden bijgewerkt met een waarde.
     */
    public function testUpdateTelefoonnummer(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);
        $dao->updateTelefoonnummer(1, '0698765432');
    }

    /**
     * Controleert dat null een geldige waarde is voor telefoonnummer.
     * Dit test het pad waarbij PDO::PARAM_NULL wordt gebruikt in bindValue().
     */
    public function testUpdateTelefoonnummerMetNull(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);
        $dao->updateTelefoonnummer(1, null);
    }

    // ---------------------------------------------------------------
    // getAllContactFormulieren
    // ---------------------------------------------------------------

    /**
     * Controleert dat getAllContactFormulieren() alle niet-soft-deleted formulieren teruggeeft,
     * inclusief formulieren die al zijn afgehandeld.
     */
    public function testGetAllContactFormulieren(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            $this->maakDatabaseRij(),
            $this->maakDatabaseRij([
                'contact_formulier_id' => 2,
                // afgehandeld_op is ingevuld — dit formulier is al behandeld maar nog zichtbaar
                'afgehandeld_op'       => '2026-06-02 12:00:00',
            ]),
        ]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);

        $resultaat = $dao->getAllContactFormulieren();

        $this->assertCount(2, $resultaat);
        $this->assertInstanceOf(ContactFormulier::class, $resultaat[0]);
    }

    /**
     * Controleert dat getAllContactFormulieren() een lege array teruggeeft
     * als er geen actieve formulieren in de database staan.
     */
    public function testGetAllContactFormulierenLegeArrayTerug(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);

        $resultaat = $dao->getAllContactFormulieren();

        $this->assertIsArray($resultaat);
        $this->assertEmpty($resultaat);
    }
}
