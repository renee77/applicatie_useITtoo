<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Eenheid;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        $this->product = new Product("Wortel", 1.95, 2, Eenheid::Kilogram, "lekkere oranje wortels", "Pietje");
    }

    public function testConstructorSetsNaam(): void
    {
        // arange is al gebeurd door setup()

        // act
        $naam = $this->product->getNaam();
        // assert
        // $this->assertEquals() betekent: "roep de assertEquals methode aan die ik geërfd heb van TestCase
        $this->assertEquals("Wortel", $naam);
    }

    public function testConstructorSetsPrijs(): void
    {
        // act
        $prijs = $this->product->getPrijs();

        // assert
        $this->assertEquals(1.95, $prijs);
    }

    public function testConstructorSetsVerkoopGewicht(): void
    {
        // act
        $verkoopGewicht = $this->product->getVerkoopGewicht();
        // assert
        $this->assertEquals(2, $verkoopGewicht);
    }

    public function testConstructorSetsOmschrijving(): void
    {
        // act
        $omschrijving = $this->product->getOmschrijving();
        // assert
        $this->assertEquals("lekkere oranje wortels", $omschrijving);
    }

    public function testConstructorSetsLeverancier(): void
    {
        // act
        $leverancier = $this->product->getLeverancier();
        // assert
        $this->assertEquals("Pietje", $leverancier);
    }

    public function testConstructorSetsEenheid(): void
    {
        // act
        $eenheid = $this->product->getEenheid();
        // assert
        $this->assertEquals(Eenheid::Kilogram, $eenheid);
    }

    public function testConstructorSetsDeletedAt(): void
    {
        // act
        $delete_at = $this->product->getDeletedAt();
        // assert
        $this->assertNull($delete_at, "deleted_at moet null zijn bij het aanmaken van een product");
    }

    public function testConstructorSetsFotoUrlAsNull(): void
    {
        // act
        $foto_url = $this->product->getFotoUrl();
        // assert
        $this->assertNull($foto_url);
    }

    public function testConstructorSetsFotoUrlWithValue(): void
    {
        // arange
        $productWithFotoUrl = new Product("Wortel", 1.95, 2, Eenheid::Kilogram, "lekkere oranje wortels", "Pietje", "url_naar_foto");
        // act
        $foto_url = $productWithFotoUrl->getFotoUrl();
        // assert
        $this->assertEquals("url_naar_foto", $foto_url);
    }

    // de setters testen
    public function testSetNaam(): void
    {
        // arange
        $nieuweNaam = "Appels";
        // act
        $this->product->setNaam($nieuweNaam);
        // assert
        $this->assertEquals($nieuweNaam, $this->product->getNaam());
    }

    public function testSetPrijs(): void
    {
        // arange
        $nieuwePrijs = 2.50;
        //act
        $this->product->setPrijs($nieuwePrijs);
        // assert
        $this->assertEquals($nieuwePrijs, $this->product->getPrijs());
    }

    public function testSetVerkoopGewicht(): void
    {
        // arrange
        $nieuwVerkoopGewicht = 3.50;
        // act
        $this->product->setVerkoopGewicht($nieuwVerkoopGewicht);
        // assert
        $this->assertEquals($nieuwVerkoopGewicht, $this->product->getVerkoopGewicht());
    }

    public function testSetOmschrijving(): void
    {
        // arrange
        $nieuweOmschrijving = "Verse biologische wortels";
        // act
        $this->product->setOmschrijving($nieuweOmschrijving);
        // assert
        $this->assertEquals($nieuweOmschrijving, $this->product->getOmschrijving());
    }

    public function testSetLeverancier(): void
    {
        // arrange
        $nieuweLeverancier = "Boer Piet";
        // act
        $this->product->setLeverancier($nieuweLeverancier);
        // assert
        $this->assertEquals($nieuweLeverancier, $this->product->getLeverancier());
    }

    public function testSetFotoUrl(): void
    {
        // arrange
        $nieuweFotoUrl = "nieuwe_url_naar_foto";
        // act
        $this->product->setFotoUrl($nieuweFotoUrl);
        // assert
        $this->assertEquals($nieuweFotoUrl, $this->product->getFotoUrl());
    }

    public function testSetEenheid(): void
    {
        // arange
        $nieuweEenheid = Eenheid::Stuks;
        //act
        $this->product->setEenheid($nieuweEenheid);
        // assert
        $this->assertEquals($nieuweEenheid, $this->product->getEenheid());

    }

    // randgevallen
    // Naam goed ingevuld
    public function testNaamMagNietLeegZijn(): void
    {
        // vertel PHPUnit EERST dat je een exception verwacht
        $this->expectException(\InvalidArgumentException::class);

        // daarna de actie die de exception moet gooien
        new Product("", 3, 2, Eenheid::Kilogram, null, null);
    }

    public function testNaamMagNietMinderDanTweeKaraktersHebben(): void
    {
        // vertel PHPUnit EERST dat je een exception verwacht
        $this->expectException(\InvalidArgumentException::class);

        // daarna de actie die de exception moet gooien
        new Product("x", 3, 2, Eenheid::Kilogram, null, null);
    }

    public function testSetNaamOpMeerDanTweeKarakters(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->product->setNaam("x");
    }
    public function testSetNaamNietLeeg(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->product->setNaam("");
    }

    // prijs hoger dan 0
    public function testPrijsNietNul(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Product("Appel", 0, 2, Eenheid::Kilogram, null, null);
    }
    public function testPrijsNietNegatief(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Product("Appel", -3, 2, Eenheid::Kilogram, null, null);
    }

    public function testSetPrijsNietNul(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->product->setPrijs(0);
    }
    public function testSetPrijsNietNegatief(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->product->setPrijs(-3);
    }

    public function testGewichtNietNul(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Product("Appel", 3, 0, Eenheid::Kilogram, null, null);
    }
    public function testGewichtNietNegatief(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Product("Appel", 3, -6, Eenheid::Kilogram, null, null);
    }
    public function testSetVerkoopGewichtNietNul(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->product->setVerkoopGewicht(0);
    }
    public function testSetVerkoopGewichtNietNegatief(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->product->setVerkoopGewicht(-5);
    }
}
