<?php

namespace Tests\Unit;

use App\DAO\ContactFormulierDAO;
use App\Models\ContactFormulier;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class ContactFormulierDAOTest extends TestCase
{
    // Herbruikbare databaserij die fromDatabase() verwacht
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

    // --- contactFormulierOpslaan ---

    public function testContactFormulierOpslaan(): void
    {
        // Nep statement aanmaken en controleren dat execute() wordt aangeroepen
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);

        $formulier = new ContactFormulier('Anna', 'de Vries', 'anna@example.com', 'Hallo');
        $dao->contactFormulierOpslaan($formulier);
    }

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

    // --- getOpenFormulieren ---

    public function testGetOpenFormulieren(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            $this->maakDatabaseRij(),
            $this->maakDatabaseRij(['contact_formulier_id' => 2, 'email' => 'bob@example.com']),
        ]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);

        $resultaat = $dao->getOpenFormulieren();

        $this->assertCount(2, $resultaat);
        $this->assertInstanceOf(ContactFormulier::class, $resultaat[0]);
    }

    public function testGetOpenFormulierenGeeftLegeArray(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);

        $resultaat = $dao->getOpenFormulieren();

        $this->assertIsArray($resultaat);
        $this->assertEmpty($resultaat);
    }

    // --- getFormulierById ---

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

    public function testGetFormulierByIdGeeftNull(): void
    {
        // fetch() geeft false terug — id bestaat niet
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn(false);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);

        $formulier = $dao->getFormulierById(999);

        $this->assertNull($formulier);
    }

    // --- deleteContactFormulier ---

    public function testDeleteContactFormulier(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);
        $dao->deleteContactFormulier(1);
    }

    // --- setAfgehandeld ---

    public function testSetAfgehandeld(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);
        $dao->setAfgehandeld(1);
    }

    // --- updateEmail ---

    public function testUpdateEmail(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);
        $dao->updateEmail(1, 'nieuw@example.com');
    }

    // --- updateTelefoonnummer ---

    public function testUpdateTelefoonnummer(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);
        $dao->updateTelefoonnummer(1, '0698765432');
    }

    public function testUpdateTelefoonnummerMetNull(): void
    {
        // telefoonnummer mag null zijn — controleer dat null ook werkt
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $dao = new ContactFormulierDAO($mockPdo);
        $dao->updateTelefoonnummer(1, null);
    }

    // --- getAllContactFormulieren ---

    public function testGetAllContactFormulieren(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            $this->maakDatabaseRij(),
            $this->maakDatabaseRij([
                'contact_formulier_id' => 2,
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
