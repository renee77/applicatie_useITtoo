<?php

namespace Tests\Unit;

use App\Models\Beheer;
use App\Models\Beheerdersrol;
use App\Models\Account;
// use App\Models\AccountType;
use PHPUnit\Framework\TestCase;

class BeheerTest extends TestCase
{
    private Beheer $beheerder;

    protected function setUp(): void
    {
        $this->beheerder = new Beheer(
            Beheerdersrol::klantenservice,
            new \DateTime('2025-09-01'),
            "beheerder@beheer.com",
            "BeheerKlantenservice",
            "Hashwachtwoord",
            new \DateTime('2026-05-13'),
            new \DateTime('2000-11-18')
        );
        // AccountType::beheerder);
    }

    // Happy Path Tests
    public function testConstructorSetsRol(): void
    {
        $role = $this->beheerder->getRol();
        $this->assertEquals(Beheerdersrol::klantenservice, $role);
    }

    public function testConstructorSetsDatumInDienst(): void
    {
        $date = $this->beheerder->getDatumInDienst();
        $this->assertEquals(new \DateTime('2025-09-01'), $date);
    }

    // public function testSetRol(): void
    // {
    //   $andereRol = Beheerdersrol::voorraadbeheerder;
    //   $this->beheerder->setRol($andereRol);
    //   $rol = $this->beheerder->getRol();

    //   $this->assertEquals($andereRol, $rol);
    // }
}
