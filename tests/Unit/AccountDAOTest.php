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
      'wachtwoord' => '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA001',
      'created_at' => null,
      'geboortedatum' => '1985-03-21',
      'type' => 'klant',
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
      'wachtwoord' => '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA001',
      'created_at' => null,
      'geboortedatum' => '1985-03-21',
      'type' => 'klant',
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
}