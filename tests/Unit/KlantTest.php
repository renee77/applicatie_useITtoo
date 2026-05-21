<?php

namespace Tests\Unit;

use App\Models\Klant;
use DateTime;
use PHPUnit\Framework\TestCase;

class KlantTest extends TestCase
{
    private Klant $klant;

    protected function setUp(): void
    {
        $this->klant = new Klant(
            "eva@gmail.com",
            "Eva",
            "mijn_wachtwoord",
            new \DateTime('2026-05-19'),
            new \DateTime('1977-06-02'),
            "Eva",
            "Bouwman",
            "0123456789"
        );
    }

    public function testConstructorSetsStartdatumLidmaatschap(): void
    {
        $datum = $this->klant->getStartdatumLidmaatschap();

        $this->assertInstanceOf(DateTime::class, $datum);
        $this->assertLessThanOrEqual(new DateTime(), $datum);
    }

    // test toegvoegd om te valideren dat de parentconstructor wordt aangeroepen
    // in de constructor
    public function testExceptionMessageGebruikersnaam(): void
    {
        // Verwacht een exception
        $this->expectException(\InvalidArgumentException::class);
        // Verwacht deze message
        $this->expectExceptionMessage(
            "Gebruikersnaam moet langer als 2 karakters zijn."
        );

        // Te korte gebruikers naam
        new Klant(
            'eva@eva.nl',
            'EB',
            'mijnwachtwoord',
            new \DateTime(),
            new \DateTime('1980-01-01'),
            "bouwman",
            "eva",
            '0123456789'
        );

    }
}
