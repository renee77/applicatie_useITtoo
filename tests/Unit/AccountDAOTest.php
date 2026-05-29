<?php

namespace Tests\Unit;

use App\DAO\AccountDAO;
use App\Models\Account;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class AccountDAOTest extends TestCase
{
    public function testGetAccountByIdReturnsAccount(): void
    {
        // nep PDO-statement en wat er terug moet worden gegeven
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn([
          'email' => 'jan.vermeer@example.com',
          'gebruikersnaam' => 'JVermeer',
          'wachtwoord_hash' => '$2y$12$K0c.y/s6lue.cfNKj2E/qu.33q7srds1tII2FI0SKM8MWIoZ424OO',
          'created_at' => '2026-05-13',
          'geboortedatum' => '1985-03-21',
          // 'type' => 'klant',
          'voornaam' => 'Jan',
          'achternaam' => 'Vermeer',
          'telefoon' => '+31610000001',
          'deleted_at' => null,
          'account_id' => 1,
        ]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $accountdao = new AccountDAO($mockPdo);

        $account = $accountdao->getById(1);

        $this->assertInstanceOf(Account::class, $account);
    }

    public function testGetAccountByUsernameReturnsAccount(): void
    {
        // nep PDO-statement aanmaken
        $stmt = $this->createMock(PDOStatement::class);
        // Aangeven wat het terug hoort te geven
        $stmt->method('fetch')->willReturn([
          'account_id' => 1,
          'email' => 'jan.vermeer@example.com',
          'gebruikersnaam' => 'JVermeer',
          'wachtwoord_hash' => '$2y$12$K0c.y/s6lue.cfNKj2E/qu.33q7srds1tII2FI0SKM8MWIoZ424OO',
          'created_at' => '2026-05-13',
          'geboortedatum' => '1985-03-21',
          // 'type' => 'klant',
          'voornaam' => 'Jan',
          'achternaam' => 'Vermeer',
          'telefoon' => '+31610000001',
          'deleted_at' => null
        ]);

        // Een nep PDO opzetten en klaarzetten.
        $mockPdo = $this->createMock(PDO::class);
        // Aangeven wat het teruggaat geven
        $mockPdo->method('prepare')->willReturn($stmt);

        $accountdao = new AccountDAO($mockPdo);

        // De username waarmee de test hoort terug te komen.
        $account = $accountdao->getByUsername('JVermeer');

        // Kijken of deze instantie wordt geretourneerd.
        $this->assertInstanceOf(Account::class, $account);
    }

    public function testGetTypeByAccountIdReturnsBeheer(): void
    {
        // Maak een nep PDOStatement aan
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->method('fetch')->willReturn([1]);

        // Nep PDO aanmaken
        $mockPdo = $this->createMock(PDO::class);
        // prepare() wordt twee keer aangeroepen maar geeft altijd hetzelfde statement terug
        $mockPdo->method('prepare')->willReturn($stmt);

        $accountDAO = new AccountDAO($mockPdo);

        $type = $accountDAO->getTypeByAccountId(1);

        // Controleer of het teruggegeven type 'beheer' is
        $this->assertEquals('beheer', $type);
    }

    public function testGetTypeByAccountIdReturnsKlant(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        // De fetch() wordt twee keer aangeroepen — één keer per accounttype
        // Eerste aanroep: klant tabel — account staat hier niet in
        // Tweede aanroep: beheer tabel — account staat hier wel in
        $stmt->method('fetch')->willReturnOnConsecutiveCalls(
            false,
            [1]
        );
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);
        $accountDAO = new AccountDAO($mockPdo);
        $type = $accountDAO->getTypeByAccountId(1);
        $this->assertEquals('klant', $type);
    }

    // sad path
    public function testGetTypeByAccountIdReturnsNullWhenNotFound(): void
    {
        // Maak een nep PDOStatement aan
        $stmt = $this->createMock(PDOStatement::class);

        // fetch() wordt twee keer aangeroepen — één keer per accounttype
        // Beide keren geeft het false terug omdat het account
        // in geen van beide tabellen (klant én beheer) voorkomt
        $stmt->method('fetch')->willReturnOnConsecutiveCalls(
            false, // klant tabel — niet gevonden
            false  // beheer tabel — niet gevonden
        );

        // Nep PDO aanmaken
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $accountDAO = new AccountDAO($mockPdo);

        $type = $accountDAO->getTypeByAccountId(1);

        // Als het account in geen enkele tabel staat, moet null worden teruggegeven
        $this->assertNull($type);
    }

    public function testGetAccountByIdReturnsNullWhenNotFound(): void
    {
        // Nep statement aanmaken dat false teruggeeft — account bestaat niet
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn(false);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $accountDAO = new AccountDAO($mockPdo);

        $account = $accountDAO->getById(999);

        // Als het account niet bestaat, moet null worden teruggegeven
        $this->assertNull($account);
    }

    public function testGetAccountByUsernameReturnsNullWhenNotFound(): void
    {
        // Nep statement aanmaken dat false teruggeeft — gebruikersnaam bestaat niet
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn(false);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($stmt);

        $accountDAO = new AccountDAO($mockPdo);

        $account = $accountDAO->getByUsername('NietBestaandeGebruiker');

        // Als de gebruikersnaam niet bestaat, moet null worden teruggegeven
        $this->assertNull($account);
    }
}
