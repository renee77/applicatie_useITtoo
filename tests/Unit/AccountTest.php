<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Models\AccountType;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
  private Account $account;

  protected function setUp(): void 
  {
    $this->account = new Account(
      "testmail@testmail.com", 
      "TestAccount", 
      "HashWachtwoord", 
      new \DateTime('2026-05-13'), 
      new \DateTime('2005-05-12'), 
      AccountType::klant,
      "Tester",
      "Jansen",
      "06 12 34 56 78",
      null);
  }


  // Happy Path tests
  public function testConstructorSetsEmail(): void
  {
    // Haal de informatie via het gemaakte testaccount op.
    $mail = $this->account->getEmail();
    // Check of de informatie overeenkomt (Dus klopt de tekst en de getMail werking)
    $this->assertEquals("testmail@testmail.com", $mail);
  }

  public function testConstructorSetsGebruikersnaam(): void
  {
    $user = $this->account->getGebruikersnaam();
    $this->assertEquals("TestAccount", $user);
  }

  public function testConstructorSetsWachtwoord(): void
  {
    $pass = $this->account->getWachtwoord();
    $this->assertEquals("HashWachtwoord", $pass);
  }

  public function testConstructorSetsCreatedAt(): void
  {
    $created = $this->account->getCreatedAt();
    $this->assertEquals(new \DateTime('2026-05-13'), $created);
  }

  public function testConstructorSetsGeboortedatum(): void
  {
    $dob = $this->account->getGeboortedatum();
    $this->assertEquals(new \DateTime('2005-05-12'), $dob);
  }

  public function testConstructorSetsType(): void
  {
    $type = $this->account->getType();
    $this->assertEquals(AccountType::klant, $type);
  }

  public function testConstructorSetsVoornaam(): void
  {
    $firstName = $this->account->getVoornaam();
    $this->assertEquals("Tester", $firstName);
  }

  public function testConstructorSetsAchtenaam(): void
  {
    $lastName = $this->account->getAchternaam();
    $this->assertEquals("Jansen", $lastName);
  }

  public function testConstructorSetsTelefoon(): void
  {
    $phone = $this->account->getTelefoon();
    $this->assertEquals("06 12 34 56 78", $phone);
  }

  public function testConstructorSetsDeletedAt(): void
  {
    $delete = $this->account->getDeletedAt();
    $this->assertEquals(null, $delete);
  }

  // Setter TESTEN
  public function testSetVoornaam(): void
  {
    // Definieer
    $andereVoornaam = "Jan";
    // Uitvoering
    $this->account->setVoornaam($andereVoornaam);
    $voornaam = $this->account->getVoornaam();
    // Beoordelen
    $this->assertEquals($andereVoornaam, $voornaam);
  }

  public function testSetAchternaam(): void
  {
    $andereachternaam = "de Boer";
    $this->account->setAchternaam($andereachternaam);
    $achternaam = $this->account->getAchternaam();

    $this->assertEquals($andereachternaam, $achternaam);
  }

  public function testTelefoon(): void
  {
    $nieuwTel = "06 23 45 67 89";
    $this->account->setTelefoon($nieuwTel);
    $tel = $this->account->getTelefoon();

    $this->assertEquals($nieuwTel, $tel);
  }

  // Exception Path
  public function testKorteGebruikersnaam(): void
  {
    // Verwacht een exception
    $this->expectException(\InvalidArgumentException::class);
    // Verwacht deze message
    $this->expectExceptionMessage(
      "Gebruikersnaam moet langer als 2 karakters zijn.");
    
    // De foute informatie
    new Account(
      "test@test.com", 
      "AB", 
      "Wachtwoord",
      new \DateTime('2025-05-12'),
      new \DateTime('2005-05-12'),
      AccountType::klant);
  }

  // Te Jong
  public function testGeboortedatumTeJong(): void
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage("Geboortedatum moet minimaal 18 jaar geleden zijn.");

    new Account(
      "test@test.com", 
      "Appie", 
      "Wachtwoord",
      new \DateTime('2026-05-13'),
      new \DateTime('2015-05-12'), // Pas 11 jaar oud
      AccountType::klant
    );
  }
}