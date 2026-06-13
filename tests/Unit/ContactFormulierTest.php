<?php

namespace Tests\Unit;

use PHPUnit\Framework\Testcase;
use App\Models\ContactFormulier;
use DateTime;

class ContactFormulierTest extends Testcase
{
    private ContactFormulier $contactFormulier;

    protected function setUp(): void
    {
        $this->contactFormulier = new ContactFormulier(
            'Anne',
            'Visser',
            'anne@home.nl',
            'Zou ik meer informatie over het bestel
                proces van jullie mogen?',
            '0123456789'
        );
    }

    // ---------------------------------------------------------------
    // Constructor
    // ---------------------------------------------------------------

    public function testVoornaamWordtCorrectOpgeslagen(): void
    {
        $this->assertSame('Anne', $this->contactFormulier->getVoornaam());
    }

    public function testAchternaamWordtCorrectOpgeslagen(): void
    {
        $this->assertSame('Visser', $this->contactFormulier->getAchternaam());
    }

    public function testEmailWordtCorrectOpgeslagen(): void
    {
        $this->assertSame('anne@home.nl', $this->contactFormulier->getEmail());
    }

    public function testBerichtWordtCorrectOpgeslagen(): void
    {
        $this->assertStringContainsString(
            'meer informatie',
            $this->contactFormulier->getBericht()
        );
    }

    public function testTelefoonnummerWordtCorrectOpgeslagen(): void
    {
        $this->assertSame('0123456789', $this->contactFormulier->getTelefoonnummer());
    }

    public function testTelefoonnummerIsStandaardNull(): void
    {
        $formulier = new ContactFormulier('Anne', 'Visser', 'anne@home.nl', 'Bericht');
        $this->assertNull($formulier->getTelefoonnummer());
    }

    public function testKlantIdIsStandaardNull(): void
    {
        $this->assertNull($this->contactFormulier->getKlantId());
    }

    public function testContactFormulierIdIsStandaardNull(): void
    {
        $this->assertNull($this->contactFormulier->getContactFormulierId());
    }

    public function testAfgehandeldOpIsStandaardNull(): void
    {
        $this->assertNull($this->contactFormulier->getAfgehandeldOp());
    }

    public function testDeletedAtIsStandaardNull(): void
    {
        $this->assertNull($this->contactFormulier->getDeletedAt());
    }

    public function testVerzondenOpIsDateTimeInstantie(): void
    {
        $this->assertInstanceOf(DateTime::class, $this->contactFormulier->getVerzondenOp());
    }

    public function testVerzondenOpIsOngeveerNu(): void
    {
        $verschil = (new DateTime())->getTimestamp() - $this->contactFormulier->getVerzondenOp()->getTimestamp();
        $this->assertLessThan(5, abs($verschil));
    }

    public function testKlantIdWordtCorrectOpgeslagenViaConstructor(): void
    {
        $formulier = new ContactFormulier('Anne', 'Visser', 'anne@home.nl', 'Bericht', null, 42);
        $this->assertSame(42, $formulier->getKlantId());
    }

    // ---------------------------------------------------------------
    // fromDatabase
    // ---------------------------------------------------------------

    public function testFromDatabaseLaadtAlleVeldenCorrect(): void
    {
        $row = [
            'contact_formulier_id' => '7',
            'klant_id'             => '3',
            'voornaam'             => 'Jan',
            'achternaam'           => 'de Boer',
            'email'                => 'jan@example.com',
            'bericht'              => 'Testbericht',
            'telefoonnummer'       => '0612345678',
            'verzonden_op'         => '2024-03-01 09:00:00',
            'afgehandeld_op'       => '2024-03-02 14:30:00',
            'deleted_at'           => null,
        ];

        $formulier = ContactFormulier::fromDatabase($row);

        $this->assertSame(7, $formulier->getContactFormulierId());
        $this->assertSame(3, $formulier->getKlantId());
        $this->assertSame('Jan', $formulier->getVoornaam());
        $this->assertSame('de Boer', $formulier->getAchternaam());
        $this->assertSame('jan@example.com', $formulier->getEmail());
        $this->assertSame('Testbericht', $formulier->getBericht());
        $this->assertSame('0612345678', $formulier->getTelefoonnummer());
    }

    public function testFromDatabaseVerzondenOpWordtOmgezetNaarDateTime(): void
    {
        $row = [
            'contact_formulier_id' => '1',
            'klant_id'             => null,
            'voornaam'             => 'Jan',
            'achternaam'           => 'de Boer',
            'email'                => 'jan@example.com',
            'bericht'              => 'Testbericht',
            'telefoonnummer'       => null,
            'verzonden_op'         => '2024-03-01 09:00:00',
            'afgehandeld_op'       => null,
            'deleted_at'           => null,
        ];

        $formulier = ContactFormulier::fromDatabase($row);

        $this->assertInstanceOf(DateTime::class, $formulier->getVerzondenOp());
        $this->assertSame('2024-03-01 09:00:00', $formulier->getVerzondenOp()->format('Y-m-d H:i:s'));
    }

