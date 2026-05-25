<?php

namespace Tests\Unit;

use App\DAO\BeheerDAO;
use App\Models\Beheer;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class BeheerDAOTest extends TestCase
{
    public function testGetBeheerByIdReturnsBeheer(): void
    {
        // nep PDO-statement en wat er terug moet worden gegeven
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn([
          'rol' => 'klantenservice',
          'datum_in_dienst' => '2023-01-10',
          'email' => 'jeroendijkstra@example.com',
          'gebruikersnaam' => 'JDijkstra',
          'wachtwoord_hash' => '$2y$12$qEiiSq6304LVLoa0wp3hw.jMe575sZ9QrePfFOObrpgV0DjnGgG9q',
          'created_at' => '2026-05-13',
          'geboortedatum' => '1980-01-30',
          // 'type' => 'beheer',
          'voornaam' => 'Jeroen',
          'achternaam' => 'Dijkstra',
          'telefoon' => '+31610000015',
          'deleted_at' => null,
          'account_id' => 15,
        ]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $beheerdao = new BeheerDAO($mockPdo);

        $beheer = $beheerdao->getById(15);

        $this->assertInstanceOf(Beheer::class, $beheer);
    }

    public function testGetBeheerByUsernameReturnsBeheer(): void
    {
        // nep PDO-statement en wat er terug moet worden gegeven
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn([
          'rol' => 'klantenservice',
          'datum_in_dienst' => '2023-01-10',
          'email' => 'jeroendijkstra@example.com',
          'gebruikersnaam' => 'JDijkstra',
          'wachtwoord_hash' => '$2y$12$qEiiSq6304LVLoa0wp3hw.jMe575sZ9QrePfFOObrpgV0DjnGgG9q',
          'created_at' => '2026-05-13',
          'geboortedatum' => '1980-01-30',
          // 'type' => 'beheer',
          'voornaam' => 'Jeroen',
          'achternaam' => 'Dijkstra',
          'telefoon' => '+31610000015',
          'deleted_at' => null,
          'account_id' => 15,
        ]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $beheerdao = new BeheerDAO($mockPdo);

        $beheer = $beheerdao->getByUsername('JDijkstra');

        $this->assertInstanceOf(Beheer::class, $beheer);
    }
}
