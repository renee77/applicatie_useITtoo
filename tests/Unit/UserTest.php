<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User(
            "Jan Jansen",
            "jan@jansen.nl",
            30,
            "06 12 34 56 78"
        );
    }

    // Happy path — constructor
    public function testConstructorSetsNaam(): void
    {
        $this->assertEquals("Jan Jansen", $this->user->getNaam());
    }

    public function testConstructorSetsEmail(): void
    {
        $this->assertEquals("jan@jansen.nl", $this->user->getEmail());
    }

    public function testConstructorSetsLeeftijd(): void
    {
        $this->assertEquals(30, $this->user->getLeeftijd());
    }

    public function testConstructorSetsTelefoon(): void
    {
        $this->assertEquals("06 12 34 56 78", $this->user->getTelefoon());
    }

    public function testTelefoonKanNullZijn(): void
    {
        $userZonderTelefoon = new User("Piet Pietersen", "piet@piet.nl", 25);
        $this->assertNull($userZonderTelefoon->getTelefoon());
    }

    // Happy path — setters
    public function testSetTelefoon(): void
    {
        $this->user->setTelefoon("06 99 88 77 66");
        $this->assertEquals("06 99 88 77 66", $this->user->getTelefoon());
    }

    public function testSetEmail(): void
    {
        $this->user->setEmail("nieuw@email.nl");
        $this->assertEquals("nieuw@email.nl", $this->user->getEmail());
    }

    // Exception path
    public function testKorteNaamGooitException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Naam moet minimaal 2 karakters zijn.");
        new User("A", "a@a.nl", 20);
    }

    public function testNegatieveLeeftijdGooitException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Leeftijd moet tussen 0 en 150 zijn.");
        new User("Jan", "jan@jan.nl", -1);
    }

    public function testTeHogeLeeftijdGooitException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Leeftijd moet tussen 0 en 150 zijn.");
        new User("Jan", "jan@jan.nl", 151);
    }
}
