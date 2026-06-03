<?php

namespace Tests\Unit;

use App\Models\Zoekterm;
use PHPUnit\Framework\TestCase;

class ZoektermTest extends TestCase
{
    private Zoekterm $zoekterm;

    protected function setUp(): void
    {
        $this->zoekterm = new Zoekterm("wortel");
    }

    public function testGetZoekterm(): void
    {
        $zoekterm = $this->zoekterm->getZoekterm();

        $this->assertEquals("wortel", $zoekterm);
    }

    public function testGetAantalKeerGezocht(): void
    {
        $aantalKeergezocht = $this->zoekterm->getAantalKeerGezocht();

        $this->assertEquals(1, $aantalKeergezocht);
    }

}