    public function testFromDatabaseAfgehandeldOpIsNullAlsNietIngevuld(): void
    {
        $row = [
            'contact_formulier_id' => '1',
            'klant_id'             => null,
            'voornaam'             => 'Jan',
            'achternaam'           => 'de Boer',
            'email'                => 'jan@example.com',
            'bericht'              => 'Testbericht',
            'telefoonnummer'       => null,
            'verzonden_op'         => '2024-03-01 09:00:00',
            'afgehandeld_op'       => null,
            'deleted_at'           => null,
        ];

        $formulier = ContactFormulier::fromDatabase($row);

        $this->assertNull($formulier->getAfgehandeldOp());
    }

    public function testFromDatabaseAfgehandeldOpWordtOmgezetNaarDateTime(): void
    {
        $row = [
            'contact_formulier_id' => '1',
            'klant_id'             => null,
            'voornaam'             => 'Jan',
            'achternaam'           => 'de Boer',
            'email'                => 'jan@example.com',
            'bericht'              => 'Testbericht',
            'telefoonnummer'       => null,
            'verzonden_op'         => '2024-03-01 09:00:00',
            'afgehandeld_op'       => '2024-03-02 14:30:00',
            'deleted_at'           => null,
        ];

        $formulier = ContactFormulier::fromDatabase($row);

        $this->assertInstanceOf(DateTime::class, $formulier->getAfgehandeldOp());
        $this->assertSame('2024-03-02 14:30:00', $formulier->getAfgehandeldOp()->format('Y-m-d H:i:s'));
    }

    public function testFromDatabaseDeletedAtWordtOmgezetNaarDateTime(): void
    {
        $row = [
            'contact_formulier_id' => '1',
            'klant_id'             => null,
            'voornaam'             => 'Jan',
            'achternaam'           => 'de Boer',
            'email'                => 'jan@example.com',
            'bericht'              => 'Testbericht',
            'telefoonnummer'       => null,
            'verzonden_op'         => '2024-03-01 09:00:00',
            'afgehandeld_op'       => null,
            'deleted_at'           => '2024-04-01 00:00:00',
        ];

        $formulier = ContactFormulier::fromDatabase($row);

        $this->assertInstanceOf(DateTime::class, $formulier->getDeletedAt());
        $this->assertSame('2024-04-01 00:00:00', $formulier->getDeletedAt()->format('Y-m-d H:i:s'));
    }

    public function testFromDatabaseContactFormulierIdWordtNaarIntGecast(): void
    {
        $row = [
            'contact_formulier_id' => '99',
            'klant_id'             => null,
            'voornaam'             => 'Jan',
            'achternaam'           => 'de Boer',
            'email'                => 'jan@example.com',
            'bericht'              => 'Testbericht',
            'telefoonnummer'       => null,
            'verzonden_op'         => '2024-03-01 09:00:00',
            'afgehandeld_op'       => null,
            'deleted_at'           => null,
        ];

        $formulier = ContactFormulier::fromDatabase($row);

        $this->assertSame(99, $formulier->getContactFormulierId());
    }

    // ---------------------------------------------------------------
    // Setters
    // ---------------------------------------------------------------

    public function testSetAfgehandeldOpSlaatDateTimeOp(): void
    {
        $tijdstip = new DateTime('2024-05-10 12:00:00');
        $this->contactFormulier->setAfgehandeldOp($tijdstip);
        $this->assertSame($tijdstip, $this->contactFormulier->getAfgehandeldOp());
    }

    public function testSetDeletedAtSlaatDateTimeOp(): void
    {
        $tijdstip = new DateTime('2024-06-01 08:00:00');
        $this->contactFormulier->setDeletedAt($tijdstip);
        $this->assertSame($tijdstip, $this->contactFormulier->getDeletedAt());
    }

    public function testSetTelefoonnummerWijzigtWaarde(): void
    {
        $this->contactFormulier->setTelefoonnummer('0698765432');
        $this->assertSame('0698765432', $this->contactFormulier->getTelefoonnummer());
    }

    public function testSetEmailWijzigtWaarde(): void
    {
        $this->contactFormulier->setEmail('nieuw@example.com');
        $this->assertSame('nieuw@example.com', $this->contactFormulier->getEmail());
    }
}
