<?php

namespace Tests\Unit;

use App\DAO\ZoektermDAO;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;
use App\Models\Zoekterm;

class ZoektermDAOTest extends TestCase
{
    public function testBestaatZoekterm(): void
    {
        $zoekterm = new Zoekterm("wortel");

        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->method('fetch')
            ->willReturn(['zoekterm' => 'wortel', 'aantal' => 1]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $zoektermDao = new ZoektermDAO($mockPdo);
        $result = $zoektermDao->bestaatZoekterm($zoekterm->getZoekterm());
        $this->assertTrue($result);
    }

    public function testBestaatZoektermNiet(): void
    {
        $zoekterm = new Zoekterm("wortel");

        $mockStmt = $this->createMock(PDOStatement::class);
        // fetch() returned false als er geen rij gevonden is in de database
        $mockStmt->method('fetch')
            ->willReturn(false);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $zoektermDao = new ZoektermDAO($mockPdo);
        $result = $zoektermDao->bestaatZoekterm($zoekterm->getZoekterm());

        // (bool)false = false, dus de zoekterm bestaat niet
        $this->assertFalse($result);
    }

    public function testOpslaanZoekterm(): void
    {
        $zoekterm = new Zoekterm("wortel");

        $mockStmt = $this->createMock(PDOStatement::class);
        // Dit zegt: "ik verwacht dat execute() precies één keer aangeroepen wordt".
        // Als dat niet gebeurt faalt de test!
        $mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $zoektermDao = new ZoektermDAO($mockPdo);

        // de check zit al in dat de methode 1x uitgevoerd wordt

        $zoektermDao->opslaanZoekterm($zoekterm);
    }

    public function testVerhoogAantal(): void
    {
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->expects($this->once())
            ->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $zoekterm = 'wortel';
        $zoektermDao = new ZoektermDAO($mockPdo);

        $zoektermDao->verhoogAantal($zoekterm);
    }

    // Happy path: de database bevat zoektermen.
    // Verwacht: een array met de juiste zoektermen en aantallen
    public function testGetAlleGeeftArrayTerug(): void
    {
        // Creeër een mockstatement met twee zoektermen die meerdere keren zijn gezocht
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->method('fetchAll')->willReturn([
            ['zoekterm' => 'wortel', 'aantal' => 5],
            ['zoekterm' => 'aardbei', 'aantal' => 3],
        ]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        // Nieuwe dao aanmaken met behulp van de MockPDO
        $dao = new ZoektermDAO($mockPdo);
        $result = $dao->getAlle();

        // Controleer of er een array terugkomt
        $this->assertIsArray($result);
        // Controleer of er twee zoektermen in zitten
        $this->assertCount(2, $result);
        // Controleer of de juiste zoekterm erin zit
        $this->assertEquals('wortel', $result[0]['zoekterm']);
        // Controleer of het aantal klopt
        $this->assertEquals(5, $result[0]['aantal']);
    }

    // Sad path: de database bevat geen zoektermen.
    // Verwacht: een lege array — geen crash of foutmelding.
    public function testGetAlleGeeftLegeArrayAlsErGeenZoektermenZijn(): void
    {
        $mockStmt = $this->createMock(PDOStatement::class);
        // fetchAll() geeft een lege array terug als er geen rijen zijn
        $mockStmt->method('fetchAll')->willReturn([]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $dao = new ZoektermDAO($mockPdo);
        $result = $dao->getAlle();

        // Controleer of het resultaat een array is
        $this->assertIsArray($result);
        // Controleer of de array leeg is
        $this->assertEmpty($result);
    }

    // Happy path: een bestaande zoekterm wordt verwijderd.
    // Verwacht: de query wordt uitgevoerd met de juiste zoekterm.
    public function testVerwijderZoektermVoertQueryUit(): void
    {
        $mockStmt = $this->createMock(PDOStatement::class);

        // expects($this->once()) controleert dat execute() precies één keer
        // wordt aangeroepen om zo zeker te zijn dat het wordt uigevoerd
        $mockStmt->expects($this->once())->method('execute');

        // Controleer dat de juiste zoekterm aan de query wordt meegegeven
        $mockStmt->expects($this->once())->method('bindValue')
            ->with(':zoekterm', 'wortel');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $dao = new ZoektermDAO($mockPdo);

        // Geen exception verwacht — de methode geeft void terug
        $dao->verwijderZoekterm('wortel');
    }

    //  Sad path: een lege string wordt meegegeven als zoekterm.
     // De database zal geen rijen verwijderen maar dat is geen fout in de DAO.
    public function testVerwijderZoektermMetLegeStringVoertNogSteedsQueryUit(): void
    {
        $mockStmt = $this->createMock(PDOStatement::class);

        // De query wordt nog steeds uitgevoerd, ook met een lege string
        $mockStmt->expects($this->once())->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $dao = new ZoektermDAO($mockPdo);

        // Lege string is een edge case — de DAO hoeft dit niet te valideren,
        // dat is de verantwoordelijkheid van de controller
        $dao->verwijderZoekterm('');
    }
}
