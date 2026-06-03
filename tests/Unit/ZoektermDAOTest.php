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
        $this->expectNotToPerformAssertions();

        $zoektermDao->opslaanZoekterm($zoekterm);

    }

    public function testVerhoogAantal(): void
    {
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->expects($this->once())
            ->method('execute');

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $zoekterm = new Zoekterm('wortel');
        $zoektermDao = new ZoektermDAO($mockPdo);

        $this->expectNotToPerformAssertions();

        $zoektermDao->verhoogAantal($zoekterm);
    }
}
